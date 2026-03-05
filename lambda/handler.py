import json
import os
import subprocess
import math
import time
import boto3

s3 = boto3.client('s3')


def log(msg):
    print(f"[convertportrait] {msg}", flush=True)


def lambda_handler(event, context):
    job_id = event['jobId']
    bucket = event['bucket']
    start_time = time.time()

    log(f"START job={job_id} bucket={bucket}")

    input_key = f"input/{job_id}.mp4"
    output_key = f"output/{job_id}.mp4"
    status_key = f"status/{job_id}.json"

    input_path = f"/tmp/{job_id}-input.mp4"
    output_path = f"/tmp/{job_id}-output.mp4"

    try:
        # Read job config from status file
        update_status(bucket, status_key, 'downloading', 5)
        status_obj = s3.get_object(Bucket=bucket, Key=status_key)
        config = json.loads(status_obj['Body'].read())
        template = config['template']
        options = config.get('options', {})
        log(f"Config: template={template} options={options}")

        # Download input
        s3.download_file(bucket, input_key, input_path)
        file_size = os.path.getsize(input_path)
        log(f"Downloaded input: {file_size / 1024 / 1024:.1f} MB in {time.time() - start_time:.1f}s")
        update_status(bucket, status_key, 'probing', 15)

        # Probe dimensions
        probe = subprocess.run(
            ['ffprobe', '-v', 'error', '-select_streams', 'v:0',
             '-show_entries', 'stream=width,height', '-of', 'csv=p=0', input_path],
            capture_output=True, text=True
        )
        dims = probe.stdout.strip().split(',')
        width, height = int(dims[0]), int(dims[1])
        log(f"Probe: {width}x{height}")

        # Build filter
        vf = build_filter(template, width, height, options)
        log(f"Filter: {vf[:200]}")
        update_status(bucket, status_key, 'processing', 20)

        # Get duration for progress tracking
        duration = get_duration(input_path)
        log(f"Duration: {duration:.1f}s")

        # Run FFmpeg with progress output
        cmd = [
            'ffmpeg', '-y', '-i', input_path,
            '-vf', vf,
            '-c:v', 'libx264', '-preset', 'ultrafast', '-crf', '23',
            '-c:a', 'aac', '-b:a', '128k',
            '-movflags', '+faststart',
            '-aspect', '16:9',
            '-metadata:s:v', 'rotate=0',
            '-progress', 'pipe:1',
            output_path
        ]
        log(f"FFmpeg cmd: {' '.join(cmd[:8])}...")

        ffmpeg_start = time.time()
        process = subprocess.Popen(
            cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE,
            universal_newlines=True
        )

        last_pct = 20
        for line in process.stdout:
            if line.startswith('out_time_ms='):
                try:
                    time_ms = int(line.strip().split('=')[1])
                    if duration > 0:
                        pct = min(85, 20 + int((time_ms / (duration * 1_000_000)) * 65))
                        if pct > last_pct + 4:
                            elapsed = time.time() - ffmpeg_start
                            log(f"Progress: {pct}% (time_ms={time_ms}, elapsed={elapsed:.1f}s)")
                            update_status(bucket, status_key, 'processing', pct)
                            last_pct = pct
                except (ValueError, ZeroDivisionError):
                    pass

        process.wait()
        ffmpeg_elapsed = time.time() - ffmpeg_start
        log(f"FFmpeg finished: returncode={process.returncode} elapsed={ffmpeg_elapsed:.1f}s")

        if process.returncode != 0:
            stderr = process.stderr.read()
            log(f"FFmpeg STDERR: {stderr[:1000]}")
            raise Exception(f"FFmpeg failed: {stderr[:500]}")

        output_size = os.path.getsize(output_path)
        log(f"Output file: {output_size / 1024 / 1024:.1f} MB")

        # Upload result
        update_status(bucket, status_key, 'uploading', 90)
        upload_start = time.time()
        s3.upload_file(
            output_path, bucket, output_key,
            ExtraArgs={'ContentType': 'video/mp4'}
        )
        log(f"Uploaded result in {time.time() - upload_start:.1f}s")

        update_status(bucket, status_key, 'completed', 100)
        total = time.time() - start_time
        log(f"DONE job={job_id} total={total:.1f}s")

    except Exception as e:
        log(f"ERROR job={job_id}: {e}")
        update_status(bucket, status_key, 'failed', 0, str(e))
        raise
    finally:
        for f in [input_path, output_path]:
            if os.path.exists(f):
                os.remove(f)

    return {'statusCode': 200}


