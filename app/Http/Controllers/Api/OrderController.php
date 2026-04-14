<?php

namespace App\Http\Controllers\Api;

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
use Illuminate\Validation\ValidationException;
use App\Services\DriverWalletService;
use App\Services\FirebaseOrderSyncService;

class OrderController extends Controller
{
    public function __construct(
        private readonly DriverWalletService $driverWalletService,
        private readonly FirebaseOrderSyncService $firebaseOrderSyncService
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
        $order = Order::with('orderDetails.produk', 'buyer', 'driver', 'orderDetails.produk.kios', 'driver.driverInfo')
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

        $produkTidakTersedia = $keranjang->first(fn ($item) => !$item->produk);
        if ($produkTidakTersedia) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Ada produk di keranjang yang sudah tidak tersedia, silakan perbarui keranjang Anda',
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

        $totalBerat = $keranjang->sum(fn ($item) => (int) ($item->produk->berat_satuan ?? 0) * $item->jumlah);
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

        DB::transaction(function () use ($request, $keranjang, $alamat, $ongkir, &$order, $totalHargaBarang, $totalHarga) {
            $order = Order::create([
                'kode_pesanan'      => 'ORD-' . strtoupper(uniqid(5)),
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

            $totalHargaBarangAktual = 0;

            foreach ($keranjang as $item) {
                $produk = Produk::lockForUpdate()->findOrFail($item->produk_id);

                if ($produk->stok < $item->jumlah) {
                    throw ValidationException::withMessages([
                        'stok' => ["Stok {$produk->nama_produk} tidak mencukupi untuk checkout."],
                    ]);
                }

                $subtotalHarga = $produk->harga * $item->jumlah;

                $order->orderDetails()->create([
                    'produk_id'      => $produk->id,
                    'kios_id'        => $produk->kios_id,
                    'harga_satuan'   => $produk->harga,
                    'jumlah'         => $item->jumlah,
                    'subtotal_harga' => $subtotalHarga,
                ]);

                $totalHargaBarangAktual += $subtotalHarga;
                $produk->decrement('stok', $item->jumlah);
            }

            $order->update([
                'total_harga_barang' => $totalHargaBarangAktual,
                'total_harga' => $totalHargaBarangAktual + (int) $ongkir,
            ]);
        });
        $keranjang->each->delete();

        $this->firebaseOrderSyncService->sync($order);


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

       $orderDriver = Order::where('driver_id', auth()->id())
    ->whereIn('status', ['dalam_proses', 'dikirim'])
    ->exists();

if ($orderDriver) {
    return response()->json([
        'status'  => 'error',
        'message' => 'Anda memiliki pesanan yang belum selesai',
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
        $this->firebaseOrderSyncService->sync($order);

        return response()->json([
            'status'  => 'success',
            'message' => 'Order berhasil diterima',
            'data'    => $order->fresh()->load('buyer', 'orderDetails.produk', 'driver'),
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
                ->with('orderDetails.produk', 'buyer', 'driver')
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
        'status' => 'required|in:diambil,tidak_ada',
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
            'message' => 'Bukan order kamu, order ini milik ' . $item->order->driver->id
            
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
    $this->refreshOrderAfterItemStatusChange($item->order);

    

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
        $this->firebaseOrderSyncService->sync($item->order);

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
        $this->firebaseOrderSyncService->sync($item->order);

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

        $this->refreshOrderAfterItemStatusChange($item->order);

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
        $this->firebaseOrderSyncService->sync($order);

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
        $this->firebaseOrderSyncService->sync($order);

        return response()->json([
            'status'  => 'success',
            'message' => 'Order berhasil diselesaikan',
            'data'    => $order,
        ]);
    }

    // ── BUYER/DRIVER — request pembatalan ─────────────────────
   public function orderCancel($id)
{
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

    // menunggu_driver → langsung bisa dibatalkan
    if ($order->status === 'menunggu_driver') {
        $order->update(['status' => 'dibatalkan']);
        $this->firebaseOrderSyncService->sync($order);

        return response()->json([
            'status'  => 'success',
            'message' => 'Order berhasil dibatalkan',
            'data'    => $order,
        ]);
    }

   
    // Status lain → tidak bisa dibatalkan
    return response()->json([
        'status'  => 'error',
        'message' => 'Order tidak dapat dibatalkan, status: ' . $order->status,
    ], 400);
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
            $this->firebaseOrderSyncService->sync($order);
            return response()->json([
                'status'  => 'success',
                'message' => 'Permintaan sudah expired, order otomatis dibatalkan',
                'data'    => $order,
            ]);
        }

        if ($request->action === 'approve') {
            $order->update(['status' => 'dibatalkan']);
            $this->firebaseOrderSyncService->sync($order);
            return response()->json([
                'status'  => 'success',
                'message' => 'Pembatalan disetujui, order dibatalkan',
                'data'    => $order,
            ]);
        }

        // Reject → balik ke dikirim
        $order->update([
            'status'              => 'dikirim',
        ]);
        $this->firebaseOrderSyncService->sync($order);

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

    private function refreshOrderAfterItemStatusChange(Order $order): Order
    {
        $order = $order->fresh(['orderDetails']);

        $semuaItemTidakAda = $order->orderDetails->isNotEmpty()
            && $order->orderDetails->every(fn (OrderDetail $detail) => $detail->status === 'tidak_ada');

        $totalHargaBarang = $semuaItemTidakAda
            ? 0
            : (int) $order->orderDetails->sum('subtotal_harga');

        $payload = [
            'total_harga_barang' => $totalHargaBarang,
            'total_harga' => $semuaItemTidakAda
                ? 0
                : $totalHargaBarang + (int) $order->ongkir,
        ];

        if ($semuaItemTidakAda) {
            $payload['status'] = 'dibatalkan';
        }

        $order->update($payload);
        $order = $order->fresh();

        $this->firebaseOrderSyncService->sync($order);

        return $order;
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
