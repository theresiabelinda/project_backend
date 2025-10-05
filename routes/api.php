<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Models\User;

// ========== ROUTE AUTENTIKASI TANPA TOKEN ==========
Route::post('/register', [AuthController::class, 'register'])
    ->withoutMiddleware(EnsureFrontendRequestsAreStateful::class);

Route::post('/login', [AuthController::class, 'login'])
    ->withoutMiddleware(EnsureFrontendRequestsAreStateful::class);

// ========== ROUTE YANG BUTUH AUTENTIKASI (SANCTUM) ==========
Route::middleware('auth:sanctum')->group(function () {

    // Profil user yang sedang login
    Route::get('/me', [AuthController::class, 'me']);

    // Logout (hapus token)
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard API — hanya bisa diakses setelah login
    Route::get('/dashboard', function () {
        return response()->json([
            'total_users' => User::count(),
            'recent_users' => User::latest()->take(5)->get(['id', 'name', 'username', 'email']),
        ], 200);
    });

    // CRUD User
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});
