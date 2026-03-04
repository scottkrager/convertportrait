<?php

namespace App\Http\Controllers;

use App\Models\ProUser;
use Aws\Lambda\LambdaClient;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LambdaVideoController extends Controller
{
    public function init(Request $request)
    {
        $email = $request->header('X-Pro-Email');
        if (!$email || !ProUser::where('email', strtolower($email))->exists()) {
            return response()->json(['error' => 'Pro access required'], 403);
        }

        $request->validate([
            'template' => 'required|string|in:blurred,gradient,solid,pattern',
            'options' => 'nullable|json',
        ]);

        $jobId = Str::uuid()->toString();
        $bucket = config('services.aws.bucket');
        $s3 = $this->getS3Client();

        // Write initial status file with job config
        $s3->putObject([
            'Bucket' => $bucket,
            'Key' => "status/{$jobId}.json",
            'Body' => json_encode([
                'status' => 'pending_upload',
                'progress' => 0,
                'template' => $request->input('template'),
                'options' => json_decode($request->input('options', '{}'), true) ?: [],
            ]),
            'ContentType' => 'application/json',
        ]);

        // Generate presigned PUT URL for upload
        $cmd = $s3->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key' => "input/{$jobId}.mp4",
            'ContentType' => 'video/mp4',
        ]);
        $uploadUrl = (string) $s3->createPresignedRequest($cmd, '+30 minutes')->getUri();

        return response()->json([
            'jobId' => $jobId,
            'uploadUrl' => $uploadUrl,
        ]);
    }

    public function start(Request $request)
    {
        $email = $request->header('X-Pro-Email');
        if (!$email || !ProUser::where('email', strtolower($email))->exists()) {
            return response()->json(['error' => 'Pro access required'], 403);
        }

        $request->validate(['jobId' => 'required|uuid']);
        $jobId = $request->input('jobId');
        $bucket = config('services.aws.bucket');

        $s3 = $this->getS3Client();
        if (!$s3->doesObjectExist($bucket, "input/{$jobId}.mp4")) {
            return response()->json(['error' => 'Video not uploaded yet'], 422);
        }

        $lambda = new LambdaClient([
            'version' => 'latest',
            'region' => config('services.aws.region'),
            'credentials' => [
                'key' => config('services.aws.key'),
                'secret' => config('services.aws.secret'),
            ],
        ]);

        $lambda->invoke([
            'FunctionName' => config('services.aws.lambda_function'),
            'InvocationType' => 'Event',
            'Payload' => json_encode([
                'jobId' => $jobId,
                'bucket' => $bucket,
            ]),
        ]);

        return response()->json(['status' => 'processing']);
    }

    public function status(string $jobId)
    {
        if (!Str::isUuid($jobId)) {
            return response()->json(['error' => 'Invalid job ID'], 422);
        }

        $s3 = $this->getS3Client();
        $bucket = config('services.aws.bucket');

        try {
            $result = $s3->getObject([
                'Bucket' => $bucket,
                'Key' => "status/{$jobId}.json",
            ]);
            $status = json_decode($result['Body'], true);
        } catch (\Aws\S3\Exception\S3Exception $e) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        $response = [
            'status' => $status['status'],
            'progress' => $status['progress'] ?? 0,
            'error' => $status['error'] ?? null,
        ];

        if ($status['status'] === 'completed') {
            $cmd = $s3->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => "output/{$jobId}.mp4",
            ]);
            $response['downloadUrl'] = (string) $s3->createPresignedRequest($cmd, '+1 hour')->getUri();
        }

        return response()->json($response);
    }

    private function getS3Client(): S3Client
    {
        return new S3Client([
            'version' => 'latest',
            'region' => config('services.aws.region'),
            'credentials' => [
                'key' => config('services.aws.key'),
                'secret' => config('services.aws.secret'),
            ],
        ]);
    }
}
