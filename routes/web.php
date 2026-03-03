<?php

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
