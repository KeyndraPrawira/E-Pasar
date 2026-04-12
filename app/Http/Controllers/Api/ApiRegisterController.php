<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Batas maksimal percobaan resend OTP per email (dalam window waktu tertentu).
     */
    private const RESEND_MAX_ATTEMPTS = 3;

    /**
     * Durasi window rate limit resend OTP (dalam menit).
     */
    private const RESEND_DECAY_MINUTES = 10;

    // =========================================================================
    // Step 1 — Registrasi: validasi input, simpan ke session, kirim OTP
    // =========================================================================

    /**
     * Terima data registrasi, simpan sementara di cache, lalu kirim OTP ke email.
     *
     * POST /api/register
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'nomor_telepon'         => ['required', 'string', 'max:20'],
            'password'              => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'name.required'              => 'Nama wajib diisi.',
            'email.required'             => 'Email wajib diisi.',
            'email.email'                => 'Format email tidak valid.',
            'email.unique'               => 'Email sudah terdaftar.',
            'nomor_telepon.required'     => 'Nomor telepon wajib diisi.',
            'password.required'          => 'Password wajib diisi.',
            'password.confirmed'         => 'Konfirmasi password tidak cocok.',
            'password.min'               => 'Password minimal 8 karakter.',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors()->toArray());
        }

        $pendingRegistration = [
            'name'           => $request->string('name')->toString(),
            'email'          => $request->string('email')->toString(),
            'nomor_telepon'  => $request->string('nomor_telepon')->toString(),
            'password'       => Hash::make($request->string('password')->toString()),
        ];

        try {
            $this->sendOtp($pendingRegistration['email']);
        } catch (\Throwable $e) {
            report($e);

            return $this->error('Gagal mengirim OTP ke email. Silakan coba lagi.', 500);
        }

        // Simpan data registrasi sementara di cache (key unik per email, TTL 10 menit)
        $cacheKey = $this->pendingCacheKey($pendingRegistration['email']);
        Cache::put($cacheKey, $pendingRegistration, now()->addMinutes(10));

        return $this->success(
            'Kode OTP telah dikirim ke email Anda. Berlaku selama 3 menit.',
            ['email' => $pendingRegistration['email']]
        );
    }

    // =========================================================================
    // Step 2 — Verifikasi OTP: cek OTP, buat user, return Sanctum token
    // =========================================================================

    /**
     * Verifikasi OTP, buat akun user, dan kembalikan Sanctum token.
     *
     * POST /api/register/verify-otp
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'otp'   => ['required', 'digits:6'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'otp.required'   => 'Kode OTP wajib diisi.',
            'otp.digits'     => 'Kode OTP harus terdiri dari 6 digit angka.',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors()->toArray());
        }

        $email    = $request->string('email')->toString();
        $cacheKey = $this->pendingCacheKey($email);

        // Pastikan data registrasi sementara masih ada
        if (! Cache::has($cacheKey)) {
            return $this->error(
                'Sesi registrasi Anda sudah habis atau tidak ditemukan. Silakan daftar ulang.',
                422
            );
        }

        $pendingRegistration = Cache::get($cacheKey);

        // Ambil OTP record yang valid (belum dipakai, purpose sesuai, terbaru)
        $otpRecord = EmailOtp::query()
            ->where('email', $email)
            ->where('purpose', EmailOtp::PURPOSE_USER_REGISTRATION)
            ->whereNull('used_at')
            ->latest()
            ->first();

        // Validasi keberadaan, masa berlaku, dan kecocokan OTP
        if (
            ! $otpRecord ||
            $otpRecord->expired_at->isPast() ||
            ! Hash::check($request->string('otp')->toString(), $otpRecord->otp)
        ) {
            return $this->error('Kode OTP tidak valid atau sudah kedaluwarsa.', 422);
        }

        // Race condition guard: cek ulang apakah email sudah terdaftar
        if (User::where('email', $email)->exists()) {
            Cache::forget($cacheKey);

            return $this->error('Email tersebut sudah terdaftar. Silakan login.', 409);
        }

        // Buat akun user baru
        $user = User::create([
            'name'           => $pendingRegistration['name'],
            'email'          => $pendingRegistration['email'],
            'nomor_telepon'  => $pendingRegistration['nomor_telepon'],
            'password'       => $pendingRegistration['password'],
            'role'           => 'user',
        ]);

        // Tandai OTP sebagai sudah dipakai
        $otpRecord->update(['used_at' => now()]);

        // Hapus OTP lain yang tersisa untuk email & purpose yang sama
        EmailOtp::where('email', $email)
            ->where('purpose', EmailOtp::PURPOSE_USER_REGISTRATION)
            ->where('id', '!=', $otpRecord->id)
            ->delete();

        // Bersihkan data registrasi sementara dari cache
        Cache::forget($cacheKey);

        // Bersihkan rate limit resend OTP
        Cache::forget($this->resendRateLimitKey($email));

        // Buat Sanctum token
        $token = $user->createToken('user-auth-token')->plainTextToken;

        return $this->success('Registrasi berhasil. Selamat datang!', [
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => [
                'id'             => $user->id,
                'name'           => $user->name,
                'email'          => $user->email,
                'nomor_telepon'  => $user->nomor_telepon,
                'role'           => $user->role,
            ],
        ], 201);
    }

    // =========================================================================
    // Step 3 — Resend OTP (dengan rate limiting)
    // =========================================================================

    /**
     * Kirim ulang OTP ke email, dengan rate limiting 3x per 10 menit.
     *
     * POST /api/register/resend-otp
     */
    public function resendOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors()->toArray());
        }

        $email    = $request->string('email')->toString();
        $cacheKey = $this->pendingCacheKey($email);

        // Pastikan sesi registrasi masih aktif
        if (! Cache::has($cacheKey)) {
            return $this->error(
                'Sesi registrasi Anda sudah habis atau tidak ditemukan. Silakan daftar ulang.',
                422
            );
        }

        // Cek rate limit resend OTP
        $rateLimitKey = $this->resendRateLimitKey($email);
        $attempts     = (int) Cache::get($rateLimitKey, 0);

        if ($attempts >= self::RESEND_MAX_ATTEMPTS) {
            return $this->error(
                sprintf(
                    'Terlalu banyak permintaan OTP. Silakan tunggu %d menit sebelum mencoba lagi.',
                    self::RESEND_DECAY_MINUTES
                ),
                429
            );
        }

        try {
            $this->sendOtp($email);
        } catch (\Throwable $e) {
            report($e);

            return $this->error('Gagal mengirim ulang OTP. Silakan coba lagi.', 500);
        }

        // Tambah hitungan percobaan resend
        Cache::put($rateLimitKey, $attempts + 1, now()->addMinutes(self::RESEND_DECAY_MINUTES));

        return $this->success('Kode OTP baru telah dikirim ke email Anda. Berlaku selama 3 menit.');
    }

    // =========================================================================
    // Helper — OTP
    // =========================================================================

    /**
     * Generate OTP baru, simpan ke DB (hash), lalu kirim via email.
     */
    protected function sendOtp(string $email): void
    {
        // Hapus semua OTP lama yang belum dipakai untuk email ini
        EmailOtp::where('email', $email)
            ->where('purpose', EmailOtp::PURPOSE_USER_REGISTRATION)
            ->whereNull('used_at')
            ->delete();

        $otp = (string) random_int(100000, 999999);

        EmailOtp::create([
            'email'      => $email,
            'purpose'    => EmailOtp::PURPOSE_USER_REGISTRATION,
            'otp'        => Hash::make($otp),
            'expired_at' => now()->addMinutes(3),
        ]);

        Mail::to($email)->send(new OtpMail($otp));
    }


    // =========================================================================
    // Step 4 — Cancel: hapus data sementara, OTP, dan rate limit
    // =========================================================================

    /**
     * Batalkan registrasi — hapus cache pending, OTP record, dan rate limit.
     * Dipanggil saat user menekan tombol "Batal" di halaman OTP.
     *
     * DELETE /api/register/cancel
     */
    public function cancelPendingRegistration(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors()->toArray());
        }

        $email = $request->string('email')->toString();

        // Kalau tidak ada sesi aktif, tetap return success
        // agar Flutter tidak perlu handle kasus ini secara khusus
        if (! Cache::has($this->pendingCacheKey($email))) {
            return $this->success('Tidak ada sesi registrasi aktif.');
        }

        // Hapus cache data registrasi sementara
        Cache::forget($this->pendingCacheKey($email));

        // Hapus semua OTP yang belum dipakai untuk email ini
        EmailOtp::where('email', $email)
            ->where('purpose', EmailOtp::PURPOSE_USER_REGISTRATION)
            ->whereNull('used_at')
            ->delete();

        // Hapus rate limit resend OTP
        Cache::forget($this->resendRateLimitKey($email));

        return $this->success('Registrasi berhasil dibatalkan.');
    }

    // =========================================================================
    // Helper — Cache keys
    // =========================================================================

    /**
     * Cache key untuk data registrasi sementara.
     */
    private function pendingCacheKey(string $email): string
    {
        return 'pending_user_registration:' . sha1($email);
    }

    /**
     * Cache key untuk rate limiting resend OTP.
     */
    private function resendRateLimitKey(string $email): string
    {
        return 'otp_resend_attempts:user_registration:' . sha1($email);
    }

    // =========================================================================
    // Helper — JSON Response
    // =========================================================================

    private function success(string $message, array $data = [], int $status = 200): JsonResponse
    {
        $body = ['success' => true, 'message' => $message];

        if (! empty($data)) {
            $body['data'] = $data;
        }

        return response()->json($body, $status);
    }

    private function error(string $message, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    private function validationError(array $errors): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Data yang diberikan tidak valid.',
            'errors'  => $errors,
        ], 422);
    }
}