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
use App\Http\Controllers\Pedagang\DashboardController as PedagangDashboardController;
use App\Http\Controllers\Pedagang\ProdukController as PedagangProdukController;
use App\Http\Controllers\Admin\KategoriLaporanController;
use App\Models\User;
use Filament\Facades\Filament;

Route::get('/', [LandingpageController::class, 'index'])->name('landingpage');
Route::get('/detail-produk/{id}', [LandingpageController::class, 'productDetail'])->name('detail-produk');
Route::resource('login', LoginController::class);




Auth::routes();
Route::prefix('admin')->middleware('admin:admin')->group(function () {
        Route::resource('pasar', PasarController::class);
        Route::resource('pengguna', UserController::class);
        Route::get('/kios/pdf', [KiosController::class, 'downloadPdf'])->name('kios.pdf');
        Route::resource('kios', KiosController::class)->parameters(['kios' => 'kios']);
                 Route::get('/produks/pdf', [ProdukController::class, 'downloadPdf'])->name('produks.pdf');
        Route::resource('produks', ProdukController::class);
        Route::resource('kategori', KategoriController::class);
        Route::resource('kategori-laporan', KategoriLaporanController::class);
       
        

        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('role:pedagang')->group(function () {
    Route::get('/dashboard', [PedagangDashboardController::class, 'index'])->name('pedagang.dashboard');
    Route::resource('produk', PedagangProdukController::class);
});

