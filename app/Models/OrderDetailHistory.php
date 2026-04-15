<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetailHistory extends Model
{
    protected $table = 'order_history_details';

    protected $fillable = [
        'order_history_id',
        'nama_produk',
        'nama_kios',
        'harga_satuan',
        'jumlah',
        'subtotal_harga',
        'status',
        'catatan'
    ];

    protected $casts = [
        'order_history_id' => 'integer',
        'harga_satuan' => 'integer',
        'jumlah' => 'integer',
        'subtotal_harga' => 'integer'
    ];

    public function orderHistory()
    {
        return $this->belongsTo(OrderHistory::class, 'order_id');
    }

    public function buyer(){
        return $this->belongsTo(User::class, 'buyer_id')->where('role', 'user');
    }
    public function driver(){
        return $this->belongsTo(User::class, 'buyer_id')->where('role', 'driver');
    }
    

    

       
}
