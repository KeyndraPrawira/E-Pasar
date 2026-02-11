<?php

use App\Http\Controllers\Api\ApiProdukController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\KiosController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\KeranjangController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);

Route::middleware('auth:sanctum', 'role:user')->group(function () {
    Route::resource('/user', UserController::class);
    Route::get('/produk', [ApiProdukController::class, 'index']);
    Route::get('/kios', [KiosController::class, 'index']);
    Route::resource('/keranjang',KeranjangController::class);
   
});

Route::middleware('auth:sanctum', 'role:pedagang')->group(function(){
    Route::resource('/kios',KiosController::class)->except('create', 'edit', 'show');
    Route::resource('/produk', ApiProdukController::class);
}
);

// Route::post('/send-otp', [ApiAuthController::class, 'sendOtp']);
// Route::post('/verify-otp', [ApiAuthController::class, 'verifyOtp']);

Route::get('/ping', function () {
    return response()->json(['msg' => 'api hidup v12']);
});



