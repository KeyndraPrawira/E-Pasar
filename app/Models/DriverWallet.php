<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DriverWallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'total_earned',
        'total_withdrawn',
        'last_transaction_at',
    ];

    protected $casts = [
        'last_transaction_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(DriverWalletTransaction::class, 'driver_wallet_id');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(DriverWithdrawal::class, 'driver_wallet_id');
    }
}
