<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public const PAYMENT_METHOD_COD = 'cod';
    public const PAYMENT_METHOD_MIDTRANS = 'midtrans';

    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_FAILED = 'failed';
    public const PAYMENT_STATUS_EXPIRED = 'expired';

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
        'payment_status',
        'payment_reference',
        'payment_token',
        'payment_url',
        'payment_type',
        'paid_at',
        'total_harga',
        'driver_earning_amount',
        'driver_wallet_credited_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'driver_wallet_credited_at' => 'datetime',
    ];

    
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function driverInfo()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
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

    public function canBePaid(): bool
    {
        return $this->metode_pembayaran === self::PAYMENT_METHOD_MIDTRANS
            && $this->status === 'dikirim'
            && $this->payment_status !== self::PAYMENT_STATUS_PAID;
    }

    public function canCreditDriverWallet(): bool
    {
        if (!$this->driver_id || $this->status !== 'selesai' || $this->driver_wallet_credited_at !== null) {
            return false;
        }

        if ($this->metode_pembayaran === self::PAYMENT_METHOD_MIDTRANS) {
            return $this->payment_status === self::PAYMENT_STATUS_PAID;
        }

        return true;
    }

}
