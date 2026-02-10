<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Panggil Controller tadi

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// === ROUTE PUBLIK (Bisa diakses tanpa login) ===
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// === ROUTE PRIVATE (Harus login & punya Token) ===
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Nanti tambah route lain di sini, misal:
    // Route::get('/pesanan', [PesananController::class, 'index']);
});

?>