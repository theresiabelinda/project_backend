<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



// --- ROUTE AUTENTIKASI (TIDAK MEMERLUKAN TOKEN) ---
// Ini mengatasi masalah 404 pada test register/login.
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// --- ROUTE YANG MEMBUTUHKAN AUTENTIKASI SANCTUM ---
Route::middleware('auth:sanctum')->group(function () {

    // Route CRUD User yang memerlukan otentikasi
    // Ganti 'UserController' dengan nama controller Anda yang sebenarnya
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    // Tambahkan route API lainnya di sini jika ada
});
