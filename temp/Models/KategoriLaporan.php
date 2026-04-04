<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriLaporan extends Model
{
    protected $fillable = [
        'nama',
        'reportable_type',
        'is_active'
    ];

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }
}
