<?php

namespace App\Services;

use App\Models\DriverWallet;
use App\Models\DriverWithdrawal;
use App\Models\DriverWalletTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DriverWithdrawalService
{
    public function __construct(
        private readonly DriverWalletService $driverWalletService
    ) {
    }

    public function createRequest(User $driver, array $payload): DriverWithdrawal
    {
        return DB::transaction(function () use ($driver, $payload): DriverWithdrawal {
            $wallet = $this->driverWalletService->getOrCreateWallet($driver);
            $wallet = DriverWallet::query()
                ->whereKey($wallet->id)
                ->lockForUpdate()
                ->firstOrFail();

            $pendingAmount = (int) $wallet->withdrawals()
                ->where('status', DriverWithdrawal::STATUS_PENDING)
                ->sum('amount');

            $availableBalance = max(0, (int) $wallet->balance - $pendingAmount);
            $amount = (int) $payload['amount'];

            if ($amount > $availableBalance) {
                throw ValidationException::withMessages([
                    'amount' => 'Saldo yang bisa ditarik tidak mencukupi.',
                ]);
            }

            return $wallet->withdrawals()->create([
                'user_id' => $driver->id,
                'amount' => $amount,
                'bank_name' => $payload['bank_name'],
                'account_name' => $payload['account_name'],
                'account_number' => $payload['account_number'],
                'status' => DriverWithdrawal::STATUS_PENDING,
                'requested_notes' => $payload['requested_notes'] ?? null,
            ]);
        });
    }

    public function approve(DriverWithdrawal $withdrawal, User $admin, array $payload = []): DriverWithdrawal
    {
        return DB::transaction(function () use ($withdrawal, $admin, $payload): DriverWithdrawal {
            $lockedWithdrawal = DriverWithdrawal::query()
                ->whereKey($withdrawal->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$lockedWithdrawal->isPending()) {
                throw ValidationException::withMessages([
                    'status' => 'Permintaan withdraw ini sudah diproses sebelumnya.',
                ]);
            }

            $wallet = DriverWallet::query()
                ->whereKey($lockedWithdrawal->driver_wallet_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) $lockedWithdrawal->amount > (int) $wallet->balance) {
                throw ValidationException::withMessages([
                    'amount' => 'Saldo driver tidak cukup untuk menyetujui withdraw ini.',
                ]);
            }

            $balanceBefore = (int) $wallet->balance;
            $balanceAfter = $balanceBefore - (int) $lockedWithdrawal->amount;

            $wallet->update([
                'balance' => $balanceAfter,
                'total_withdrawn' => (int) $wallet->total_withdrawn + (int) $lockedWithdrawal->amount,
                'last_transaction_at' => now(),
            ]);

            $wallet->transactions()->create([
                'type' => DriverWalletTransaction::TYPE_DEBIT,
                'amount' => (int) $lockedWithdrawal->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => DriverWithdrawal::class,
                'reference_id' => $lockedWithdrawal->id,
                'description' => 'Withdraw driver disetujui',
                'metadata' => [
                    'bank_name' => $lockedWithdrawal->bank_name,
                    'account_name' => $lockedWithdrawal->account_name,
                    'account_number' => $lockedWithdrawal->account_number,
                    'transfer_reference' => $payload['transfer_reference'] ?? null,
                ],
            ]);

            $lockedWithdrawal->update([
                'status' => DriverWithdrawal::STATUS_APPROVED,
                'admin_notes' => $payload['admin_notes'] ?? null,
                'transfer_reference' => $payload['transfer_reference'] ?? null,
                'processed_by' => $admin->id,
                'processed_at' => now(),
            ]);

            return $lockedWithdrawal->fresh(['user', 'wallet', 'processor']);
        });
    }

    public function reject(DriverWithdrawal $withdrawal, User $admin, array $payload = []): DriverWithdrawal
    {
        return DB::transaction(function () use ($withdrawal, $admin, $payload): DriverWithdrawal {
            $lockedWithdrawal = DriverWithdrawal::query()
                ->whereKey($withdrawal->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$lockedWithdrawal->isPending()) {
                throw ValidationException::withMessages([
                    'status' => 'Permintaan withdraw ini sudah diproses sebelumnya.',
                ]);
            }

            $lockedWithdrawal->update([
                'status' => DriverWithdrawal::STATUS_REJECTED,
                'admin_notes' => $payload['admin_notes'] ?? null,
                'processed_by' => $admin->id,
                'processed_at' => now(),
            ]);

            return $lockedWithdrawal->fresh(['user', 'wallet', 'processor']);
        });
    }
}
