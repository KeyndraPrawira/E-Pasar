<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    private const RESEND_MAX_ATTEMPTS = 3;
    private const RESEND_DECAY_MINUTES = 10;
    private const OTP_VALID_MINUTES = 3;
    private const PENDING_CHANGE_CACHE_TTL = 10; // minutes

    /**
     * Step 1: Kirim OTP ke email user yang sudah login
     * POST /api/password-change/send-otp
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        // Hanya untuk pedagang, user, driver
        $allowedRoles = ['user', 'pedagang', 'driver'];
        if (!in_array($user->role, $allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Fitur ini hanya untuk user, pedagang, dan driver.'
            ], 403);
        }

        $validator = \Validator::make($request->all(), [
            'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Password tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $newPasswordHash = Hash::make($request->new_password);

        try {
            $this->generateAndSendOtp($user->email);
        } catch (\Throwable $e) {
            \Log::error('Gagal kirim OTP password change', ['email' => $user->email, 'error' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP. Coba lagi.'
            ], 500);
        }

        // Simpan new_password di cache (TTL 10 menit)
        $cacheKey = $this->getPendingChangeKey($user->id);
        Cache::put($cacheKey, $newPasswordHash, now()->addMinutes(self::PENDING_CHANGE_CACHE_TTL));

        return response()->json([
            'success' => true,
            'message' => 'OTP dikirim ke ' . $user->email . '. Berlaku ' . self::OTP_VALID_MINUTES . ' menit.',
            'email' => $user->email
        ]);
    }

    /**
     * Step 2: Verifikasi OTP
     * POST /api/password-change/verify-otp  
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $user = Auth::user();
        $allowedRoles = ['user', 'pedagang', 'driver'];
        if (!in_array($user->role, $allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = \Validator::make($request->all(), [
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $cacheKey = $this->getPendingChangeKey($user->id);
        if (!Cache::has($cacheKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi ganti password habis. Kirim OTP ulang.'
            ], 422);
        }

        // Cek OTP record terbaru untuk purpose password_change
        $otpRecord = EmailOtp::where('email', $user->email)
            ->where('purpose', EmailOtp::PURPOSE_PASSWORD_CHANGE)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otpRecord || $otpRecord->expired_at->isPast() || !Hash::check($request->otp, $otpRecord->otp)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP salah atau kedaluwarsa'
            ], 422);
        }

        // Tandai OTP sebagai used
        $otpRecord->update(['used_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'OTP valid. Lanjutkan ganti password.'
        ]);
    }

    /**
     * Helper: Generate OTP + kirim email
     */
    private function generateAndSendOtp(string $email): void
    {
        // Hapus OTP lama yang belum dipakai
        EmailOtp::where('email', $email)
            ->where('purpose', EmailOtp::PURPOSE_PASSWORD_CHANGE)
            ->whereNull('used_at')
            ->delete();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        EmailOtp::create([
            'email' => $email,
            'purpose' => EmailOtp::PURPOSE_PASSWORD_CHANGE,
            'otp' => Hash::make($otp),
            'expired_at' => now()->addMinutes(self::OTP_VALID_MINUTES),
        ]);

        Mail::to($email)->send(new OtpMail($otp));
    }

    /**
     * Cache key untuk pending password change
     */
    private function getPendingChangeKey(int $userId): string
    {
        return 'password_change_pending:' . $userId;
    }

    /**
     * Resend OTP (dengan rate limit)
     */
    public function resendOtp(Request $request): JsonResponse
    {
        $user = Auth::user();
        $allowedRoles = ['user', 'pedagang', 'driver'];
        if (!in_array($user->role, $allowedRoles)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $cacheKey = $this->getPendingChangeKey($user->id);
        if (!Cache::has($cacheKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi ganti password habis. Mulai dari awal.'
            ], 422);
        }

        $rateLimitKey = 'password_change_resend:' . $user->id;
        $attempts = (int) Cache::get($rateLimitKey, 0);

        if ($attempts >= self::RESEND_MAX_ATTEMPTS) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak resend. Tunggu 10 menit.'
            ], 429);
        }

        try {
            $this->generateAndSendOtp($user->email);
            Cache::put($rateLimitKey, $attempts + 1, now()->addMinutes(self::RESEND_DECAY_MINUTES));
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Gagal kirim OTP'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP baru dikirim'
        ]);
    }
}

