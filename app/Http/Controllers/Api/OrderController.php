<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderUpdated;
use App\Helpers\HaversineHelper;
use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Driver;
use App\Models\Keranjang;
use App\Models\Order;
use App\Models\Pasar;
use App\Jobs\AutoCancelOrder;
use App\Models\OrderDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\DriverWalletService;

class OrderController extends Controller
{
    public function __construct(
        private readonly DriverWalletService $driverWalletService
    ) {
    }

    // ── DRIVER — scan order available ─────────────────────────
    public function index()
    {
        $user = auth()->user();
        if ($response = $this->ensureApprovedDriver($user)) {
            return $response;
        }

        if ($user->is_online === false) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver sedang offline'
            ], 403);
        }
        $orders = Order::whereNull('driver_id')
            ->where('status', 'menunggu_driver')
            ->with('orderDetails.produk', 'buyer')
            ->latest()
            ->get();



        return response()->json([
            'status'  => 'success',
            'message' => 'Semua data order berhasil ditampilkan',
            'data'    => $orders,
        ]);
    }

    public function show($id)
    {
        $order = Order::with('orderDetails.produk', 'buyer', 'driver', 'pedagang')
            ->find($id);

        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan',
            ], 404);
        }
        
        

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail order berhasil ditampilkan',
            'data'    => $order,
        ]);
    }

    
   

    // ── BUYER — checkout dari keranjang ───────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:cod,midtrans',
        ], [
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'metode_pembayaran.in' => 'Metode pembayaran yang dipilih tidak valid.',
        ]);

        $keranjang = Keranjang::where('user_id', auth()->id())
            ->with('produk')
            ->get();

        $alamat = Alamat::where('user_id', auth()->id())->first();

        if ($keranjang->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Keranjang kosong',
            ], 400);
        }

        if (!$alamat) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Alamat tidak ditemukan, silakan set alamat terlebih dahulu',
            ], 404);
        }

        $pasar = Pasar::first();
        if (!$pasar) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data pasar belum dikonfigurasi',
            ], 500);
        }

        $totalBerat = $keranjang->sum(fn($item) => $item->produk->berat * $item->jumlah);
        $totalHargaBarang = $keranjang->sum('harga_total');
        $ongkir = HaversineHelper::hitungOngkir(
            $alamat->jarak_km,
            (int) ($pasar->ongkir ?? 0),
            (int) ($pasar->minimal_ongkir ?? 0),
            (int) ($pasar->biaya_layanan ?? 0),
            $totalBerat,
            (int) ($pasar->biaya_per_kg ?? 0)
        );

        $totalHarga = $totalHargaBarang + $ongkir;
        $order = null;

        DB::transaction(function () use ($request, $keranjang, $alamat, $ongkir, $totalHarga, &$order, $totalHargaBarang) {
            $order = Order::create([
                'kode_pesanan'      => 'ORD-' . strtoupper(uniqid()),
                'buyer_id'          => auth()->id(),
                'status'            => 'menunggu_driver',
                'metode_pembayaran' => $request->metode_pembayaran,
                'payment_status'    => Order::PAYMENT_STATUS_PENDING,
                'alamat_pengiriman' => $alamat->alamat_lengkap,
                'latitude'          => $alamat->latitude,
                'longitude'         => $alamat->longitude,
                'jarak_km'          => $alamat->jarak_km,
                'total_harga_barang'=> $totalHargaBarang,
                'ongkir'            => $ongkir,
                'total_harga'       => $totalHarga,
                'driver_earning_amount' => $ongkir,
            ]);

            foreach ($keranjang as $item) {
                $order->orderDetails()->create([
                    'produk_id'      => $item->produk_id,
                    'kios_id'        => $item->produk->kios_id,
                    'harga_satuan'   => $item->produk->harga,
                    'jumlah'         => $item->jumlah,
                    'subtotal_harga' => $item->harga_total,
                ]);
            }

            
        });
        $keranjang->each->delete();
         // Di OrderController@store, setelah $keranjang->each->delete()
         // Di OrderController@store, setelah $keranjang->each->delete()
