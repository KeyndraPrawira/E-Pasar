<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::where('role', 'user')->get();
        $title = 'Hapus data!';
        $text = 'Apakah anda yakin ingin menghapus data ini?';
        confirmDelete($title, $text);
        return view('admin.Pengguna.index', compact('user'));
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
        'name'          => 'required|string|max:255',
        'email'         => 'required|string|email|max:255|unique:users',
        'password'      => 'required|string|min:8',
        'role'          => 'required|in:user,pedagang,driver',
        'nomor_telepon' => 'required|string|max:15',
        'foto_profil'   => 'nullable|mimes:jpg,png,jpeg,svg'
    ], [
        'name.required'         => 'Nama wajib diisi.',
        'email.required'        => 'Email wajib diisi.',
        'email.email'           => 'Format email tidak valid.',
        'email.unique'          => 'Email sudah terdaftar.',
        'password.required'     => 'Password wajib diisi.',
        'password.min'          => 'Password minimal 8 karakter.',
        'role.required'         => 'Role wajib dipilih.',
        'role.in'               => 'Role yang dipilih tidak valid.',
        'nomor_telepon.required'=> 'Nomor telepon wajib diisi.',
        'foto_profil.mimes'     => 'File harus berupa gambar jpg, png, jpeg, atau svg.',
    ]);

    $data = [
        'name'          => $request->name,
        'email'         => $request->email,
        'password'      => bcrypt($request->password),
        'role'          => $request->role,
        'nomor_telepon' => $request->nomor_telepon,
        'foto_profil'   => null,
    ];

    if ($request->hasFile('foto_profil')) {
        $tanggal  = Carbon::now()->format('dmY');
        $urutan   = User::whereDate('created_at', Carbon::today())->count() + 1;
        $ext      = $request->file('foto_profil')->getClientOriginalExtension();
        $namaFile = $tanggal . '_' . $urutan . '.' . $ext;
        $data['foto_profil'] = $request->file('foto_profil')
            ->storeAs('foto_profil', $namaFile, 'public');
    }

    User::create($data);

    toast('Pengguna berhasil ditambahkan', 'success');
    return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
}
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.Pengguna.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255' . $user->id,
            'password' => 'nullable',
            'role' => 'required|in:user,pedagang,driver',
            'nomor_telepon' => 'required|string|max:15',
            'foto'
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

        toast('Pengguna berhasil diperbarui', 'success');

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        toast('Pengguna berhasil dihapus', 'success');
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
