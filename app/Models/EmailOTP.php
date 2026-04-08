<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    public const PURPOSE_DRIVER_REGISTRATION = 'driver_registration';

    protected $table = 'email_otps';
    protected $fillable = ['email', 'purpose', 'otp', 'expired_at', 'used_at'];
    protected $casts = [
        'expired_at' => 'datetime',
        'used_at' => 'datetime',
    ];
}
