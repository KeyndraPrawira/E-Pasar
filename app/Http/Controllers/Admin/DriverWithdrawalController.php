<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessDriverWithdrawalRequest;
use App\Models\DriverWithdrawal;
use App\Services\DriverWithdrawalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DriverWithdrawalController extends Controller
{
    public function __construct(
        private readonly DriverWithdrawalService $driverWithdrawalService
    ) {
    }

    public function index(): View
    {
        $withdrawals = DriverWithdrawal::with(['user', 'processor'])
            ->latest()
            ->get();

        return view('admin.driver-withdrawal.index', compact('withdrawals'));
    }

    public function show(DriverWithdrawal $driverWithdrawal): View
    {
        $driverWithdrawal->loadMissing(['user', 'wallet', 'processor']);

        return view('admin.driver-withdrawal.show', compact('driverWithdrawal'));
    }

    public function process(ProcessDriverWithdrawalRequest $request, DriverWithdrawal $driverWithdrawal): RedirectResponse
    {
        try {
            if ($request->validated('status') === DriverWithdrawal::STATUS_APPROVED) {
                $this->driverWithdrawalService->approve($driverWithdrawal, $request->user(), $request->validated());
                $message = 'Withdraw driver berhasil disetujui.';
            } else {
                $this->driverWithdrawalService->reject($driverWithdrawal, $request->user(), $request->validated());
                $message = 'Withdraw driver berhasil ditolak.';
            }
        } catch (ValidationException $exception) {
            throw $exception;
        }

        return redirect()
            ->route('driver-withdrawals.show', $driverWithdrawal)
            ->with('success', $message);
    }
}