def update_status(bucket, key, status, progress, error=None):
    try:
        obj = s3.get_object(Bucket=bucket, Key=key)
        data = json.loads(obj['Body'].read())
    except Exception:
        data = {}

    data['status'] = status
    data['progress'] = progress
    if error:
        data['error'] = error

    s3.put_object(
        Bucket=bucket, Key=key,
        Body=json.dumps(data),
        ContentType='application/json'
    )


def get_duration(path):
    result = subprocess.run(
        ['ffprobe', '-v', 'error', '-show_entries', 'format=duration',
         '-of', 'default=noprint_wrappers=1:nokey=1', path],
        capture_output=True, text=True
    )
    try:
        return float(result.stdout.strip())
    except ValueError:
        return 0


def build_filter(template, width, height, options):
    out_w, out_h = 1920, 1080
    fg_h = out_h
    fg_w = int(math.ceil((width * out_h / height) / 2) * 2)
    pad_x = f"({out_w}-{fg_w})/2"

    if template == 'blurred':
        return (
            f"split[fg][bg];"
            f"[bg]scale=480:270,boxblur=20:20,scale={out_w}:{out_h}[blurred];"
            f"[fg]scale={fg_w}:{fg_h}[sharp];"
            f"[blurred][sharp]overlay={pad_x}:0"
        )

    elif template == 'gradient':
        fr = options.get('gradientFrom', 'f97316').lstrip('#')
        to = options.get('gradientTo', '9333ea').lstrip('#')
        return (
            f"color=c=0x{fr}:s={out_w}x{out_h}[left];"
            f"color=c=0x{to}:s={out_w}x{out_h}[right];"
            f"[left][right]blend=all_mode=addition:all_opacity=0.5[bg];"
            f"[0:v]scale={fg_w}:{fg_h}[fg];"
            f"[bg][fg]overlay={pad_x}:0:shortest=1"
        )

    elif template == 'solid':
        hex_c = options.get('solidColor', '000000').lstrip('#')
        return (
            f"color=c=0x{hex_c}:s={out_w}x{out_h}[bg];"
            f"[0:v]scale={fg_w}:{fg_h}[fg];"
            f"[bg][fg]overlay={pad_x}:0:shortest=1"
        )

    elif template == 'pattern':
        hex_c = options.get('patternColor', '2dd4bf').lstrip('#')
        pattern_type = options.get('patternType', 'dots')
        drawboxes = ''

        if pattern_type == 'lines':
            for i in range(0, out_w, 40):
                drawboxes += f",drawbox=x={i}:y=0:w=2:h={out_h}:color=0x{hex_c}@0.3:t=fill"
        elif pattern_type == 'chevrons':
            for i in range(0, out_w, 60):
                drawboxes += f",drawbox=x={i}:y=0:w=30:h={out_h}:color=0x{hex_c}@0.15:t=fill"
        else:
            for y in range(0, out_h, 30):
                for x in range(0, out_w, 30):
                    drawboxes += f",drawbox=x={x}:y={y}:w=4:h=4:color=0x{hex_c}@0.4:t=fill"
            drawboxes = drawboxes[:5000]

        return (
            f"color=c=0x111111:s={out_w}x{out_h}{drawboxes}[bg];"
            f"[0:v]scale={fg_w}:{fg_h}[fg];"
            f"[bg][fg]overlay={pad_x}:0:shortest=1"
        )

    else:
        return (
            f"split[fg][bg];"
            f"[bg]scale=480:270,boxblur=20:20,scale={out_w}:{out_h}[blurred];"
            f"[fg]scale={fg_w}:{fg_h}[sharp];"
            f"[blurred][sharp]overlay={pad_x}:0"
        )
