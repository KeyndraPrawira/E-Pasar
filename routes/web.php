<?php

use App\Http\Controllers\Admin\KategoriController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\PasarController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KiosController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\LandingpageController;
use App\Models\User;
use Filament\Facades\Filament;

Route::get('/', [LandingpageController::class, 'index'])->name('landingpage');

Route::resource('login', LoginController::class);




Auth::routes();
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
        Route::resource('pasar', PasarController::class);
        Route::resource('pengguna', UserController::class);
        Route::resource('kios', KiosController::class)->parameters(['kios' => 'kios']);;
        Route::resource('kategori', KategoriController::class);
        Route::resource('produk', ProdukController::class);
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

