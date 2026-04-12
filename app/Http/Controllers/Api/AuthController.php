<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    // public function sendOtp(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email'
    //     ]);

    //     // hapus OTP lama
    //     EmailOtp::where('email', $request->email)->delete();

    //     $otp = rand(100000, 999999);

    //     EmailOtp::create([
    //         'email' => $request->email,
    //         'otp' => Hash::make($otp),
    //         'expired_at' => now()->addMinutes(3)
    //     ]);

    //     Mail::to($request->email)->send(new OtpMail($otp));

    //     return response()->json([
    //         'message' => 'OTP terkirim'
    //     ]);
    // }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        
            if (! $user) {
                return response()->json(['err' => 'email tidak ditemukan']);
            }

            if (! Hash::check($request->password, $user->password)) {
                return response()->json([
                    
                    'message' => 'password salah'
                ]);
            }
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'data' => $user
        ]);
    }


     public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $pendingRegistration = [
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'nomor_telepon' => $request->string('nomor_telepon')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
        ];

        try {
            $this->sendOtp($pendingRegistration['email']);
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'Gagal mengirim OTP ke email. Silakan coba lagi. '.$exception->getMessage());
        }

        session([
            'pending_driver_registration' => $pendingRegistration,
        ]);

        return response()->json([
            'message' => 'Kode OTP telah dikirim ke email Anda.',
            'status' => true
        ]);
    }

    public function googleLogin(Request $request)
{
    $request->validate([
        'id_token' => 'required|string'
    ]);

    // Gunakan namespace modern
    $client = new \Google\Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
    
    // Verifikasi Token ke Google
    $payload = $client->verifyIdToken($request->id_token);

    if (!$payload) {
        return response()->json(['message' => 'Token tidak valid'], 401);
    }

    // Cari user berdasarkan google_id (sub) atau email
    $user = User::where('google_id', $payload['sub'])
                ->orWhere('email', $payload['email'])
                ->first();

    $isNewUser = false;

    if (!$user) {
        // User baru banget (belum ada di DB)
        $user = User::create([
            'name'        => $payload['name'],
            'email'       => $payload['email'],
            'google_id'   => $payload['sub'],
            'foto_profil' => $payload['picture'] ?? null,
            'password'    => null, 
            'role'        => 'user',
        ]);
        $isNewUser = true;
    } else {
        // User sudah ada, tapi cek apakah dia sudah melengkapi profil (nomor telepon)
        // Kalau nomor_telepon masih kosong, anggap dia masih "New User" agar diarahkan ke form
        if (empty($user->nomor_telepon)) {
            $isNewUser = true;
        } else {
            $isNewUser = false;
        }

        // Update google_id jika sebelumnya belum ada
        if (!$user->google_id) {
            $user->update(['google_id' => $payload['sub']]);
        }
    }

    // Buat token login (Sanctum)
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token'       => $token,
        'user'        => $user,
        'is_new_user' => $isNewUser,
    ]);
}

    // ==================== COMPLETE PROFILE ====================
    public function completeProfile(Request $request)
{
    // 1. Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'nomor_telepon' => 'required|string|max:15',
    ]);

    // 2. Ambil user yang sedang login via Sanctum Token
    $user = $request->user(); 

    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    // 3. Update datanya
    $user->update([
        'name' => $request->name, 
        'nomor_telepon' => $request->nomor_telepon,
        // Tambahkan field lain jika perlu, misal: 'alamat' => $request->alamat
    ]);

    return response()->json([
        'message' => 'Profil berhasil dilengkapi',
        'user'    => $user,
        'status'  => true
    ]);
}
}

