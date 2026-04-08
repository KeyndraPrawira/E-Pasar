<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDriverApplicationRequest;
use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DriverApplicationController extends Controller
{
    /**
     * Tampilkan form pengajuan driver.
     */
    public function create(): View|RedirectResponse
    {
        $driver = auth()->user()->loadMissing('driver')->driver;

        if ($driver !== null && !$driver->isRejected()) {
            return redirect()
                ->route('driver.application.status')
                ->with('info', 'Pengajuan driver Anda sedang diproses atau sudah disetujui.');
        }

        return view('driver.create', compact('driver'));
    }

    /**
     * Simpan pengajuan driver user.
     */
    public function store(StoreDriverApplicationRequest $request): RedirectResponse
    {
        $user = $request->user()->loadMissing('driver');
        $existingDriver = $user->driver;

        if ($existingDriver !== null && !$existingDriver->isRejected()) {
            return redirect()
                ->route('driver.application.status')
                ->with('error', 'Anda sudah memiliki pengajuan driver yang aktif.');
        }

        $storedFiles = [];
        $oldFiles = $existingDriver !== null
            ? array_filter([
                $existingDriver->foto_ktp,
                $existingDriver->foto_sim,
                $existingDriver->foto_stnk,
                $existingDriver->foto_kendaraan,
            ])
            : [];

        try {
            foreach ($this->documentDirectories() as $field => $directory) {
                $storedFiles[$field] = $request->file($field)->store($directory, 'public');
            }

            DB::transaction(function () use ($request, $user, $existingDriver, $storedFiles): void {
                $payload = [
                    'nomor_kendaraan' => $request->validated('nomor_kendaraan'),
                    'jenis_kendaraan' => $request->validated('jenis_kendaraan'),
                    'nomor_stnk' => $request->validated('nomor_stnk'),
                    'nomor_sim' => $request->validated('nomor_sim'),
                    'foto_ktp' => $storedFiles['foto_ktp'],
                    'foto_sim' => $storedFiles['foto_sim'],
                    'foto_stnk' => $storedFiles['foto_stnk'],
                    'foto_kendaraan' => $storedFiles['foto_kendaraan'],
                    'status' => Driver::STATUS_PENDING,
                    'verification_notes' => null,
                    'verified_by' => null,
                    'verified_at' => null,
                ];

                if ($existingDriver !== null) {
                    $existingDriver->update($payload);
                } else {
                    $user->driver()->create($payload);
                }

                $user->update([
                    'role' => 'user',
                    'is_online' => false,
                ]);
            });

            if (!empty($oldFiles)) {
                Storage::disk('public')->delete($oldFiles);
            }

            return redirect()
                ->route('driver.application.status')
                ->with('success', 'Pengajuan driver berhasil dikirim dan menunggu verifikasi admin.');
        } catch (\Throwable $exception) {
            if (!empty($storedFiles)) {
                Storage::disk('public')->delete(array_values($storedFiles));
            }

            report($exception);

            return back()
                ->withInput()
                ->with('error', 'Pengajuan driver gagal disimpan. Silakan coba lagi.');
        }
    }

    /**
     * Tampilkan status verifikasi driver.
     */
    public function status(): View
    {
        $driver = auth()->user()
            ->loadMissing(['driver.verifier'])
            ->driver;

        return view('driver.status', compact('driver'));
    }

    /**
     * Direktori penyimpanan dokumen driver.
     *
     * @return array<string, string>
     */
    private function documentDirectories(): array
    {
        return [
            'foto_ktp' => 'drivers/ktp',
            'foto_sim' => 'drivers/sim',
            'foto_stnk' => 'drivers/stnk',
            'foto_kendaraan' => 'drivers/kendaraan',
        ];
    }
}
