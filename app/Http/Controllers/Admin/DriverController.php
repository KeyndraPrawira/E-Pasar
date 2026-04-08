<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyDriverRequest;
use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DriverController extends Controller
{
    
    /**
     * Tampilkan daftar pengajuan driver.
     */
    public function index(): View
    {
        
        $drivers = Driver::with(['user', 'verifier'])
            ->latest()
            ->get();

        return view('admin.driver.index', compact('drivers'));
    }

    /**
     * Tampilkan detail pengajuan driver.
     */
    public function show(Driver $driver): View
    {
        $driver->loadMissing(['user', 'verifier']);

        return view('admin.driver.show', compact('driver'));
    }

    /**
     * Verifikasi pengajuan driver oleh admin.
     */
    public function verify(VerifyDriverRequest $request, Driver $driver): RedirectResponse
    {
        if (!$driver->isPending()) {
            return redirect()
                ->route('driver.show', $driver)
                ->with('error', 'Hanya pengajuan dengan status pending yang dapat diverifikasi.');
        }

        DB::transaction(function () use ($request, $driver): void {
            $status = $request->validated('status');

            $driver->update([
                'status' => $status,
                'verification_notes' => $request->validated('verification_notes'),
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $driver->user()->update([
                'role' => 'driver',
                'is_online' => false,
            ]);
        });

        $message = $driver->status === Driver::STATUS_APPROVED
            ? 'Pengajuan driver berhasil disetujui.'
            : 'Pengajuan driver berhasil ditolak.';

        return redirect()
            ->route('driver.show', $driver)
            ->with('success', $message);
    }
}
