<?php

namespace App\Http\Controllers\Pedagang;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Kios;
use App\Models\Pasar;
use App\Models\Produk;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Data Pasar (tunggal - ambil pasar pertama atau yang aktif)
        $pasar = Pasar::first();
        
        // Informasi Kios
        $kios = Kios::where('user_id', auth()->id());
        
        // Jumlah Produk
        $totalProduk = Produk::where('kios_id', $kios->pluck('id'))->count();
        
        // Jumlah Kategori
        $totalKategori = Kategori::count();
        
        // Produk per Kategori untuk Chart Pie
       // Ambil semua kategori dengan hitung jumlah produk
   $kategori = Kategori::withCount('produk')->get();
    // Siapkan data untuk chart
    $chartLabels = $kategori->pluck('nama_kategori')->toArray();
    $chartData = $kategori->pluck('produk_count')->toArray();
    
    $produkPerKategori = $kategori;

        return view('pedagang.index', compact(
            'kios',
            'totalProduk',
            'totalKategori',
            'produkPerKategori',
            'chartLabels',
            'chartData'
        ));
    }
}
