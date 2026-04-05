<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';
    protected $fillable = [
        'user_id',
        'nomor_kendaraan',
        'jenis_kendaraan',
        'nomor_stnk',
        'nomor_sim',
        'foto_ktp',
        'foto_sim',
        'foto_stnk',
        'foto_kendaraan'

    ];

    public function laporans()
    {
        return $this->morphMany(Laporan::class, 'reportable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'driver_id');
    }
    
}
