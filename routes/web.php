<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\SellerController;
use App\Http\Controllers\Seller\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. HALAMAN UTAMA ---
Route::get('/', [LandingController::class, 'index'])->name('home');

// --- 2. HALAMAN USER UMUM (PRODUK, TOKO, DLL) ---
Route::controller(PageController::class)->group(function () {
    Route::get('/pages/produk', 'produk');
    Route::get('/pages/semua_toko', 'semuaToko');
    Route::get('/pages/detail_produk', 'detailProduk');
    Route::get('/pages/toko', 'detailToko');
    Route::get('/pages/keranjang', 'keranjang');
    Route::get('/pages/search', 'search');
});

// --- 3. AUTHENTICATION (Pemisahan Customer & Seller) ---
Route::controller(AuthController::class)->group(function () {
    
    // AUTH CUSTOMER
    Route::get('/login', 'showLogin')->name('login'); // Menampilkan login customer
    Route::post('/login', 'login')->name('login.process');
    Route::get('/register', 'showRegister')->name('register'); // Menampilkan register customer
    Route::post('/register', 'register')->name('register.process');

    // AUTH SELLER (Entitas Berbeda)
    Route::get('/seller/login', 'showLoginSeller')->name('seller.login'); // Menampilkan login seller
    Route::post('/seller/login', 'login')->name('seller.login.process');
    Route::get('/seller/register', 'showRegisterSeller')->name('seller.register'); // Load data provinsi
    Route::post('/seller/register', 'registerSeller')->name('seller.register.process');

    // LOGOUT GLOBAL
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/seller/logout', 'logoutSeller')->name('seller.logout');
});

// --- 4. AREA SELLER (WAJIB LOGIN & LEVEL SELLER) ---
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'index'])->name('dashboard');
    // Tambahkan route management produk/toko seller di sini
});

// --- 5. API & AJAX ---
Route::get('/api/cities/{province_id}', [AuthController::class, 'getCities']);
Route::get('/api/districts/{city_id}', [AuthController::class, 'getDistricts']);
Route::post('/api/chat', function() {
    return response()->json(['reply' => 'Halo! Saya POTA (Versi Laravel).']);
})->name('api.chat');

// --- 6. PLACEHOLDER ---
Route::get('/auth/google', function() { return "Fitur Login Google (Coming Soon)"; });
Route::get('/lupa-password', function() { return "Halaman Lupa Password (Coming Soon)"; });

Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// routes/web.php
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
Route::resource('products', App\Http\Controllers\Seller\ProductController::class);
});