\Log::info('Broadcasting OrderUpdated', [
    'order_id' => $order->id,
    'channels' => ['user.' . $order->buyer_id, 'orders']
]) ;
                event(new OrderUpdated($order));


        return response()->json([
            'status'  => 'success',
            'message' => 'Order berhasil dibuat',
            'data'    => $order->load('orderDetails'),
        ], 201);
    }

    // ── BUYER — lihat order sendiri ───────────────────────────
    public function myOrders()
    {
        $orders = Order::where('buyer_id', auth()->id())
            ->with('orderDetails.produk', 'driver')
            ->latest()
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data order berhasil ditampilkan',
            'data'    => $orders,
        ]);
    }

    // ── DRIVER — accept order ─────────────────────────────────
    public function AcceptOrder($id)
    {
       
        $order = Order::find($id);
         
        $user  = auth()->user();

        if ($response = $this->ensureApprovedDriver($user)) {
            return $response;
        }

        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan',
            ], 404);
        }

        if ($user->role !== 'driver') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Hanya driver yang dapat menerima order',
            ], 403);
        }

        if ($order->driver_id !== null) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order sudah diambil driver lain',
            ], 400);
        }

        if ($order->status !== 'menunggu_driver') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak dapat diterima, status: ' . $order->status,
            ], 400);
        }

        $order->update([
            'status'    => 'dalam_proses',
            'driver_id' => auth()->id(),
        ]);
        
        event(new OrderUpdated($order));

        return response()->json([
            'status'  => 'success',
            'message' => 'Order berhasil diterima',
            'data'    => $order->fresh()->load('buyer'),
        ]);
    }

    public function indexActiveOrders() {
        $user = auth()->user();
        if ($user->role == 'driver') {
            if ($response = $this->ensureApprovedDriver($user)) {
                return $response;
            }

            $orders = Order::where('driver_id', $user->id)
                ->whereIn('status', ['dalam_proses', 'dikirim'])
                ->with('orderDetails.produk', 'buyer')
                ->latest()
                ->get();
        } elseif($user->role == 'user') {
            $orders = Order::where('buyer_id', $user->id)
                ->whereIn('status', ['dalam_proses', 'dikirim'])
                ->with('orderDetails.produk', 'driver')
                ->latest()
                ->get();
    
        }

            return response()->json([
                'status'  => 'success',
                'message' => 'Data semua order yang aktif berhasil ditampilkan',
                'data'    => $orders
            ]);
        }
    

