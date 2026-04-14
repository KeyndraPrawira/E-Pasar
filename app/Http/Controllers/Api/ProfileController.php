<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HaversineHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Alamat;
use app\http\Controllers\Controller;
use App\Models\Pasar;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{

    // ambil data profile user
    public function show()
    {
        $user = Auth::user()->load('alamat');

        return response()->json([
            'data' => $user
        ]);
    }


    // update nama dan nomor telepon
    public function update(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'user') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
        ]);

        $user = Auth::user();

        $user->update([
            'name' => $request->name,
            'nomor_telepon' => $request->nomor_telepon
            
        ]);

        return response()->json([
            'message' => 'Profil berhasil diupdate',
            'data' => $user
        ]);
    }

    public function uploadFotoProfil(Request $request)
{
    $user = Auth::user();

    // validasi dasar
    $request->validate([
        'foto_profil' => 'required|image|mimes:jpg,jpeg,png|max:2048'
    ]);

   

    // hapus foto lama (biar storage ga jadi kuburan file)
    if ($user->foto_profil && file_exists(storage_path('app/public/' . $user->foto_profil))) {
        unlink(storage_path('app/public/' . $user->foto_profil));
    }

    // simpan foto baru
    $path = $request->file('foto_profil')->store('foto_profil', 'public');

    // update ke user
    $user->update([
        'foto_profil' => $path
    ]);

    return response()->json([
        'message' => 'Foto profil berhasil diupload',
        'data' => $user
    ]);
}


    // set atau update alamat (map)
   public function setAlamat(Request $request)
{
    $request->validate([
        'alamat_lengkap' => 'required|string',
        'latitude'       => 'required|numeric',
        'longitude'      => 'required|numeric',
    ]);

    $pasar = Pasar::first();
    if (!$pasar) {
        return response()->json([
            'message' => 'Data pasar belum dikonfigurasi'
        ], 500);
    }

    // Hitung jarak pasar → rumah user
    $jarakKm = HaversineHelper::hitungJarak(
        $pasar->latitude,
        $pasar->longitude,
        $request->latitude,
        $request->longitude
    );

    $user   = Auth::user();
    $alamat = Alamat::updateOrCreate(
        ['user_id' => $user->id],
        [
            'alamat_lengkap' => $request->alamat_lengkap,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
            'jarak_km'       => $jarakKm, // ✅ otomatis tersimpan
        ]
    );

    return response()->json([
        'message' => 'Alamat berhasil disimpan',
        'data'    => $alamat
    ]);
}

    public function updatePassword(Request $request)
{
    $user = Auth::user();
    
    $allowedRoles = ['user', 'pedagang', 'driver'];
    if (!in_array($user->role, $allowedRoles)) {
        return response()->json(['message' => 'Unauthorized untuk admin'], 403);
    }

    $request->validate([
        'current_password' => 'required|string',
        'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'message' => 'Password saat ini tidak sesuai'
        ], 422);
    }

    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Password berhasil diubah.'
    ]);
}

}
