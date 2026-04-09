<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDriverWithdrawalRequest;
use App\Services\DriverWithdrawalService;
use App\Services\DriverWalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverWithdrawalController extends Controller
{
    public function __construct(
        private readonly DriverWithdrawalService $driverWithdrawalService,
        private readonly DriverWalletService $driverWalletService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $wallet = $this->driverWalletService->getOrCreateWallet($request->user());
        $perPage = min((int) $request->integer('per_page', 15), 50);

        $withdrawals = $wallet->withdrawals()
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat withdraw driver berhasil diambil.',
            'data' => $withdrawals,
        ]);
    }

    public function store(StoreDriverWithdrawalRequest $request): JsonResponse
    {
        $withdrawal = $this->driverWithdrawalService->createRequest(
            $request->user(),
            $request->validated()
        );

        $wallet = $this->driverWalletService->getOrCreateWallet($request->user());

        return response()->json([
            'status' => 'success',
            'message' => 'Permintaan withdraw berhasil dibuat dan menunggu persetujuan admin.',
            'data' => [
                'withdrawal' => $withdrawal,
                'available_balance' => $this->driverWalletService->availableBalance($wallet),
                'pending_withdrawal_amount' => $this->driverWalletService->pendingWithdrawalAmount($wallet),
            ],
        ], 201);
    }
}
