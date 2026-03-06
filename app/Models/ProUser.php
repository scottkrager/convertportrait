<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProUser extends Model
{
    protected $fillable = [
        'email',
        'stripe_session_id',
        'stripe_customer_id',
        'stripe_subscription_id',
        'subscription_status',
        'subscription_ends_at',
    ];

    protected $casts = [
        'subscription_ends_at' => 'datetime',
    ];

    /**
     * Check if the user has an active subscription.
     * Active if subscription is 'active', or if 'canceled' but period hasn't ended yet.
     */
    public function isActive(): bool
    {
        if ($this->subscription_status === 'active') {
            return true;
        }

        if ($this->subscription_status === 'canceled' && $this->subscription_ends_at && $this->subscription_ends_at->isFuture()) {
            return true;
        }

        // Legacy lifetime purchasers (before subscription model) have no subscription_status
        if ($this->subscription_status === null && $this->stripe_customer_id) {
            return true;
        }

        return false;
    }
}
