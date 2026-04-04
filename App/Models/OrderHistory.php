<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $table = 'order_histories';

    protected $fillable = [
        'order_id',
        'buyer_id',
        'driver_id',
        'total_harga',
        'alamat_pengiriman',
        'ongkir',
        'updated_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
