<?php

use App\Http\Controllers\Api\ApiProdukController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\KiosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/user', UserController::class);
    Route::resource('/produk', ApiProdukController::class);
    Route::resource('/api-kios', controller: KiosController::class);
});

Route::get('/ping', function () {
    return response()->json(['msg' => 'api hidup v12']);
});

