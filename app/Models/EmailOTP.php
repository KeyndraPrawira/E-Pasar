<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $table = 'email_otps';
    protected $fillable = ['email', 'otp', 'expired_at'];
    protected $casts = ['expired_at' => 'datetime'];
}

