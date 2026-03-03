<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function createCheckout(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'ConvertPortrait Pro',
                        'description' => 'Lifetime access — all templates, unlimited video length',
                    ],
                    'unit_amount' => 1999, // $19.99
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/') . '?pro=activated',
            'cancel_url' => url('/') . '?pro=cancelled',
        ]);

        return response()->json(['url' => $session->url]);
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig, $secret);
        } catch (\Exception $e) {
            return response('', 400);
        }

        // For now we handle pro activation client-side via the success URL
        // In the future, use webhooks to issue license keys or set cookies
        return response('', 200);
    }
}
