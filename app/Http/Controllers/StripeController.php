<?php

namespace App\Http\Controllers;

use App\Models\ProUser;
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
            'allow_promotion_codes' => true,
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

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $email = $session->customer_details->email ?? $session->customer_email;

            if ($email) {
                ProUser::updateOrCreate(
                    ['email' => strtolower($email)],
                    [
                        'stripe_session_id' => $session->id,
                        'stripe_customer_id' => $session->customer,
                    ]
                );
            }
        }

        return response('', 200);
    }

    public function restore(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $exists = ProUser::where('email', strtolower($request->email))->exists();

        return response()->json(['pro' => $exists]);
    }
}
