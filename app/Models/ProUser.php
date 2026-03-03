<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProUser extends Model
{
    protected $fillable = ['email', 'stripe_session_id', 'stripe_customer_id'];
}
