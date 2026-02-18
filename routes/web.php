<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController; // Wajib di-import

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =================================================================
// 1. HALAMAN UTAMA (LANDING PAGE)
// =================================================================
Route::get('/', [LandingController::class, 'index'])->name('home');


// =================================================================
// 2. HALAMAN USER (PRODUK, TOKO, KERANJANG)
// =================================================================
Route::controller(PageController::class)->group(function () {
    
    // Halaman List Produk
    Route::get('/pages/produk', 'produk');

    // Halaman List Semua Toko
    Route::get('/pages/semua_toko', 'semuaToko');

    // Halaman Detail Produk (Link: /pages/detail_produk?id=1)
    Route::get('/pages/detail_produk', 'detailProduk');

    // Halaman Profil Toko (Link: /pages/toko?slug=nama-toko)
    Route::get('/pages/toko', 'detailToko');

    // Halaman Keranjang Belanja
    Route::get('/pages/keranjang', 'keranjang');

    // Halaman Pencarian
    Route::get('/pages/search', 'search');
});

// --- 3. AUTHENTICATION (Pemisahan Customer & Seller) ---
Route::controller(AuthController::class)->group(function () {
    
    // AUTH CUSTOMER
    Route::get('/login', 'showLogin')->name('login'); // Menampilkan login customer
    Route::post('/login', 'login')->name('login.process');
    Route::get('/register', 'showRegister')->name('register'); // Menampilkan register customer
    Route::post('/register', 'register')->name('register.process');

// =================================================================
// 3. AUTHENTICATION (LOGIN / REGISTER / LOGOUT)
// =================================================================
Route::controller(AuthController::class)->group(function () {
    
    // --- LOGIN ---
    // Menampilkan Form Login
    Route::get('/login', 'showLogin')->name('login');
    // Memproses Data Login (POST)
    Route::post('/login', 'login')->name('login.process');

    // --- REGISTER ---
    // Menampilkan Form Register (Pastikan view 'auth.register_customer' ada)
    // Jika method showRegister belum ada di controller, pakai Route::view
    Route::view('/register', 'auth.register_customer')->name('register'); 
    // Memproses Data Register (POST)
    Route::post('/register', 'register')->name('register.process');

    // --- LOGOUT ---
    // Logout wajib POST demi keamanan
    Route::post('/logout', 'logout')->name('logout');

});

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