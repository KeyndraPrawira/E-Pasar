<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Tampilkan halaman login gabungan admin dan driver.
     */
    public function showLoginForm(): View
    {
        return view('auth.login', [
            'loginAs' => request('as', 'admin'),
        ]);
    }

    /**
     * Tangani login admin dan driver dari halaman yang sama.
     */
    public function login(Request $request)
    {
        $request->validate([
            $this->username() => ['required', 'email'],
            'password' => ['required', 'string'],
            'login_as' => ['required', 'in:admin,driver'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'login_as.required' => 'Pilih jenis akun terlebih dahulu.',
            'login_as.in' => 'Jenis akun tidak valid.',
        ]);

        $credentials = [
            $this->username() => $request->input($this->username()),
            'password' => $request->input('password'),
            'role' => $request->input('login_as'),
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            $this->sendFailedLoginResponse($request);
        }

        $request->session()->regenerate();

        return redirect()->intended($this->redirectPathForRole($request->input('login_as')));
    }

    /**
     * Tentukan tujuan setelah login berhasil.
     */
    protected function redirectPathForRole(string $role): string
    {
        if ($role === 'driver') {
            $user = Auth::user()?->loadMissing('driver');

            if ($user === null) {
                return route('landingpage');
            }

            return $user->driver === null
                ? route('driver.application.create')
                : route('driver.application.status');
        }

        return route('dashboard');
    }

    /**
     * Pesan gagal login sesuai role yang dipilih.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $role = $request->input('login_as') === 'driver' ? 'driver' : 'admin';
        $message = $role === 'driver'
            ? 'Email atau password salah, atau akun ini belum terdaftar sebagai driver.'
            : 'Email atau password admin salah.';

        throw ValidationException::withMessages([
            $this->username() => [$message],
        ]);
    }
}
