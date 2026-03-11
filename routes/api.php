<?php

use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\ApiProdukController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\KiosController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\KeranjangController;
use App\Http\Controllers\Api\PasarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);
Route::get('/kategori', [KategoriController::class, 'index']);
       Route::apiResource('/produk', ApiProdukController::class)->only(['index', 'show']);
       Route::get('/pasar', [PasarController::class, 'index']);


    Route::middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::apiResource('/user', UserController::class);
    });

Route::middleware('auth:sanctum', 'role:user')->group(function () {
    Route::get('/kios', [KiosController::class, 'index']);
    Route::post('/keranjang/{produkId}', [KeranjangController::class, 'store']);
    Route::apiResource('/keranjang', KeranjangController::class);

});

Route::middleware('auth:sanctum', 'role:pedagang')->group(function(){
    Route::get('kios/me', [KiosController::class, 'myKios']);
    Route::apiResource('/kios', KiosController::class)->except('create', 'edit', 'show')->parameters([
    'kios' => 'kios'
    ]);
    Route::get('/produk/{produk}', [ApiProdukController::class, 'myProduk']);
    Route::apiResource('/produk', ApiProdukController::class)->except('edit', 'show', 'create', 'index');
}    
);

// Route::post('/send-otp', [ApiAuthController::class, 'sendOtp']);
// Route::post('/verify-otp', [ApiAuthController::class, 'verifyOtp']);

Route::get('/ping', function () {
    return response()->json(['msg' => 'api hidup v12']);
});



