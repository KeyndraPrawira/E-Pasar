<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
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
        return $this->hasMany(Kios::class, 'user_id');
    }
    public function alamat()
    {
        return $this->hasOne(Alamat::class, 'user_id');
    }
}
