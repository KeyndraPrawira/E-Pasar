<?php

use App\Http\Controllers\Api\AuthController;
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
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\LaporanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/google-login', [ApiAuthController::class, 'googleLogin']);

Route::get('/kategori', [KategoriController::class, 'index']);
Route::apiResource('/produk', ApiProdukController::class)->only(['index', 'show']);
Route::get('/pasar', [PasarController::class, 'index']);


Route::middleware('auth:sanctum', 'role:driver')->group(function(){
    Route::post('/set-active', [UserController::class, 'setActive']);
    Route::get('/orders/available', [OrderController::class, 'index']);      // ← spesifik dulu
    Route::post('/orders/{order}/accept', [OrderController::class, 'acceptOrder']);
    Route::post('/orders/{id}/send', [OrderController::class, 'sendDelivery']); 
    Route::post('/orders/{id}/complete', [OrderController::class, 'completeOrder']); 
    Route::patch('/order-item/{id}', [OrderController::class, 'updateItemStatus']);
    Route::patch('/order-item/{id}/request-ganti', [OrderController::class, 'requestGantiItem']);
    Route::patch('/order-item/{id}/pilih-pengganti/{produk}', [OrderController::class, 'pilihPengganti']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::post('/complete-profile', [AuthController::class, 'completeProfile']);
    Route::get('/profile/me', [ProfileController::class, 'show']);
    Route::put('/profile/me', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
        Route::get('/orders/active', [OrderController::class, 'indexActiveOrders']);

    Route::get('/orders/{id}', [OrderController::class, 'show']);
    


});


Route::middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::apiResource('user', UserController::class);
    Route::apiResource('laporans', LaporanController::class);
});



Route::middleware('auth:sanctum', 'role:user')->group(function () {
    Route::get('/kios', [KiosController::class, 'index']);
    Route::post('/keranjang/{produkId}', [KeranjangController::class, 'store']);
    Route::apiResource('/keranjang', KeranjangController::class);
    Route::post('/profile/alamat', [ProfileController::class, 'setAlamat']);
    Route::post('/orders/checkout', [OrderController::class, 'store']);
    Route::get('/orders/my', [OrderController::class, 'myOrders']);
    Route::get('/orders/history', [OrderController::class, 'orderHistory']);
    Route::get('/orders/history/{id}', [OrderController::class, 'detailOrderHistory']);
       Route::apiResource('laporans', LaporanController::class)->only(['index', 'store']);
});

Route::middleware('auth:sanctum', 'role:pedagang')->group(function(){
    Route::get('/kios/me', [KiosController::class, 'myKios']);
    Route::apiResource('/kios', KiosController::class)->except(['create', 'edit', 'show'])->parameters([
        'kios' => 'kios'
    ]);
    Route::get('/produk/{produk}', [ApiProdukController::class, 'myProduk']);
    Route::apiResource('/produk', ApiProdukController::class)->except(['edit', 'show', 'create', 'index']);
});

// Route::post('/send-otp', [ApiAuthController::class, 'sendOtp']);
// Route::post('/verify-otp', [ApiAuthController::class, 'verifyOtp']);

Route::get('/ping', function () {
    return response()->json(['msg' => 'api hidup v12']);
});

