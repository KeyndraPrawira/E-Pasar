<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverWalletTransaction extends Model
{
    public const TYPE_CREDIT = 'credit';
    public const TYPE_DEBIT = 'debit';
    public const TYPE_ADJUSTMENT = 'adjustment';

    protected $fillable = [
        'driver_wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(DriverWallet::class, 'driver_wallet_id');
    }
}
