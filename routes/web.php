<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\LambdaVideoController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'stripeKey' => config('services.stripe.key'),
    ]);
});

// SEO Landing Pages
Route::get('/convert-tiktok-to-youtube', fn() => Inertia::render('Landing/TiktokToYoutube'));
Route::get('/portrait-to-landscape-video-converter', fn() => Inertia::render('Landing/PortraitToLandscape'));
Route::get('/vertical-to-horizontal-video', fn() => Inertia::render('Landing/VerticalToHorizontal'));
Route::get('/convert-reels-to-landscape', fn() => Inertia::render('Landing/ReelsToLandscape'));
Route::get('/video-aspect-ratio-converter', fn() => Inertia::render('Landing/AspectRatioConverter'));

// Blog
Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{slug}', [BlogController::class, 'show']);

Route::post('/api/checkout', [StripeController::class, 'createCheckout']);
Route::post('/api/stripe/webhook', [StripeController::class, 'webhook']);
Route::post('/api/restore', [StripeController::class, 'restore']);

Route::post('/api/process/init', [LambdaVideoController::class, 'init']);
Route::post('/api/process/start', [LambdaVideoController::class, 'start']);
Route::get('/api/process/status/{jobId}', [LambdaVideoController::class, 'status']);
