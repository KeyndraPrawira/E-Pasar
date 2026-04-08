<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DriverLoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Tampilkan halaman login driver.
     */
    public function showLoginForm(): View
    {
        return view('auth.driver-login');
    }

    /**
     * Validasi form login driver.
     */
    protected function validateLogin(Request $request): void
    {
        $request->validate([
            $this->username() => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);
    }

    /**
     * Batasi login hanya untuk akun driver.
     *
     * @return array<string, string>
     */
    protected function credentials(Request $request): array
    {
        return [
            $this->username() => $request->input($this->username()),
            'password' => $request->input('password'),
            'role' => 'driver',
        ];
    }

    /**
     * Tentukan tujuan setelah login driver berhasil.
     */
    protected function redirectTo(): string
    {
        $user = auth()->user()?->loadMissing('driver');

        if ($user === null) {
            return route('landingpage');
        }

        return $user->driver === null
            ? route('driver.application.create')
            : route('driver.application.status');
    }

    /**
     * Pesan gagal login khusus driver.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['Email atau password salah, atau akun ini belum terdaftar sebagai driver.'],
        ]);
    }
}
