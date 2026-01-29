<?php

namespace App\Http\Controllers;

use App\Models\Pasar;
use App\Models\Kios;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data Pasar (tunggal - ambil pasar pertama atau yang aktif)
        $pasar = Pasar::first();
        
        // Jumlah Kios
        $totalKios = Kios::count();
        
        // Jumlah Produk
        $totalProduk = Produk::count();
        
        // Jumlah Kategori
        $totalKategori = Kategori::count();
        
        // Produk per Kategori untuk Chart Pie
       // Ambil semua kategori dengan hitung jumlah produk
   $kategori = Kategori::withCount('produk')->get();
    // Siapkan data untuk chart
    $chartLabels = $kategori->pluck('nama_kategori')->toArray();
    $chartData = $kategori->pluck('produk_count')->toArray();
    
    $produkPerKategori = $kategori;

        return view('admin.index', compact(
            'pasar',
            'totalKios',
            'totalProduk',
            'totalKategori',
            'produkPerKategori',
            'chartLabels',
            'chartData'
        ));
    }
}