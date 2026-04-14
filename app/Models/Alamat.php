<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamats';
    
    protected $fillable = [
        'user_id',
        'alamat_lengkap',
        'longitude',
        'latitude',
        'jarak_km'
    ];

    protected $casts = [
        'user_id' => 'int',
        

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
