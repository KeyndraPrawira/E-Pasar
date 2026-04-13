<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kios;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PedagangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(){
        $pedagang = User::with('kios')->where('role', 'pedagang')->get();
        $title = 'Hapus data!';
        $text = 'Apakah anda yakin ingin menghapus data ini?';
        confirmDelete($title, $text);
        return view('admin.pedagang.index', compact('pedagang'));
    }

    public function create()
    {
        $availableKios = Kios::whereNull('user_id')->orderBy('nama_kios')->get();

        return view('admin.pedagang.create', compact('availableKios'));
    }

    public function createPedagang()
    {
        return $this->create();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nomor_telepon' => 'required|string|max:15',
            'kios_id' => [
                'required',
                Rule::exists('kios', 'id')->where(fn ($query) => $query->whereNull('user_id')),
            ],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'kios_id.required' => 'Pilih kios untuk pedagang.',
            'kios_id.exists' => 'Kios yang dipilih tidak tersedia.',
        ]);

        DB::transaction(function () use ($request) {
            $pedagang = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'pedagang',
                'nomor_telepon' => $request->nomor_telepon,
            ]);

            Kios::whereKey($request->kios_id)->update([
                'user_id' => $pedagang->id,
            ]);
        });

        toast('Pedagang berhasil ditambahkan', 'success');
        return redirect()->route('pedagang.index')->with('success', 'Pedagang berhasil ditambahkan.');
    }

    public function show(User $user){
        return view('admin.pedagang.show', compact('user'));
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('admin.pedagang.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'nomor_telepon' => 'required|string|max:15',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        toast('Pedagang berhasil diperbarui', 'success');

        return redirect()->route('pedagang.index')->with('success', 'Pedagang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        DB::transaction(function () use ($user) {
            $user->kios()->update(['user_id' => null]);
            $user->delete();
        });

        toast('Pedagang berhasil dihapus', 'success');
        return redirect()->route('pedagang.index')->with('success', 'Pedagang berhasil dihapus.');
    }
}
