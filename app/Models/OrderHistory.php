<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $table = 'order_histories';

    protected $fillable = [
        'order_id',
        'buyer_id',
        'kode_pesanan',
        'driver_id',
        'alamat_pengiriman',
        'latitude',
        'longitude',
        'jarak_km',
        'metode_pembayaran',
        'total_harga_barang',
        'ongkir',
        'total_harga',
        'updated_at',
    ];
    protected $casts = [
        'order_id' => 'integer',
        'buyer_id' => 'integer',
        'driver_id' => 'integer',
        'total_harga_barang' => 'integer',
        'total_harga' => 'integer',
        'ongkir' => 'integer',
    ];

    public function orderDetailHistory()
    {
        return $this->belongsTo(OrderDetailHistory::class, 'order_id');
    }

     public function buyer(){
        return $this->belongsTo(User::class, 'buyer_id')->where('role', 'user');
    }
     public function driver(){
        return $this->belongsTo(User::class, 'driver_id')->where('role', 'driver');
    }

}
