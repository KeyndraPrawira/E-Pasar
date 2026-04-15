<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminDriverRequest;
use App\Http\Requests\UpdateAdminDriverRequest;
use App\Http\Requests\VerifyDriverRequest;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
     * Tampilkan form tambah driver oleh admin.
     */
    public function create()
    {
        $users = User::query()
            ->doesntHave('driver')
            ->orderBy('name')
            ->get();

        return view('admin.driver.create', compact('users'));
    }

    /**
     * Simpan data driver baru dari admin.
     */
    public function store(StoreAdminDriverRequest $request)
    {
        $storedFiles = [];

        try {
            foreach ($this->documentDirectories() as $field => $directory) {
                $storedFiles[$field] = $request->file($field)->store($directory, 'public');
            }

            $driver = null;
            DB::transaction(function () use ($request, &$driver, $storedFiles) {
                $verifiedBy =  auth()->id();
                $verifiedAt = now();

                $driver = Driver::create([
                    'user_id' => $request->validated('user_id'),
                    'nomor_kendaraan' => $request->validated('nomor_kendaraan'),
                    'jenis_kendaraan' => $request->validated('jenis_kendaraan'),
                    'nomor_stnk' => $request->validated('nomor_stnk'),
                    'nomor_sim' => $request->validated('nomor_sim'),
                    'foto_ktp' => $storedFiles['foto_ktp'],
                    'foto_sim' => $storedFiles['foto_sim'],
                    'foto_stnk' => $storedFiles['foto_stnk'],
                    'foto_kendaraan' => $storedFiles['foto_kendaraan'],
                    'foto_diri' => $storedFiles['foto_diri'],
                    'status' => 'approved',
                    'verification_notes' => $request->validated('verification_notes'),
                    'verified_by' => $verifiedBy,
                    'verified_at' => $verifiedAt,
                ]);

                $driver->user()->update([
                    'role' => 'driver',
                    'is_online' => false,
                    'foto_profile'  => $driver->foto_diri
                ]);
            });

            return redirect()
                ->route('driver.show', $driver)
                ->with('success', 'Driver berhasil ditambahkan.');
        } catch (\Throwable $exception) {
            if (! empty($storedFiles)) {
                Storage::disk('public')->delete(array_values($storedFiles));
            }

            report($exception);

            return back()
                ->withInput()
                ->with('error', 'Driver gagal disimpan. Silakan coba lagi.');
        }
    }

    /**
     * Tampilkan detail pengajuan driver.
     */
    public function show(Driver $driver)
    {
        $driver->loadMissing(['user', 'verifier']);

        return view('admin.driver.show', compact('driver'));
    }

    /**
     * Tampilkan form edit driver.
     */
    public function edit(Driver $driver)
    {
        $driver->loadMissing(['user', 'verifier']);

        return view('admin.driver.edit', compact('driver'));
    }

    /**
     * Update data driver oleh admin.
     */
    public function update(UpdateAdminDriverRequest $request, Driver $driver)
    {
        $driver->loadMissing('user');
        $storedFiles = [];
        $oldFiles = [];

        try {
            foreach ($this->documentDirectories() as $field => $directory) {
                if ($request->hasFile($field)) {
                    $storedFiles[$field] = $request->file($field)->store($directory, 'public');
                    $oldFiles[] = $driver->{$field};
                }
            }

            DB::transaction(function () use ($request, $driver, $storedFiles): void {
                $status = $request->validated('status');
                $verifiedBy = $status === Driver::STATUS_PENDING ? null : auth()->id();
                $verifiedAt = $status === Driver::STATUS_PENDING ? null : now();

                $payload = [
                    'nomor_kendaraan' => $request->validated('nomor_kendaraan'),
                    'jenis_kendaraan' => $request->validated('jenis_kendaraan'),
                    'nomor_stnk' => $request->validated('nomor_stnk'),
                    'nomor_sim' => $request->validated('nomor_sim'),
                    'status' => $status,
                    'verification_notes' => $request->validated('verification_notes'),
                    'verified_by' => $verifiedBy,
                    'verified_at' => $verifiedAt,
                ];

                foreach ($storedFiles as $field => $path) {
                    $payload[$field] = $path;
                }



                $driver->update($payload);
                $driver->user()->update([
                    'role' => 'driver',
                    'is_online' => false,
                ]);
            });

            if (! empty($oldFiles)) {
                Storage::disk('public')->delete(array_filter($oldFiles));
            }

            return redirect()
                ->route('driver.show', $driver)
                ->with('success', 'Data driver berhasil diperbarui.');
        } catch (\Throwable $exception) {
            if (! empty($storedFiles)) {
                Storage::disk('public')->delete(array_values($storedFiles));
            }

            report($exception);

            return back()
                ->withInput()
                ->with('error', 'Data driver gagal diperbarui. Silakan coba lagi.');
        }
    }

    /**
     * Hapus data driver beserta dokumen.
     */
    public function destroy(Driver $driver)
    {
        $driver->loadMissing('user');
        $files = array_filter([
            $driver->foto_ktp,
            $driver->foto_sim,
            $driver->foto_stnk,
            $driver->foto_kendaraan,
            $driver->foto_diri,
        ]);

        DB::transaction(function () use ($driver) {
            $driver->user()->update([
                'role' => 'user',
                'is_online' => false,
            ]);

            $driver->delete();
        });

        if (! empty($files)) {
            Storage::disk('public')->delete($files);
        }

        return redirect()
            ->route('driver.index')
            ->with('success', 'Driver berhasil dihapus.');
    }

    /**
     * Verifikasi pengajuan driver oleh admin.
     */
    public function verify(VerifyDriverRequest $request, Driver $driver)
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

    /**
     * Direktori penyimpanan dokumen driver.
     *
     * @return array<string, string>
     */
    private function documentDirectories()
    {
        return [
            'foto_ktp' => 'drivers/ktp',
            'foto_sim' => 'drivers/sim',
            'foto_stnk' => 'drivers/stnk',
            'foto_kendaraan' => 'drivers/kendaraan',
            'foto_diri' => 'drivers/diri',
        ];
    }
}
