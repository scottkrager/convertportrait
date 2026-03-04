<?php

use App\Http\Controllers\LambdaVideoController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'stripeKey' => config('services.stripe.key'),
    ]);
});

Route::post('/api/checkout', [StripeController::class, 'createCheckout']);
Route::post('/api/stripe/webhook', [StripeController::class, 'webhook']);
Route::post('/api/restore', [StripeController::class, 'restore']);

Route::post('/api/process/init', [LambdaVideoController::class, 'init']);
Route::post('/api/process/start', [LambdaVideoController::class, 'start']);
Route::get('/api/process/status/{jobId}', [LambdaVideoController::class, 'status']);
