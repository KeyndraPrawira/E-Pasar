<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class DriverForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');

        ResetPasswordNotification::createUrlUsing(function ($user, string $token): string {
            return route('driver.password.reset', [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ]);
        });
    }

    /**
     * Tampilkan form lupa password driver.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.driver-passwords.email');
    }

    /**
     * Validasi email reset password driver.
     */
    protected function validateEmail(Request $request): void
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
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
            'role' => 'driver',
        ];
    }

    /**
     * Respons berhasil kirim link reset password driver.
     */
    protected function sendResetLinkResponse(Request $request, $response): RedirectResponse
    {
        return back()->with('status', 'Link reset password sudah dikirim ke email driver Anda.');
    }

    /**
     * Respons gagal kirim link reset password driver.
     */
    protected function sendResetLinkFailedResponse(Request $request, $response): RedirectResponse
    {
        $message = $response === Password::INVALID_USER
            ? 'Email driver tidak ditemukan.'
            : __($response);

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $message]);
    }
}
