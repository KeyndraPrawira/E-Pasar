<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\PasarController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\KiosController;
use App\Models\User;
use Filament\Facades\Filament;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('login', LoginController::class);





Auth::routes();
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
        Route::resource('pasar', PasarController::class);
        Route::resource('pengguna', UserController::class);
        Route::resource('kios', KiosController::class);
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

