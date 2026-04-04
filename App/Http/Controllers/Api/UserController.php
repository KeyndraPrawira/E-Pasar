<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Data pengguna berhasil ditampilkan',
            'data' => $user
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,pedagang,driver',
            'nomor_telepon' => 'required|string|max:15',
            
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
        ]);
        User::create(   
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'nomor_telepon' => $request->nomor_telepon,
            ]);
        
        return response()->json([
            'data' => $request->all(),
            'status' => 'success',
            'message' => 'Pengguna berhasil ditambahkan',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = User::find($user->id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data pengguna berhasil ditampilkan',
            'data' => $user
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data pengguna berhasil ditampilkan',
            'data' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255' . $user->id,
            'role' => 'required|in:user,pedagang,driver',
            'nomor_telepon' => 'required|string|max:15',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
        ]);

        $user->update(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'nomor_telepon' => $request->nomor_telepon,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna berhasil diperbarui',
            'data' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna berhasil dihapus',
        ], 200);
    }

    public function setActive(Request $request)
{
    $user = $request->user();

    if ($user->role !== 'driver') {
        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }

    $request->validate([
        'is_online' => 'required|boolean'
    ]);

    $user->update([
        'is_online' => $request->is_online
    ]);
    if ($user->is_online == 1) {
        $status = 'online';
    }else {
        $status = 'offline';
    }   
    return response()->json([
        'message' => 'Status aktif berhasil diubah',
        'status' => $status
    ]);
}
}