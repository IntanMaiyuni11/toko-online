<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\API\VoucherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Menggunakan Sanctum untuk mendapatkan data user yang sedang login
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rute untuk pengecekan email (Gunakan Controller yang kita buat tadi)
Route::get('register/check', [RegisteredUserController::class, 'check'])
    ->name('api-register-check');

// Rute untuk Lokasi (Provinsi & Kota)
Route::get('provinces', [LocationController::class, 'provinces'])
    ->name('api-provinces');

Route::get('regencies/{provinces_id}', [LocationController::class, 'regencies'])
    ->name('api-regencies');

// Rute untuk masuk ke dalam Dasboard Midtrans 
Route::post('/checkout/callback', [CheckoutController::class, 'callback'])
    ->name('midtrans-callback');

// Route untuk cek voucher via AJAX di halaman Cart
Route::get('check-voucher', [VoucherController::class, 'check']);