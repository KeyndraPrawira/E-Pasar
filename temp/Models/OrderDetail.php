<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'produk_id',
        'jumlah',
        'status',
        'produk_pengganti_id',
        'catatan',
        'catatan_driver',
        'subtotal_harga',
        'harga_satuan',
        'kios_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

        public function kios()
        {
            return $this->belongsTo(Kios::class, 'kios_id');
        }

       
}
