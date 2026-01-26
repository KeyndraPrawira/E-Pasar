<?php

namespace App\Models;

use App\Models\User as ModelsUser;

use Illuminate\Database\Eloquent\Model;

class Kios extends Model
{
    protected $table = 'kios';

    protected $fillable = [
        'nama_kios',
        'lokasi',
        'pasar_id',
        'user_id',
        'kontak',
        'deskripsi',
        'foto_kios',
    ];

    public function pasar()
    {
        return $this->belongsTo(Pasar::class, 'pasar_id');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kios_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
