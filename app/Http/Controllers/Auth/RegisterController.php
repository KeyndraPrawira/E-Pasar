<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    private const RESEND_MAX_ATTEMPTS = 3;
    private const RESEND_DECAY_MINUTES = 10;

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function showOtpForm(Request $request)
    {
        $email = (string) $request->old('email', session('otp_email', ''));

        if ($email === '') {
            return redirect()->route('register')
                ->with('error', 'Sesi registrasi tidak ditemukan. Silakan daftar ulang.');
        }

        return view('auth.register-otp', compact('email'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pendingRegistration = [
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'nomor_telepon' => $request->string('nomor_telepon')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
        ];

        try {
            $this->sendOtp($pendingRegistration['email']);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Gagal mengirim OTP ke email. Silakan coba lagi.');
        }

        $email = $pendingRegistration['email'];
        $cacheKey = $this->pendingCacheKey($email);
        Cache::put($cacheKey, $pendingRegistration, now()->addMinutes(10));

        session(['otp_email' => $email]);

        return redirect()->route('register.otp.form')
            ->with('success', 'Kode OTP telah dikirim ke email Anda. Masukkan kode 6 digit.')
            ->with('email', $email);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'otp' => ['required', 'digits:6'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus terdiri dari 6 digit angka.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->string('email')->toString();
        $cacheKey = $this->pendingCacheKey($email);

        if (! Cache::has($cacheKey)) {
            return redirect()->route('register')
                ->with('error', 'Sesi registrasi Anda sudah habis atau tidak ditemukan. Silakan daftar ulang.');
        }

        $pendingRegistration = Cache::get($cacheKey);

        $otpRecord = EmailOtp::query()
            ->where('email', $email)
            ->where('purpose', EmailOtp::PURPOSE_USER_REGISTRATION)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (
            ! $otpRecord ||
            $otpRecord->expired_at->isPast() ||
            ! Hash::check($request->string('otp')->toString(), $otpRecord->otp)
        ) {
            return redirect()->route('register.otp.form')
                ->with('error', 'Kode OTP tidak valid atau sudah kedaluwarsa.')
                ->with('email', $email);
        }

        if (User::where('email', $email)->exists()) {
            Cache::forget($cacheKey);

            return redirect()->route('driver.login')
                ->with('error', 'Email tersebut sudah terdaftar. Silakan login.');
        }

        $user = User::create([
            'name' => $pendingRegistration['name'],
            'email' => $pendingRegistration['email'],
            'nomor_telepon' => $pendingRegistration['nomor_telepon'],
            'password' => $pendingRegistration['password'],
            'role' => 'driver',
        ]);

        $otpRecord->update(['used_at' => now()]);

        EmailOtp::where('email', $email)
            ->where('purpose', EmailOtp::PURPOSE_USER_REGISTRATION)
            ->where('id', '!=', $otpRecord->id)
            ->delete();

        Cache::forget($cacheKey);
        Cache::forget($this->resendRateLimitKey($email));
        session()->forget('otp_email');

        Auth::login($user);

        return redirect()->route('driver.application.create')
            ->with('success', 'OTP valid, silahkan isi dokumen yang diperlukan.');
    }

    public function resendOtp(Request $request)
    {
        $emailInput = $request->input('email', session('otp_email'));
        $validator = Validator::make(['email' => $emailInput], [
            'email' => ['required', 'string', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register.otp.form')
                ->withErrors($validator)
                ->withInput();
        }

        $email = (string) $emailInput;
        $cacheKey = $this->pendingCacheKey($email);

        if (! Cache::has($cacheKey)) {
            return redirect()->route('register')
                ->with('error', 'Sesi registrasi Anda sudah habis atau tidak ditemukan. Silakan daftar ulang.');
        }

        $rateLimitKey = $this->resendRateLimitKey($email);
        $attempts = (int) Cache::get($rateLimitKey, 0);

        if ($attempts >= self::RESEND_MAX_ATTEMPTS) {
            return redirect()->route('register.otp.form')
                ->with('error', sprintf(
                    'Terlalu banyak permintaan OTP. Silakan tunggu %d menit sebelum mencoba lagi.',
                    self::RESEND_DECAY_MINUTES
                ))
                ->with('email', $email);
        }

        try {
            $this->sendOtp($email);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('register.otp.form')
                ->with('error', 'Gagal mengirim ulang OTP. Silakan coba lagi.')
                ->with('email', $email);
        }

        Cache::put($rateLimitKey, $attempts + 1, now()->addMinutes(self::RESEND_DECAY_MINUTES));

        return redirect()->route('register.otp.form')
            ->with('success', 'Kode OTP baru telah dikirim ke email Anda. Berlaku selama 3 menit.')
            ->with('email', $email);
    }

    protected function sendOtp(string $email): void
    {
        EmailOtp::where('email', $email)
            ->where('purpose', EmailOtp::PURPOSE_USER_REGISTRATION)
            ->whereNull('used_at')
            ->delete();

        $otp = (string) random_int(100000, 999999);

        EmailOtp::create([
            'email' => $email,
            'purpose' => EmailOtp::PURPOSE_USER_REGISTRATION,
            'otp' => Hash::make($otp),
            'expired_at' => now()->addMinutes(3),
        ]);

        Mail::to($email)->send(new OtpMail($otp));
    }

    private function pendingCacheKey(string $email): string
    {
        return 'pending_user_registration:' . sha1($email);
    }

    private function resendRateLimitKey(string $email): string
    {
        return 'otp_resend_attempts:user_registration:' . sha1($email);
    }
}
