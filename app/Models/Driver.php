<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Driver extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $table = 'drivers';

    protected $fillable = [
        'user_id',
        'nomor_kendaraan',
        'jenis_kendaraan',
        'nomor_stnk',
        'nomor_sim',
        'foto_ktp',
        'foto_sim',
        'foto_stnk',
        'foto_kendaraan',
        'foto_diri',
        'status',
        'verification_notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'user_id' => 'int',
        'verified_at' => 'datetime',
    ];

    public function laporans(): MorphMany
    {
        return $this->morphMany(Laporan::class, 'reportable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'driver_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
