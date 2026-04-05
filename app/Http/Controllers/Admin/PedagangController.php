<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

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

    public function createPedagang(){
        return view('admin.pedagang.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nomor_telepon' => 'required|string|max:15',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
        ]);
    

    

        User::create(   
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => "pedagang",
                'nomor_telepon' => $request->nomor_telepon,
            ]);
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

    public function update(Request $request, User $user){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255' . $user->id,
            'password' => 'nullable',
            'nomor_telepon' => 'required|string|max:15',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
        ]);

        $user->update(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'nomor_telepon' => $request->nomor_telepon,
            ]
        );

        toast('Pedagang berhasil diperbarui', 'success');

        return redirect()->route('pedagang.index')->with('success', 'Pedagang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        toast('Pedagang berhasil dihapus', 'success');
        return redirect()->route('pedagang.index')->with('success', 'Pedagang berhasil dihapus.');
    }
}
