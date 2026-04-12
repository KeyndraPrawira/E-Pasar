<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjangs';

    protected $fillable = [
        'user_id',
        'produk_id',
        'jumlah',
        'harga_total',
    ];
        protected $casts = [
            'user_id' => 'integer',
            'produk_id' => 'integer',
            'jumlah' => 'integer',
            'harga_total' => 'integer',
        ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
