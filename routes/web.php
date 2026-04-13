<?php

use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\DriverWithdrawalController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Auth\DriverForgotPasswordController;
use App\Http\Controllers\Auth\DriverLoginController;
use App\Http\Controllers\Auth\DriverResetPasswordController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\PasarController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KiosController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\DriverApplicationController;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\Pedagang\DashboardController as PedagangDashboardController;
use App\Http\Controllers\Pedagang\ProdukController as PedagangProdukController;
use App\Http\Controllers\Admin\KategoriLaporanController;
use App\Http\Controllers\Admin\PedagangController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Filament\Facades\Filament;

Route::get('/', [LandingpageController::class, 'index'])->name('landingpage');
Route::get('/detail-produk/{id}', [LandingpageController::class, 'productDetail'])->name('detail-produk');
Route::resource('login', LoginController::class);




Auth::routes();
Route::middleware('guest')->group(function () {
        Route::get('/login-driver', [DriverLoginController::class, 'showLoginForm'])->name('driver.login');
        Route::post('/login-driver', [DriverLoginController::class, 'login'])->name('driver.login.submit');
        Route::get('/password-driver/reset', [DriverForgotPasswordController::class, 'showLinkRequestForm'])->name('driver.password.request');
        Route::post('/password-driver/email', [DriverForgotPasswordController::class, 'sendResetLinkEmail'])->name('driver.password.email');
        Route::get('/password-driver/reset/{token}', [DriverResetPasswordController::class, 'showResetForm'])->name('driver.password.reset');
        Route::post('/password-driver/reset', [DriverResetPasswordController::class, 'reset'])->name('driver.password.update');
        Route::get('/register/otp', [RegisterController::class, 'showOtpForm'])->name('register.otp.form');
        Route::post('/register/otp', [RegisterController::class, 'verifyOtp'])->name('register.otp.verify');
        Route::post('/register/otp/resend', [RegisterController::class, 'resendOtp'])->name('register.otp.resend');
});

Route::middleware('auth')->group(function () {
        Route::get('/driver/daftar', [DriverApplicationController::class, 'create'])->name('driver.application.create');
        Route::post('/driver/daftar', [DriverApplicationController::class, 'store'])->name('driver.application.store');
        Route::get('/driver/status', [DriverApplicationController::class, 'status'])->name('driver.application.status');
});

Route::prefix('admin')->middleware('admin:admin')->group(function () {
        Route::resource('pasar', PasarController::class);
        Route::resource('pelanggan', UserController::class);
        Route::get('/kios/pdf', [KiosController::class, 'downloadPdf'])->name('kios.pdf');
        Route::resource('kios', KiosController::class)->parameters(['kios' => 'kios']);
                 Route::get('/produks/pdf', [ProdukController::class, 'downloadPdf'])->name('produks.pdf');
        Route::resource('produks', ProdukController::class);
        Route::resource('kategori', KategoriController::class);
        Route::resource('kategori-laporan', KategoriLaporanController::class);
       Route::resource('pedagang', PedagangController::class)->parameters(['pedagang' => 'pedagang']);
       Route::resource('driver', DriverController::class)->only(['index', 'show'])->parameters(['driver' => 'driver']);
       Route::patch('driver/{driver}/verify', [DriverController::class, 'verify'])->name('driver.verify');
       Route::get('driver-withdrawals', [DriverWithdrawalController::class, 'index'])->name('driver-withdrawals.index');
       Route::get('driver-withdrawals/{driverWithdrawal}', [DriverWithdrawalController::class, 'show'])->name('driver-withdrawals.show');
       Route::patch('driver-withdrawals/{driverWithdrawal}', [DriverWithdrawalController::class, 'process'])->name('driver-withdrawals.process');
       Route::resource('transaksi', TransaksiController::class)->only(['index', 'show']);
        

        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('role:pedagang')->group(function () {
    Route::get('/dashboard', [PedagangDashboardController::class, 'index'])->name('pedagang.dashboard');
    Route::resource('produk', PedagangProdukController::class);
});
