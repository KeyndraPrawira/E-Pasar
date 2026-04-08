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

class OrderPaymentController extends Controller
{
    public function __construct()
    {
        $this->configureMidtrans();
    }

    /**
     * Buat transaksi Midtrans untuk order yang sudah dikirim.
     */
    public function create(Order $order, Request $request): JsonResponse
    {
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

        if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
            return response()->json([
                'status' => 'success',
                'message' => 'Order ini sudah dibayar.',
                'data' => $this->paymentPayload($order),
            ]);
        }

        if ($order->payment_status === Order::PAYMENT_STATUS_PENDING && $order->payment_token && $order->payment_url) {
            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi Midtrans sudah tersedia.',
                'data' => $this->paymentPayload($order),
            ]);
        }

        $order->loadMissing(['buyer', 'orderDetails.produk']);

        $reference = $this->generatePaymentReference($order);
        $grossAmount = (int) $order->total_harga;

        $transaction = Snap::createTransaction([
            'transaction_details' => [
                'order_id' => $reference,
                'gross_amount' => $grossAmount,
            ],
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

        $order->update([
            'metode_pembayaran' => 'midtrans',
            'payment_status' => Order::PAYMENT_STATUS_PENDING,
            'payment_reference' => $reference,
            'payment_token' => $transaction->token,
            'payment_url' => $transaction->redirect_url,
            'payment_type' => null,
            'paid_at' => null,
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
        } catch (\Throwable $exception) {
            Log::warning('Gagal mengambil status Midtrans.', [
                'order_id' => $order->id,
                'payment_reference' => $order->payment_reference,
                'error' => $exception->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Status pembayaran berhasil diambil.',
            'data' => $this->paymentPayload($order->fresh()),
        ]);
    }

    /**
     * Endpoint webhook notifikasi Midtrans.
     */
    public function notification(Request $request): JsonResponse
    {
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
        return 'MID-' . $order->id . '-' . Str::upper(Str::random(8));
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
            'payment_url' => $order->payment_url,
            'gross_amount' => (int) $order->total_harga,
            'paid_at' => $order->paid_at,
            'client_key' => config('midtrans.clientKey'),
        ];
    }
}
