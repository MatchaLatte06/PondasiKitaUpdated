<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PageController;
<<<<<<< HEAD
use App\Http\Controllers\AuthController; // Wajib di-import
=======
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\SellerController;
use App\Http\Controllers\Seller\DashboardController;
>>>>>>> a08b632c2aa9fe6bcb487ef64029fa6676633682

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

<<<<<<< HEAD
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
=======
// --- 1. HALAMAN UTAMA ---
Route::get('/', [LandingController::class, 'index'])->name('home');

// --- 2. HALAMAN USER UMUM (PRODUK, TOKO, DLL) ---
Route::controller(PageController::class)->group(function () {
    Route::get('/pages/produk', 'produk');
    Route::get('/pages/semua_toko', 'semuaToko');
    Route::get('/pages/detail_produk', 'detailProduk');
    Route::get('/pages/toko', 'detailToko');
    Route::get('/pages/keranjang', 'keranjang');
>>>>>>> a08b632c2aa9fe6bcb487ef64029fa6676633682
    Route::get('/pages/search', 'search');
});

// --- 3. AUTHENTICATION (Pemisahan Customer & Seller) ---
Route::controller(AuthController::class)->group(function () {
    
    // AUTH CUSTOMER
    Route::get('/login', 'showLogin')->name('login'); // Menampilkan login customer
    Route::post('/login', 'login')->name('login.process');
    Route::get('/register', 'showRegister')->name('register'); // Menampilkan register customer
    Route::post('/register', 'register')->name('register.process');

<<<<<<< HEAD
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
=======
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
>>>>>>> a08b632c2aa9fe6bcb487ef64029fa6676633682

// --- 6. PLACEHOLDER ---
Route::get('/auth/google', function() { return "Fitur Login Google (Coming Soon)"; });
Route::get('/lupa-password', function() { return "Halaman Lupa Password (Coming Soon)"; });

<<<<<<< HEAD
// =================================================================
// 4. ROUTE LAINNYA (CHATBOT & SOCIALITE)
// =================================================================

// Route Chatbot POTA (Agar tidak 404 di Landing Page)
// Nanti ganti dengan ChatController jika sudah siap
Route::post('/api/chat', function() {
    return response()->json([
        'reply' => 'Halo! Saya POTA. Sistem otak saya sedang diperbarui, silakan coba lagi nanti ya!'
    ]);
})->name('api.chat');

// Placeholder untuk Login Google & Lupa Password
Route::get('/auth/google', function() { return "Fitur Login Google (Coming Soon)"; });
Route::get('/lupa-password', function() { return "Halaman Lupa Password (Coming Soon)"; });
=======
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
>>>>>>> a08b632c2aa9fe6bcb487ef64029fa6676633682
