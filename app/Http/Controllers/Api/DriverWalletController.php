<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DriverWalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverWalletController extends Controller
{
    public function __construct(
        private readonly DriverWalletService $driverWalletService
    ) {
    }

    public function show(Request $request): JsonResponse
    {
        $wallet = $this->driverWalletService->getOrCreateWallet($request->user()->loadMissing('driverWallet'));
        $wallet->load([
            'transactions' => fn ($query) => $query->latest()->limit(10),
        ]);
        $pendingWithdrawalAmount = $this->driverWalletService->pendingWithdrawalAmount($wallet);
        $availableBalance = $this->driverWalletService->availableBalance($wallet);

        return response()->json([
            'status' => 'success',
            'message' => 'Data dompet driver berhasil diambil.',
            'data' => [
                'balance' => (int) $wallet->balance,
                'available_balance' => $availableBalance,
                'pending_withdrawal_amount' => $pendingWithdrawalAmount,
                'total_earned' => (int) $wallet->total_earned,
                'total_withdrawn' => (int) $wallet->total_withdrawn,
                'last_transaction_at' => $wallet->last_transaction_at,
                'recent_transactions' => $wallet->transactions->map(fn ($transaction) => [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => (int) $transaction->amount,
                    'balance_before' => (int) $transaction->balance_before,
                    'balance_after' => (int) $transaction->balance_after,
                    'description' => $transaction->description,
                    'reference_type' => $transaction->reference_type,
                    'reference_id' => $transaction->reference_id,
                    'metadata' => $transaction->metadata,
                    'created_at' => $transaction->created_at,
                ])->values(),
            ],
        ]);
    }

    public function transactions(Request $request): JsonResponse
    {
        $wallet = $this->driverWalletService->getOrCreateWallet($request->user());
        $perPage = min((int) $request->integer('per_page', 15), 50);

        $transactions = $wallet->transactions()
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat dompet driver berhasil diambil.',
            'data' => $transactions,
        ]);
    }
}
