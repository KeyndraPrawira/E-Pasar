<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory;

class TransaksiController extends Controller
{
    public function index()
    {
        $orders = OrderHistory::with([
            'buyer',
            'driver',
            'orderDetailHistory',
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
