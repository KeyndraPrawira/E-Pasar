<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Tampilkan form reset password driver.
     */
    public function showResetForm(Request $request, ?string $token = null): View
    {
        return view('auth.driver-passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Batasi reset password hanya untuk akun driver.
     *
     * @return array<string, string>
     */
    protected function credentials(Request $request): array
    {
        return [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
            'token' => $request->input('token'),
            'role' => 'driver',
        ];
    }

    /**
     * Arahkan driver ke halaman berikutnya setelah reset password.
     */
    protected function redirectTo(): string
    {
        $user = auth()->user()?->loadMissing('driver');

        if ($user === null) {
            return route('driver.login');
        }

        return $user->driver === null
            ? route('driver.application.create')
            : route('driver.application.status');
    }
}
