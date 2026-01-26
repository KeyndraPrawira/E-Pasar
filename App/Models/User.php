<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nomor_telepon',
        
    ];
}
