<?php

namespace App\Http\Controllers;

use App\Models\ProUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class VideoController extends Controller
{
    public function process(Request $request)
    {
        // Verify Pro status
        $email = $request->header('X-Pro-Email');
        $proUser = $email ? ProUser::where('email', strtolower($email))->first() : null;
        if (!$proUser || !$proUser->isActive()) {
            return response()->json(['error' => 'Pro access required'], 403);
        }

        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/quicktime,video/webm,video/x-msvideo|max:204800',
            'template' => 'required|string|in:blurred,gradient,solid,pattern',
            'options' => 'nullable|json',
        ]);

        $jobId = Str::uuid()->toString();
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $inputPath = $tempDir . '/' . $jobId . '-input.mp4';
        $outputPath = $tempDir . '/' . $jobId . '-output.mp4';

        try {
            // Save uploaded file
            $request->file('video')->move($tempDir, $jobId . '-input.mp4');

            // Probe video dimensions
            $probe = new Process(['ffprobe', '-v', 'error', '-select_streams', 'v:0',
                '-show_entries', 'stream=width,height', '-of', 'csv=p=0', $inputPath]);
            $probe->run();

            if (!$probe->isSuccessful()) {
                return response()->json(['error' => 'Could not read video file'], 422);
            }

            $dims = explode(',', trim($probe->getOutput()));
            $width = (int) $dims[0];
            $height = (int) $dims[1];

            // Build filter
            $options = json_decode($request->input('options', '{}'), true) ?: [];
            $filter = $this->buildFilter($request->input('template'), $width, $height, $options);

            // Run FFmpeg
            $ffmpeg = new Process([
                'ffmpeg', '-y',
                '-i', $inputPath,
                '-vf', $filter,
                '-c:v', 'libx264', '-preset', 'ultrafast', '-crf', '23',
                '-c:a', 'aac', '-b:a', '128k',
                '-movflags', '+faststart',
                $outputPath,
            ]);
            $ffmpeg->setTimeout(600);
            $ffmpeg->run();

            if (!$ffmpeg->isSuccessful()) {
                return response()->json([
                    'error' => 'Video processing failed',
                    'details' => $ffmpeg->getErrorOutput(),
                ], 500);
            }

            // Stream result back
            return response()->download($outputPath, 'converted-landscape.mp4', [
                'Content-Type' => 'video/mp4',
            ])->deleteFileAfterSend(true);
        } finally {
            // Cleanup input file
            if (file_exists($inputPath)) {
                @unlink($inputPath);
            }
        }
    }

    private function buildFilter(string $template, int $width, int $height, array $options): string
    {
        // Always target 1920x1080
        $outH = 1080;
        $outW = 1920;

        // Scale foreground to fit height, maintain aspect ratio
        $fgH = $outH;
        $fgW = (int) (ceil(($width * $outH / $height) / 2) * 2);
        $padX = "({$outW}-{$fgW})/2";

        switch ($template) {
            case 'blurred':
                return "split[fg][bg];[bg]scale={$outW}:{$outH}:force_original_aspect_ratio=increase,crop={$outW}:{$outH},boxblur=40:40[blurred];[fg]scale={$fgW}:{$fgH}[sharp];[blurred][sharp]overlay={$padX}:0";

            case 'gradient':
                $from = ltrim($options['gradientFrom'] ?? 'f97316', '#');
                $to = ltrim($options['gradientTo'] ?? '9333ea', '#');
                return "color=c=0x{$from}:s={$outW}x{$outH}:d=1[left];color=c=0x{$to}:s={$outW}x{$outH}:d=1[right];[left][right]blend=all_mode=addition:all_opacity=0.5[bg];[0:v]scale={$fgW}:{$fgH}[fg];[bg][fg]overlay={$padX}:0:shortest=1";

            case 'solid':
                $hex = ltrim($options['solidColor'] ?? '000000', '#');
                return "color=c=0x{$hex}:s={$outW}x{$outH}:d=1[bg];[0:v]scale={$fgW}:{$fgH}[fg];[bg][fg]overlay={$padX}:0:shortest=1";

            case 'pattern':
                $hex = ltrim($options['patternColor'] ?? '2dd4bf', '#');
                $patternType = $options['patternType'] ?? 'dots';
                $drawboxes = '';

                if ($patternType === 'lines') {
                    for ($i = 0; $i < $outW; $i += 40) {
                        $drawboxes .= ",drawbox=x={$i}:y=0:w=2:h={$outH}:color=0x{$hex}@0.3:t=fill";
                    }
                } elseif ($patternType === 'chevrons') {
                    for ($i = 0; $i < $outW; $i += 60) {
                        $drawboxes .= ",drawbox=x={$i}:y=0:w=30:h={$outH}:color=0x{$hex}@0.15:t=fill";
                    }
                } else {
                    for ($y = 0; $y < $outH; $y += 30) {
                        for ($x = 0; $x < $outW; $x += 30) {
                            $drawboxes .= ",drawbox=x={$x}:y={$y}:w=4:h=4:color=0x{$hex}@0.4:t=fill";
                        }
                    }
                    $drawboxes = substr($drawboxes, 0, 5000);
                }

                return "color=c=0x111111:s={$outW}x{$outH}:d=1{$drawboxes}[bg];[0:v]scale={$fgW}:{$fgH}[fg];[bg][fg]overlay={$padX}:0:shortest=1";

            default:
                return "split[fg][bg];[bg]scale={$outW}:{$outH}:force_original_aspect_ratio=increase,crop={$outW}:{$outH},boxblur=40:40[blurred];[fg]scale={$fgW}:{$fgH}[sharp];[blurred][sharp]overlay={$padX}:0";
        }
    }
}
