<?php

namespace App\Http\Controllers;

use App\Models\Pasar;
use App\Models\Produk;
use Illuminate\Http\Request;

class LandingpageController extends Controller
{
    public function index()
    {
        $pasar = Pasar::first();

        return view('welcome', compact('pasar'));
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
