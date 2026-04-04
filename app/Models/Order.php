<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'kode_pesanan',
        'buyer_id',
        'driver_id',
        'longitude',
        'latitude',
        'jarak_km',
        'total_harga_barang',
        'ongkir',
        'status',
        'alamat_pengiriman',
        'metode_pembayaran',
        'total_harga',
    ];

    
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function produk(){
            return $this->belongsToMany(Produk::class, 'order_details', 'order_id', 'produk_id')->withPivot('jumlah', 'subtotal_harga')
                    ->withTimestamps();
     }

     public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

     public function orderHistory()
    {
        return $this->hasOne(OrderHistory::class, 'order_id');
    }

    public function buyer(){
        return $this->belongsTo(User::class, 'buyer_id')->where('role', 'user');
    }

    public function pedagang(){
        return $this->belongsTo(User::class, 'user_id')->where('role', 'pedagang');
    }

}