public function activeOrder(Request $request)
{
    $user = auth()->user();
    
    // Hanya driver yang bisa akses
    if ($user->role !== 'driver') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    if ($response = $this->ensureApprovedDriver($user)) {
        return $response;
    }

    $order = Order::with('orderDetails')
        ->where('driver_id', $user->id)
        ->whereIn('status', ['dalam_proses', 'dikirim'])
        ->latest()
        ->first();

    if (!$order) {
        return response()->json([
            'status' => 'success',
            'data' => null
        ], 200);
    }

    return response()->json([
        'status' => 'success',
        'data' => $order
    ], 200);
}

     public function updateItemStatus(Request $request, $id)
    {
    
    $request->validate([
        'status' => 'required|in:diambil,tidak_ada, pending_request, diganti',
        'catatan_driver' => 'nullable|string'
    ]);

    $user = auth()->user();

    if ($user->role !== 'driver') {
        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }

    if ($response = $this->ensureApprovedDriver($user)) {
        return $response;
    }

    $item = OrderDetail::with('order')->findOrFail($id);


    //  pastikan driver yang update = yang pegang order
    if ($item->order->driver_id !== $user->id) {
        return response()->json([
            'message' => 'Bukan order kamu'
        ], 403);
    }

    $data = [
        'status' => $request->status,
    ];

    

    //  kalau diambil (biar aman, hitung ulang)
    if ($request->status == 'pending_request'){
        return response()->json([
            'message' => 'Menunggu konfirmasi ganti barang'
        ], 200);
    }

    if ($request->status === 'diambil') {
        $data['subtotal_harga'] = $item->harga_satuan * $item->jumlah;
    }
        if ($request->status === 'tidak_ada') {
            $data['subtotal_harga'] = 0;
        }




    $item->update($data);

                event(new OrderUpdated($item->order));

    

    return response()->json([
        'message' => 'Status item berhasil diupdate',
        'data' => $item
        ]);
    }

    public function requestTidakDiambil(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->role !== 'driver') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($response = $this->ensureApprovedDriver($user)) {
            return $response;
        }

        $item = OrderDetail::with('order')->findOrFail($id);

        if ($item->order->driver_id !== $user->id) {
            return response()->json(['message' => 'Bukan order kamu'], 403);
        }
        $request->validate([
            'catatan_driver' => 'required|string'
        ],
        [
            'catatan_driver.required' => 'Catatan driver wajib diisi jika barang tidak diambil'
        ]);
        $item->update([
            'status' => 'menunggu_konfirmasi',
            'catatan_driver' => $request->catatan_driver
        ]);

        return response()->json([
            'message' => 'Menunggu konfirmasi user',
            'data' => $item
        ]);
    }

    public function pilihPengganti(Request $request, $id)
    {
        $request->validate([
            'produk_pengganti_id' => 'required|exists:produks,id'
        ]);     

        $user = auth()->user();

        $item = OrderDetail::with('order')->findOrFail($id);

        if ($item->order->buyer_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $produk = Produk::findOrFail($request->produk_pengganti_id);

        $item->update([
            'produk_pengganti_id' => $produk->id,
            'harga_satuan' => $produk->harga,
            'subtotal_harga' => $produk->harga * $item->jumlah,
            'status' => 'diganti'
        ]);

        return response()->json([
            'message' => 'Produk pengganti dipilih',
            'data' => $item
        ]);
    }

    public function tidakJadiGanti(Request $request, $id)
    {
        $user = auth()->user();

        $item = OrderDetail::with('order')->findOrFail($id);

        if ($item->order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $item->update([
            'status' => 'tidak_ada',
            'subtotal_harga' => 0
        ]);

        $sum = $item->order->orderDetails()->whereIn('status', ['pending', 'diambil', 'diganti'])->sum('subtotal_harga');

         $item->order->update(['total_harga' => $sum]);

        return response()->json([
            'message' => 'Item tidak jadi dibeli',
            'data' => $item
        ]);
    }
    public function OrderHistory()
    {
        $orders = Order::where('buyer_id', auth()->id())
            ->orWhere('driver_id', auth()->id())
            ->with('orderDetails.produk', 'buyer', 'driver')
            ->latest()
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data order berhasil ditampilkan',
            'data'    => $orders,
        ]);
    }

    public function detailOrderHistory($id)
    {
        $order = Order::with('orderDetails.produk', 'buyer', 'driver')
            ->find($id);

        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan',
            ], 404);
        }

        if ($order->buyer_id !== auth()->id() && $order->driver_id !== auth()->id()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses untuk melihat order ini',
            ], 403);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail order berhasil ditampilkan',
            'data'    => $order,
        ]);
    }

    public function sendDelivery($id)
    {
        $order = Order::find($id);
        $user  = auth()->user();

        if ($response = $this->ensureApprovedDriver($user)) {
            return $response;
        }

        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan',
            ], 404);
        }

        if ($user->id !== $order->driver_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Hanya driver yang dapat mengirim order',
            ], 403);
        }

        if ($order->status !== 'dalam_proses') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak dapat dikirim, status: ' . $order->status,
            ], 400);
        }

        $belum = $order->orderDetails()
        ->where('status', 'pending')
        ->exists();

        if ($belum) {
            return response()->json([
                'message' => 'Masih ada barang belum dicek'
            ], 400);
        }
            $total = $order->orderDetails()->sum('subtotal_harga');
        $order->update(['status' => 'dikirim']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Order berhasil dikirim',
            'data'    => $order,
        ]);
    }

   

    public function completeOrder($id)
    {
        $order = Order::find($id);
        $user  = auth()->user();

        if ($response = $this->ensureApprovedDriver($user)) {
            return $response;
        }

        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan',
            ], 404);
        }

        if ($user->id !== $order->driver_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Hanya driver yang dapat menyelesaikan order',
            ], 403);
        }

        if ($order->status !== 'dikirim') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak dapat diselesaikan, status: ' . $order->status,
            ], 400);
        }

        $payload = ['status' => 'selesai'];

        if ($order->metode_pembayaran !== Order::PAYMENT_METHOD_MIDTRANS && $order->payment_status !== Order::PAYMENT_STATUS_PAID) {
            $payload['payment_status'] = Order::PAYMENT_STATUS_PAID;
            $payload['paid_at'] = now();
        }

        $order->update($payload);
        $this->driverWalletService->creditCompletedOrder($order->fresh());

        return response()->json([
            'status'  => 'success',
            'message' => 'Order berhasil diselesaikan',
            'data'    => $order,
        ]);
    }

    // ── BUYER/DRIVER — request pembatalan ─────────────────────
    public function requestCancel(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $order = Order::find($id);
        $user  = auth()->user();

        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan',
            ], 404);
        }

        $role = $this->resolveRole($user, $order);
        if (!$role) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses untuk membatalkan order ini',
            ], 403);
        }

        if (!in_array($order->status, ['dalam_proses', 'dikirim'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak dapat dibatalkan pada status: ' . $order->status,
            ], 400);
        }

        // Buyer batalkan saat belum ada driver → langsung batal
        if ($order->status === 'dalam_proses' && is_null($order->driver_id)) {
            if ($role !== 'buyer') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Hanya buyer yang dapat membatalkan order yang belum diambil driver',
                ], 403);
            }

            $order->update([
                'status'              => 'dibatalkan',
                'cancel_reason'       => $request->reason,
                'cancel_request_by'   => 'buyer',
                'cancel_requested_at' => now(),
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Order berhasil dibatalkan',
                'data'    => $order,
            ]);
        }

        if ($order->status === 'menunggu_konfirmasi_batal') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Sudah ada permintaan pembatalan yang menunggu konfirmasi',
            ], 400);
        }

        $order->update([
            'status'              => 'menunggu_konfirmasi_batal',
            'cancel_request_by'   => $role,
            'cancel_reason'       => $request->reason,
            'cancel_requested_at' => now(),
        ]);

        // Auto cancel setelah 5 menit kalau tidak direspons
        AutoCancelOrder::dispatch($order->id)->delay(now()->addMinutes(5));

        return response()->json([
            'status'  => 'success',
            'message' => 'Permintaan pembatalan dikirim, menunggu konfirmasi',
            'data'    => $order,
        ]);
    }

    // ── BUYER/DRIVER — konfirmasi pembatalan ──────────────────
    public function confirmCancel(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $order = Order::find($id);
        $user  = auth()->user();

        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan',
            ], 404);
        }

        if ($order->status !== 'menunggu_konfirmasi_batal') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak ada permintaan pembatalan aktif',
            ], 400);
        }

        $role = $this->resolveRole($user, $order);
        if (!$role || $role === $order->cancel_request_by) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak berhak mengkonfirmasi permintaan ini',
            ], 403);
        }

        // Cek expired
        if (now()->diffInMinutes($order->cancel_requested_at) >= 5) {
            $order->update(['status' => 'dibatalkan']);
            return response()->json([
                'status'  => 'success',
                'message' => 'Permintaan sudah expired, order otomatis dibatalkan',
                'data'    => $order,
            ]);
        }

        if ($request->action === 'approve') {
            $order->update(['status' => 'dibatalkan']);
            return response()->json([
                'status'  => 'success',
                'message' => 'Pembatalan disetujui, order dibatalkan',
                'data'    => $order,
            ]);
        }

        // Reject → balik ke dikirim
        $order->update([
            'status'              => 'dikirim',
            'cancel_request_by'   => null,
            'cancel_reason'       => null,
            'cancel_requested_at' => null,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pembatalan ditolak, order dilanjutkan',
            'data'    => $order,
        ]);
    }

    

    // ── HELPER — tentukan role user terhadap order ────────────
    private function resolveRole($user, Order $order): ?string
    {
        if ($user->id === $order->buyer_id) return 'buyer';
        if ($user->id === $order->driver_id) return 'driver';
        return null;
    }

    private function ensureApprovedDriver($user)
    {
        $user->loadMissing('driver');

        if ($user->driver === null || $user->driver->status !== Driver::STATUS_APPROVED) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun driver Anda belum diverifikasi admin.',
            ], 403);
        }

        return null;
    }

    
}
