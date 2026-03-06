<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pro_users', function (Blueprint $table) {
            $table->string('stripe_subscription_id')->nullable();
            $table->string('subscription_status')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pro_users', function (Blueprint $table) {
            $table->dropColumn(['stripe_subscription_id', 'subscription_status', 'subscription_ends_at']);
        });
    }
};
