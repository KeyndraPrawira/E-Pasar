<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'kode_pesanan',
        'buyer_id',
        'total_harga',
        'driver_id',
        'alamat',
        'longitude',
        'latitude',
        'jarak_km',
        'status',
        'alamat_pengiriman',
        'metode_pembayaran',
        'total_harga',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
