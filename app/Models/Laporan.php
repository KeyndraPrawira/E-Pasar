<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{

    protected $table = 'laporans';
    protected $fillable = [
        'user_id',
        'reportable_type',
        'reportable_id',
        'kategori_laporan_id',
        'alasan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriLaporan::class, 'kategori_laporan_id');
    }

    public function reportable()
    {
        return $this->morphTo();
    }
}