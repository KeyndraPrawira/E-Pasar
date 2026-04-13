<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class TransaksiController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'buyer',
            'driver',
            'orderDetails.produk',
        ])
            ->latest()
            ->get();

        return view('admin.transaksi.index', compact('orders'));
    }

    public function show(Order $transaksi)
    {
        $transaksi->load([
            'buyer',
            'driver',
            'orderDetails.produk.kategori',
            'orderDetails.produk.kios.user',
            'orderDetails.kios.user',
        ]);

        return view('admin.transaksi.show', [
            'order' => $transaksi,
        ]);
    }
}
