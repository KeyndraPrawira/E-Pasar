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

    public function pembeli()
{
    return $this->belongsTo(User::class, 'buyer_id');
}
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function produk(){
            return $this->belongsToMany(Produk::class, 'order_details', 'order_id', 'produk_id')->withPivot('jumlah', 'subtotal_harga')
                    ->withTimestamps();
     }

}
