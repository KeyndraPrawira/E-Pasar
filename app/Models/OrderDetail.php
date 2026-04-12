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
        'catatan_driver',
        'subtotal_harga',
        'harga_satuan',
        'kios_id',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'produk_id' => 'integer',
        'jumlah' => 'integer',
        'subtotal_harga' => 'integer',
        'harga_satuan' => 'integer',
        'kios_id' => 'integer',
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
