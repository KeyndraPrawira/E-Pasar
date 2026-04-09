<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nomor_telepon',
        'foto_profil',
        'is_online'
        
        
    ];

    protected $casts = [
        'is_online' => 'boolean',
    ];

    public function kios()
    {
        return $this->hasOne(Kios::class, 'user_id');
    }
    public function alamat()
    {
        return $this->hasOne(Alamat::class, 'user_id');
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'user_id');
    }

    public function driverWallet(): HasOne
    {
        return $this->hasOne(DriverWallet::class, 'user_id');
    }

    public function driverWithdrawals()
    {
        return $this->hasMany(DriverWithdrawal::class, 'user_id');
    }

    public function isApprovedDriver(): bool
    {
        if ($this->relationLoaded('driver')) {
            return $this->role === 'driver'
                && $this->driver !== null
                && $this->driver->status === Driver::STATUS_APPROVED;
        }

        return $this->role === 'driver'
            && $this->driver()->where('status', Driver::STATUS_APPROVED)->exists();
    }
}
