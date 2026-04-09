<?php

namespace App\Services;

use App\Models\DriverWallet;
use App\Models\DriverWithdrawal;
use App\Models\DriverWalletTransaction;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DriverWalletService
{
    public function getOrCreateWallet(User $driver): DriverWallet
    {
        return DriverWallet::firstOrCreate(
            ['user_id' => $driver->id],
            [
                'balance' => 0,
                'total_earned' => 0,
                'total_withdrawn' => 0,
            ]
        );
    }

    public function pendingWithdrawalAmount(DriverWallet $wallet): int
    {
        return (int) $wallet->withdrawals()
            ->where('status', DriverWithdrawal::STATUS_PENDING)
            ->sum('amount');
    }

    public function availableBalance(DriverWallet $wallet): int
    {
        return max(0, (int) $wallet->balance - $this->pendingWithdrawalAmount($wallet));
    }

    public function creditCompletedOrder(Order $order): ?DriverWalletTransaction
    {
        if (!$order->canCreditDriverWallet()) {
            return null;
        }

        return DB::transaction(function () use ($order): ?DriverWalletTransaction {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedOrder || !$lockedOrder->canCreditDriverWallet()) {
                return null;
            }

            $driver = User::query()->find($lockedOrder->driver_id);
            if (!$driver || $driver->role !== 'driver') {
                return null;
            }

            $wallet = DriverWallet::query()->firstOrCreate(
                ['user_id' => $driver->id],
                [
                    'balance' => 0,
                    'total_earned' => 0,
                    'total_withdrawn' => 0,
                ]
            );

            $wallet = DriverWallet::query()
                ->whereKey($wallet->id)
                ->lockForUpdate()
                ->firstOrFail();

            $amount = (int) ($lockedOrder->driver_earning_amount ?: $lockedOrder->ongkir);

            if ($amount <= 0) {
                $lockedOrder->update([
                    'driver_earning_amount' => 0,
                    'driver_wallet_credited_at' => now(),
                ]);

                return null;
            }

            $balanceBefore = (int) $wallet->balance;
            $balanceAfter = $balanceBefore + $amount;

            $wallet->update([
                'balance' => $balanceAfter,
                'total_earned' => (int) $wallet->total_earned + $amount,
                'last_transaction_at' => now(),
            ]);

            $transaction = $wallet->transactions()->create([
                'type' => DriverWalletTransaction::TYPE_CREDIT,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => Order::class,
                'reference_id' => $lockedOrder->id,
                'description' => 'Pendapatan ongkir order ' . $lockedOrder->kode_pesanan,
                'metadata' => [
                    'order_id' => $lockedOrder->id,
                    'kode_pesanan' => $lockedOrder->kode_pesanan,
                    'payment_method' => $lockedOrder->metode_pembayaran,
                ],
            ]);

            $lockedOrder->update([
                'driver_earning_amount' => $amount,
                'driver_wallet_credited_at' => now(),
            ]);

            return $transaction;
        });
    }
}
