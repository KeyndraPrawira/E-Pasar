<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Database;

class FirebaseOrderSyncService
{
    public function __construct(
        private readonly Database $database
    ) {
    }

    public function sync(Order $order): void
    {
        try {
            $snapshot = $order->fresh(['orderDetails.produk', 'buyer', 'driver']) ?? $order->loadMissing(['orderDetails.produk', 'buyer', 'driver']);

            $orderPayload = $snapshot->toArray();
            $orderPayload['synced_at'] = now()->toIso8601String();
            $orderPayload['payment'] = $this->paymentPayload($snapshot);

            $this->database->getReference('orders/' . $snapshot->id)->set($orderPayload);

            if ($snapshot->status === 'menunggu_driver') {
                $this->database->getReference('pending_orders/' . $snapshot->id)->set($orderPayload);
            } else {
                $this->database->getReference('pending_orders/' . $snapshot->id)->remove();
            }

            $this->database->getReference('payments/' . $snapshot->id)->set($this->paymentPayload($snapshot));

            Log::info('Firebase order sync berhasil', [
                'order_id' => $snapshot->id,
                'status' => $snapshot->status,
                'payment_status' => $snapshot->payment_status,
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Firebase order sync gagal', [
                'order_id' => $order->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function paymentPayload(Order $order): array
    {
        return [
            'order_id' => $order->id,
            'kode_pesanan' => $order->kode_pesanan,
            'buyer_id' => $order->buyer_id,
            'driver_id' => $order->driver_id,
            'status' => $order->status,
            'payment_method' => $order->metode_pembayaran,
            'payment_status' => $order->payment_status,
            'payment_reference' => $order->payment_reference,
            'payment_token' => $order->payment_token,
            'payment_url' => $this->normalizePaymentUrl($order->payment_url),
            'payment_type' => $order->payment_type,
            'gross_amount' => (int) $order->total_harga,
            'paid_at' => optional($order->paid_at)->toIso8601String(),
            'updated_at' => optional($order->updated_at)->toIso8601String(),
            'synced_at' => now()->toIso8601String(),
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
