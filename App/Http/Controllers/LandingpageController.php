<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pasar;
use App\Models\Produk;
use Illuminate\Http\Request;

class LandingpageController extends Controller
{
        public function index()
    {
        // Ambil data pasar (asumsi hanya ada 1 pasar atau pasar utama)
        $pasar = Pasar::first();
        
        // Ambil semua kategori
        $categories = Kategori::all();
        
        // Ambil semua produk dengan relasi kategori
        $products = Produk::with('kategori')
                         ->where('stok', '>', 0) // hanya produk yang ada stoknya
                         ->orderBy('created_at', 'desc')
                         ->get();
        
        return view('welcome', compact('pasar', 'categories', 'products'));
    }

    /**
     * Filter produk berdasarkan kategori (untuk AJAX request)
     */
    public function filterByCategory($kategori_id)
    {
        if ($kategori_id === 'all') {
            $products = Produk::with('kategori')
                             ->where('stok', '>', 0)
                             ->orderBy('created_at', 'desc')
                             ->get();
        } else {
            $products = Produk::with('kategori')
                             ->where('kategori_id', $kategori_id)
                             ->where('stok', '>', 0)
                             ->orderBy('created_at', 'desc')
                             ->get();
        }

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    /**
     * Detail produk
     */
    public function productDetail($id)
{
    $product = Produk::with(['kategori', 'kios'])->findOrFail($id);

    $relatedProducts = Produk::where('kategori_id', $product->kategori_id)
        ->where('id', '!=', $product->id)
        ->where('stok', '>', 0)
        ->limit(4)
        ->get();

    return view('product-details', compact('product', 'relatedProducts'));
    }


}
