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
    protected $casts = [
        'order_id' => 'integer',
        'buyer_id' => 'integer',
        'driver_id' => 'integer',
        'total_harga' => 'integer',
        'ongkir' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
