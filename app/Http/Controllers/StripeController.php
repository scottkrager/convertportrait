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
                        'description' => 'Yearly subscription — all templates, unlimited video length',
                    ],
                    'unit_amount' => 1999, // $19.99
                    'recurring' => [
                        'interval' => 'year',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
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

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
        }

        return response('', 200);
    }

    public function restore(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $proUser = ProUser::where('email', strtolower($request->email))->first();

        return response()->json(['pro' => $proUser && $proUser->isActive()]);
    }

    private function handleCheckoutCompleted($session)
    {
        $email = $session->customer_details->email ?? $session->customer_email;

        if (!$email) {
            return;
        }

        $data = [
            'stripe_session_id' => $session->id,
            'stripe_customer_id' => $session->customer,
        ];

        // For subscription checkouts, save subscription details
        if ($session->subscription) {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $subscription = $stripe->subscriptions->retrieve($session->subscription);

            $data['stripe_subscription_id'] = $subscription->id;
            $data['subscription_status'] = 'active';
            $data['subscription_ends_at'] = date('Y-m-d H:i:s', $subscription->current_period_end);
        }

        ProUser::updateOrCreate(
            ['email' => strtolower($email)],
            $data
        );
    }

    private function handleSubscriptionUpdated($subscription)
    {
        $proUser = ProUser::where('stripe_subscription_id', $subscription->id)->first();

        if (!$proUser) {
            return;
        }

        $statusMap = [
            'active' => 'active',
            'past_due' => 'past_due',
            'canceled' => 'canceled',
            'unpaid' => 'past_due',
            'incomplete' => 'past_due',
            'incomplete_expired' => 'expired',
        ];

        $proUser->update([
            'subscription_status' => $statusMap[$subscription->status] ?? $subscription->status,
            'subscription_ends_at' => date('Y-m-d H:i:s', $subscription->current_period_end),
        ]);
    }

    private function handleSubscriptionDeleted($subscription)
    {
        $proUser = ProUser::where('stripe_subscription_id', $subscription->id)->first();

        if (!$proUser) {
            return;
        }

        $proUser->update([
            'subscription_status' => 'expired',
        ]);
    }

    private function handlePaymentFailed($invoice)
    {
        if (!$invoice->subscription) {
            return;
        }

        $proUser = ProUser::where('stripe_subscription_id', $invoice->subscription)->first();

        if (!$proUser) {
            return;
        }

        $proUser->update([
            'subscription_status' => 'past_due',
        ]);
    }
}
