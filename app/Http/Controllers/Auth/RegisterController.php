<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request): RedirectResponse
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
                ->with('error', 'Gagal mengirim OTP ke email. Silakan coba lagi.'.$exception->getMessage());
        }

        session([
            'pending_driver_registration' => $pendingRegistration,
        ]);

        return redirect()
            ->route('register.otp.form')
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    /**
     * Tampilkan form verifikasi OTP.
     */
    public function showOtpForm(): View|RedirectResponse
    {
        if (!$this->hasPendingRegistration()) {
            return redirect()
                ->route('register')
                ->with('error', 'Silakan isi formulir registrasi terlebih dahulu.');
        }

        return view('auth.register-otp', [
            'email' => $this->pendingRegistration()['email'],
        ]);
    }

    /**
     * Verifikasi OTP registrasi lalu arahkan ke form dokumen driver.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        if (!$this->hasPendingRegistration()) {
            return redirect()
                ->route('register')
                ->with('error', 'Sesi registrasi Anda sudah habis. Silakan daftar ulang.');
        }

        $request->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus terdiri dari 6 digit.',
        ]);

        $pendingRegistration = $this->pendingRegistration();

        $otpRecord = EmailOtp::query()
            ->where('email', $pendingRegistration['email'])
            ->where('purpose', EmailOtp::PURPOSE_DRIVER_REGISTRATION)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otpRecord || $otpRecord->expired_at->isPast() || !Hash::check($request->otp, $otpRecord->otp)) {
            return back()
                ->withInput()
                ->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        if (User::where('email', $pendingRegistration['email'])->exists()) {
            $this->forgetPendingRegistration();

            return redirect()
                ->route('login')
                ->with('error', 'Email tersebut sudah terdaftar. Silakan login.');
        }

        $user = User::create([
            'name' => $pendingRegistration['name'],
            'email' => $pendingRegistration['email'],
            'nomor_telepon' => $pendingRegistration['nomor_telepon'],
            'password' => $pendingRegistration['password'],
            'role' => 'user',
        ]);

        $otpRecord->update([
            'used_at' => now(),
        ]);

        EmailOtp::where('email', $pendingRegistration['email'])
            ->where('purpose', EmailOtp::PURPOSE_DRIVER_REGISTRATION)
            ->where('id', '!=', $otpRecord->id)
            ->delete();

        $this->forgetPendingRegistration();

        Auth::login($user);
        $request->session()->regenerate();
        toast('OTP valid, silahkan isi dokumen yang diperlukan', 'success');

        return redirect()->route('driver.application.create');
    }

    /**
     * Kirim ulang OTP ke email registrasi.
     */
    public function resendOtp(): RedirectResponse
    {
        if (!$this->hasPendingRegistration()) {
            return redirect()
                ->route('register')
                ->with('error', 'Sesi registrasi Anda sudah habis. Silakan daftar ulang.');
        }

        try {
            $this->sendOtp($this->pendingRegistration()['email']);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'Gagal mengirim ulang OTP. Silakan coba lagi.');
        }

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nomor_telepon' => $data['nomor_telepon'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);
    }

    /**
     * Kirim kode OTP registrasi.
     */
    protected function sendOtp(string $email): void
    {
        EmailOtp::where('email', $email)
            ->where('purpose', EmailOtp::PURPOSE_DRIVER_REGISTRATION)
            ->delete();

        $otp = (string) random_int(100000, 999999);

        EmailOtp::create([
            'email' => $email,
            'purpose' => EmailOtp::PURPOSE_DRIVER_REGISTRATION,
            'otp' => Hash::make($otp),
            'expired_at' => now()->addMinutes(3),
        ]);

        Mail::to($email)->send(new OtpMail($otp));
    }

    /**
     * Cek apakah masih ada data registrasi sementara.
     */
    protected function hasPendingRegistration(): bool
    {
        return session()->has('pending_driver_registration');
    }

    /**
     * Ambil data registrasi sementara.
     *
     * @return array{name:string,email:string,nomor_telepon:string,password:string}
     */
    protected function pendingRegistration(): array
    {
        return session('pending_driver_registration', []);
    }

    /**
     * Hapus data registrasi sementara.
     */
    protected function forgetPendingRegistration(): void
    {
        session()->forget('pending_driver_registration');
    }
}
