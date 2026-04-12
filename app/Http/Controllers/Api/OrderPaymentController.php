<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Services\DriverWalletService;
use App\Services\FirebaseOrderSyncService;

class OrderPaymentController extends Controller
{
    public function __construct(
        private readonly DriverWalletService $driverWalletService,
        private readonly FirebaseOrderSyncService $firebaseOrderSyncService
    ) {
        $this->configureMidtrans();
    }

    /**
     * Buat transaksi Midtrans untuk order yang sudah dikirim.
     */
    public function create(Order $order, Request $request): JsonResponse
    {
        $this->configureMidtrans();
        $user = $request->user();

        if ($order->buyer_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke order ini.',
            ], 403);
        }

        if ($order->status !== 'dikirim') {
            return response()->json([
                'status' => 'error',
                'message' => 'Pembayaran hanya bisa dibuat ketika order berstatus dikirim.',
            ], 422);
        }

        if ($order->metode_pembayaran !== Order::PAYMENT_METHOD_MIDTRANS) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order ini tidak menggunakan metode pembayaran Midtrans.',
            ], 422);
        }

        if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
            return response()->json([
                'status' => 'success',
                'message' => 'Order ini sudah dibayar.',
                'data' => $this->paymentPayload($order),
            ]);
        }

        $normalizedExistingPaymentUrl = $this->normalizePaymentUrl($order->payment_url);

        if ($order->payment_status === Order::PAYMENT_STATUS_PENDING && $order->payment_token && $normalizedExistingPaymentUrl) {
            if ($normalizedExistingPaymentUrl !== $order->payment_url) {
                $order->update([
                    'payment_url' => $normalizedExistingPaymentUrl,
                ]);
                $this->firebaseOrderSyncService->sync($order);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi Midtrans sudah tersedia.',
                'data' => $this->paymentPayload($order->fresh()),
            ]);
        }

        $order->loadMissing(['buyer', 'orderDetails.produk']);

        $reference = $this->generatePaymentReference($order);
        $grossAmount = (int) $order->total_harga;
        try {
            \Log::info('Midtrans CREATE transaction', [
                'SDK_serverKey' => \Midtrans\Config::$serverKey,
                'SDK_isProduction' => \Midtrans\Config::$isProduction,
                'reference' => $reference,
            ]);

            $transaction = Snap::createTransaction([
                'transaction_details' => [
                    'order_id' => $reference,
                    'gross_amount' => $grossAmount,
                ],
                'enabled_payments' => ['qris'],
                'customer_details' => [
                    'first_name' => $order->buyer?->name ?? 'Customer',
                    'email' => $order->buyer?->email,
                    'phone' => $order->buyer?->nomor_telepon,
                ],
                'item_details' => $this->buildItemDetails($order),
                'expiry' => [
                    'start_time' => now()->format('Y-m-d H:i:s O'),
                    'unit' => 'day',
                    'duration' => 1,
                ],
            ]);

            \Log::info('Midtrans transaction created', [
                'reference' => $reference,
                'redirect_url' => $transaction->redirect_url ?? null,
                'token' => $transaction->token ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat transaksi Midtrans.', [
                'order_id' => $order->id,
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat transaksi Midtrans. Silakan coba lagi.',
            ], 500);
        }

        $paymentUrl = $this->normalizePaymentUrl($transaction->redirect_url ?? null);

        if (!$paymentUrl) {
            Log::error('Midtrans tidak mengembalikan redirect_url.', [
                'order_id' => $order->id,
                'reference' => $reference,
                'transaction' => (array) $transaction,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'URL pembayaran QRIS tidak tersedia dari Midtrans.',
            ], 500);
        }

        $order->update([
            'payment_status' => Order::PAYMENT_STATUS_PENDING,
            'payment_reference' => $reference,
            'payment_token' => $transaction->token,
            'payment_url' => $paymentUrl,
            'payment_type' => null,
            'paid_at' => null,
        ]);
        $this->firebaseOrderSyncService->sync($order);
        \Log::info('Midtrans notification URL', [
            'finish_redirect_url' => 'akan dikirim ke: ' . config('app.url') . '/api/midtrans/notification',
            'ngrok_url' => 'pastikan ngrok aktif',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi Midtrans berhasil dibuat.',
            'data' => $this->paymentPayload($order->fresh()),
        ]);
    }

    /**
     * Ambil status pembayaran order dan sinkronkan dari Midtrans jika reference tersedia.
     */
    public function status(Order $order, Request $request): JsonResponse
    {
           
        $user = $request->user();
        $syncedFromMidtrans = true;
        $syncError = null;

        if ($order->buyer_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke order ini.',
            ], 403);
        }

        if (!$order->payment_reference) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order ini belum memiliki transaksi pembayaran Midtrans.',
            ], 404);
        }

        try {
        
            $midtransStatus = Transaction::status($order->payment_reference);
            $this->syncPaymentStatus(
                $order,
                (array) $midtransStatus,
            );
            $syncedFromMidtrans = true;
        } catch (\Throwable $exception) {
            $syncError = $exception->getMessage();

            Log::warning('Gagal mengambil status Midtrans.', [
                'order_id' => $order->id,
                'payment_reference' => $order->payment_reference,
                'error' => $syncError,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => $syncedFromMidtrans
                ? 'Status pembayaran berhasil diambil dari Midtrans.'
                : 'Status pembayaran lokal ditampilkan karena sinkronisasi Midtrans gagal.',
            'data' => $this->paymentPayload($order->fresh()),
            'meta' => [
                'synced_from_midtrans' => $syncedFromMidtrans,
                'sync_error' => $syncError,
            ],
        ]);
        
    
    }

    /**
     * Endpoint webhook notifikasi Midtrans.
     */
    public function notification(Request $request): JsonResponse
    {
         \Log::info('NOTIFICATION MASUK', $request->all()); // ← TAMBAH INI
    $this->configureMidtrans();
        $payload = $request->all();

        if (!$this->isValidSignature($payload)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Signature Midtrans tidak valid.',
            ], 403);
        }

        $order = Order::where('payment_reference', $payload['order_id'] ?? null)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order pembayaran tidak ditemukan.',
            ], 404);
        }

        $this->syncPaymentStatus($order, $payload);

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi Midtrans berhasil diproses.',
        ]);
    }

    /**
     * Konfigurasi SDK Midtrans.
     */
    private function configureMidtrans(): void
    {
        
        Config::$serverKey = config('midtrans.serverKey');
        Config::$clientKey = config('midtrans.clientKey');
        Config::$isProduction = (bool) config('midtrans.isProduction', false);
        Config::$isSanitized = (bool) config('midtrans.isSanitized', true);
        Config::$is3ds = (bool) config('midtrans.is3ds', true);
    }

    /**
     * Buat reference Midtrans yang unik.
     */
    private function generatePaymentReference(Order $order): string
    {
        return 'MID-' . $order->id ;
    }

    /**
     * Bangun item detail untuk Midtrans.
     *
     * @return array<int, array<string, int|string>>
     */
    private function buildItemDetails(Order $order): array
    {
        $items = $order->orderDetails->map(function ($detail) {
            return [
                'id' => 'ITEM-' . $detail->id,
                'price' => (int) $detail->harga_satuan,
                'quantity' => (int) $detail->jumlah,
                'name' => Str::limit($detail->produk?->nama_produk ?? 'Produk Pasar', 50, ''),
            ];
        })->values()->all();

        $items[] = [
            'id' => 'ONGKIR-' . $order->id,
            'price' => (int) $order->ongkir,
            'quantity' => 1,
            'name' => 'Biaya Pengiriman',
        ];

        return $items;
    }

    /**
     * Sinkronkan status pembayaran lokal berdasarkan payload Midtrans.
     *
     * @param  array<string, mixed>  $payload
     */
    private function syncPaymentStatus(Order $order, array $payload): void
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;

        $paymentStatus = match ($transactionStatus) {
            'capture' => $fraudStatus === 'challenge'
                ? Order::PAYMENT_STATUS_PENDING
                : Order::PAYMENT_STATUS_PAID,
            'settlement' => Order::PAYMENT_STATUS_PAID,
            'pending' => Order::PAYMENT_STATUS_PENDING,
            'expire' => Order::PAYMENT_STATUS_EXPIRED,
            'cancel', 'deny', 'failure' => Order::PAYMENT_STATUS_FAILED,
            default => $order->payment_status ?? Order::PAYMENT_STATUS_PENDING,
        };

        $order->update([
            'payment_status' => $paymentStatus,
            'payment_type' => $paymentType,
            'paid_at' => $paymentStatus === Order::PAYMENT_STATUS_PAID
                ? ($order->paid_at ?? now())
                : null,
        ]);

        if ($paymentStatus === Order::PAYMENT_STATUS_PAID) {
            $this->driverWalletService->creditCompletedOrder($order->fresh());
        }

        $this->firebaseOrderSyncService->sync($order);
    }

    /**
     * Validasi signature webhook Midtrans.
     *
     * @param  array<string, mixed>  $payload
     */
    private function isValidSignature(array $payload): bool
    {
        $signature = $payload['signature_key'] ?? null;
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';

        if (!$signature || !$orderId || !$statusCode || !$grossAmount) {
            return false;
        }

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.serverKey'));

        return hash_equals($expected, $signature);
    }

    /**
     * Format response pembayaran untuk Flutter.
     */
    private function paymentPayload(Order $order): array
    {
        return [
            'order_id' => $order->id,
            'kode_pesanan' => $order->kode_pesanan,
            'midtrans_order_id' => $order->payment_reference,
            'payment_method' => $order->metode_pembayaran,
            'payment_status' => $order->payment_status,
            'payment_type' => $order->payment_type,
            'payment_token' => $order->payment_token,
            'payment_url' => $this->normalizePaymentUrl($order->payment_url),
            'gross_amount' => (int) $order->total_harga,
            'paid_at' => $order->paid_at,
            'client_key' => config('midtrans.clientKey'),
        ];
    }

    private function normalizePaymentUrl(?string $paymentUrl): ?string
    {
        if (!$paymentUrl) {
            return null;
        }

        return preg_replace('#/other-qris/?$#', '', trim($paymentUrl));
    }
}
