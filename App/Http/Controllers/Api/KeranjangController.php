<?php

namespace App\Http\Controllers\Api;

use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class KeranjangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keranjang = Keranjang::with('produk')
                    ->where('user_id', auth()->id())
                    ->get();
        
        return response()->json([
            'status' => 200,
            'message' => 'Keranjang berhasil diambil',
            'data' => $keranjang
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
   public function store(Request $request, $produkId)
{
    // auth:sanctum
    $user = auth()->user();
    if ($user->role !== 'user') {
        return response()->json([
            'status' => 403,
            'message' => 'Anda tidak memiliki izin untuk melakukan tindakan ini'
        ], 403);
    }



    $request->validate([
        'jumlah' => 'required|integer|min:1',
    ]);

    $produk = Produk::find($produkId);
    if ($produk == null) {
        return response()->json([
            'status' => 404,
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }

    // cari cart user + produk
    $keranjang = Keranjang::where('user_id', $user->id)
        ->where('produk_id', $produkId)
        ->first();

    if ($keranjang) {
        // kalo udah ada → tambah jumlah
        $keranjang->jumlah += $request->jumlah;
        $keranjang->harga_total = $keranjang->jumlah * $produk->harga;
        $keranjang->save();
    } else {
        // kalo belum ada → bikin baru
        $keranjang = Keranjang::create([
            'user_id'      => $user->id,
            'produk_id'    => $produkId,
            'jumlah'       => $request->jumlah,
            'harga_total'  => $request->jumlah * $produk->harga,
        ]);
    }

    return response()->json([
        'status' => 200,
        'message' => 'Produk berhasil ditambahkan ke keranjang',
        'data' => $keranjang
    ]);
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'jumlah' => 'required|integer|min:1',
    ]);

    $cart = Keranjang::where('id', $id)
        ->where('user_id', $request->user()->id)
        ->with('produk')
        ->firstOrFail();

    $cart->jumlah = $request->jumlah;
    $cart->harga_total = $cart->jumlah * $cart->produk->harga;
    $cart->save();

    return response()->json([
        'status' => 200,
        'message' => 'Jumlah berhasil diperbarui',
        'data' => $cart
    ]);
}



    /**
     * Store a newly created resource in storage.
     */
    
    /**
     * Display the specified resource.
     */
    

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
{
    $cart = Keranjang::where('id', $id)
        ->where('user_id', $request->user()->id)
        ->firstOrFail();

    $cart->delete();

    return response()->json([
        'status' => 200,
        'message' => 'Produk berhasil dihapus dari keranjang'
    ]);
}

}
