<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasar extends Model
{
    protected $table = 'pasars';

    protected $fillable = [
        'nama_pasar',
        'alamat',
        'longitude',
        'latitude',
        'foto_pasar',
        'ongkir',
        'kontak',
        'deskripsi',
    ];
}
