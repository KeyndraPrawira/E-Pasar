<?php

namespace App\Models;

use App\Kategori;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'nama_produk',
        'kategori_id',
        'kios_id',
        'harga',
        'stok',
        'deskripsi',
        'foto_produk',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function kios()
    {
        return $this->belongsTo(Kios::class, 'kios_id');
    }
}
