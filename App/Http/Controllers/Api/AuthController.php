<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\OtpMail;
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
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'nomor_telepon' => 'required|string|max:15',
        // 'otp' => 'required|string',
   
    ]);

//    $otpData = EmailOtp::where('email', $request->email)
//     ->where('expired_at', '>', now())
//     ->first();

//         if (!$otpData || !Hash::check($request->otp, $otpData->otp)) {
//             return response()->json([
//                 'message' => 'OTP invalid'
//             ], 400);
//         }


    // CREATE USER
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'nomor_telepon' => $request->nomor_telepon,
        'role' => 'user',
    ]);

    //  HAPUS OTP (sekali pakai)
    // $otpData->delete();

   

    return response()->json([
        'message' => 'Register berhasil',
        'user' => $user,
        'status' => 'true'
    ], 201);
}

    public function googleLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string'
        ]);

        $client = new \Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($request->id_token);

        if (!$payload) {
            return response()->json(['message' => 'Token tidak valid'], 401);
        }

        // Cek by google_id dulu, kalau ga ada cek by email
        $user = User::where('google_id', $payload['sub'])
                    ->orWhere('email', $payload['email'])
                    ->first();

        if (!$user) {
            // Buat user baru
            $user = User::create([
                'name'       => $payload['name'],
                'email'      => $payload['email'],
                'google_id'  => $payload['sub'],
                'foto_profil'=> $payload['picture'],
                'password'   => null,
                'role'       => 'user',
            ]);
            $isNewUser = true;
        } else {
            // Update google_id kalau login email biasa sebelumnya
            if (!$user->google_id) {
                $user->update(['google_id' => $payload['sub']]);
            }
            $isNewUser = false;
        }

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
        $request->validate([
            'username'      => 'required|unique:users,username|min:3',
            'nomor_telepon' => 'required|string|max:15',
        ]);

        $user = $request->user(); // ambil user dari Sanctum token

        $user->update([
            'username'      => $request->username,
            'nomor_telepon' => $request->nomor_telepon,
        ]);

        return response()->json([
            'message' => 'Profil berhasil dilengkapi',
            'user'    => $user,
        ]);
    }
}

