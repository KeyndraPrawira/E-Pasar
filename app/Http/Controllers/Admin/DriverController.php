<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index()
    {
        $driver = Driver::with('user')->get();

        $title = 'Hapus data!';
        $text = 'Apakah anda yakin ingin menghapus data ini?';
        confirmDelete($title, $text);

        return view('admin.driver.index', compact('driver'));
    }

    public function create()
    {
        return view('admin.driver.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nomor_telepon' => 'required',

            'nomor_kendaraan' => 'required|unique:drivers,nomor_kendaraan',
            'jenis_kendaraan' => 'required',
            'nomor_stnk' => 'required|integer',
            'nomor_sim' => 'required|integer',

            'foto_ktp' => 'required|image|mimes:jpg,jpeg,png',
            'foto_sim' => 'required|image|mimes:jpg,jpeg,png',
            'foto_stnk' => 'required|image|mimes:jpg,jpeg,png',
            'foto_kendaraan' => 'required|image|mimes:jpg,jpeg,png',
        ],
        [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',

            'nomor_kendaraan.required' => 'Nomor kendaraan wajib diisi.',
            'nomor_kendaraan.unique' => 'Nomor kendaraan sudah terdaftar.',
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib diisi.',
            'nomor_stnk.required' => 'Nomor STNK wajib diisi.',
            'nomor_sim.required' => 'Nomor SIM wajib diisi.',
            'nomor_stnk.integer' => 'Nomor STNK hanya boleh berisi angka.',
            'nomor_sim.integer' => 'Nomor SIM hanya boleh berisi angka.',

            'foto_ktp.required' => 'Foto KTP wajib diunggah.',
            'foto_ktp.image' => 'File yang diunggah harus berupa gambar.',
            'foto_sim.required' => 'Foto SIM wajib diunggah.',
            'foto_sim.image' => 'File yang diunggah harus berupa gambar.',
            'foto_stnk.required' => 'Foto STNK wajib diunggah.',
            'foto_stnk.image' => 'File yang diunggah harus berupa gambar.',
            'foto_kendaraan.required' => 'Foto kendaraan wajib diunggah.',
            'foto_kendaraan.image' => 'File yang diunggah harus berupa gambar.',
        ]);

        DB::beginTransaction();

        try {
            // upload
            $ktp = $request->file('foto_ktp')->store('drivers/ktp', 'public');
            $sim = $request->file('foto_sim')->store('drivers/sim', 'public');
            $stnk = $request->file('foto_stnk')->store('drivers/stnk', 'public');
            $kendaraan = $request->file('foto_kendaraan')->store('drivers/kendaraan', 'public');

            // user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nomor_telepon' => $request->nomor_telepon,
                'role' => 'driver'
            ]);

            // driver
            Driver::create([
                'user_id' => $user->id,
                'nomor_kendaraan' => $request->nomor_kendaraan,
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'nomor_stnk' => $request->nomor_stnk,
                'nomor_sim' => $request->nomor_sim,
                'foto_ktp' => $ktp,
                'foto_sim' => $sim,
                'foto_stnk' => $stnk,
                'foto_kendaraan' => $kendaraan,
            ]);

            DB::commit();

            toast('Driver berhasil ditambahkan', 'success');
           
            return redirect()->route('driver.index');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $driver = Driver::with('user')->findOrFail($id);
        return view('admin.driver.edit', compact('driver'));
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $driver->user_id,
            'password' => 'nullable|min:6',
            'nomor_telepon' => 'required',


            'nomor_kendaraan' => 'required|unique:drivers,nomor_kendaraan,' . $id,
            'jenis_kendaraan' => 'required',
            'nomor_stnk' => 'required|integer',
            'nomor_sim' => 'required|integer',

            'foto_ktp' => 'nullable|image',
            'foto_sim' => 'nullable|image',
            'foto_stnk' => 'nullable|image',
            'foto_kendaraan' => 'nullable|image',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('foto_ktp')) {
                $driver->foto_ktp = $request->file('foto_ktp')->store('drivers/ktp', 'public');
            }

            if ($request->hasFile('foto_sim')) {
                $driver->foto_sim = $request->file('foto_sim')->store('drivers/sim', 'public');
            }

            if ($request->hasFile('foto_stnk')) {
                $driver->foto_stnk = $request->file('foto_stnk')->store('drivers/stnk', 'public');
            }

            if ($request->hasFile('foto_kendaraan')) {
                $driver->foto_kendaraan = $request->file('foto_kendaraan')->store('drivers/kendaraan', 'public');
            }

            $driver->update([
                'nomor_kendaraan' => $request->nomor_kendaraan,
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'nomor_stnk' => $request->nomor_stnk,
                'nomor_sim' => $request->nomor_sim,
            ]);

            DB::commit();

            toast('Driver berhasil diupdate', 'success');
            return redirect()->route('driver.index');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);

        $driver->user()->delete(); // ikut hapus user
        $driver->delete();

        toast('Driver berhasil dihapus', 'success');
        return redirect()->route('driver.index');
    }
}