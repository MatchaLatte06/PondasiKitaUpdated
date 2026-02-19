<?php

use Illuminate\Support\Facades\Route;

// --- IMPORT CONTROLLER UTAMA ---
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;

// --- IMPORT CONTROLLER SELLER ---
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController;
// Note: Controller lain (Order, Finance, dll) belum dibuat, jadi kita pakai placeholder dulu.

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
// 2. HALAMAN USER UMUM (PRODUK, TOKO, KERANJANG)
// =================================================================
Route::controller(PageController::class)->group(function () {
    Route::get('/pages/produk', 'produk')->name('produk.index');
    Route::get('/pages/semua_toko', 'semuaToko')->name('toko.index');
    Route::get('/pages/detail_produk', 'detailProduk')->name('produk.detail');
    Route::get('/pages/toko', 'detailToko')->name('toko.detail');
    Route::get('/pages/keranjang', 'keranjang')->name('keranjang.index');
    Route::get('/pages/search', 'search')->name('search');
    
    // Route sementara untuk tambah keranjang
    Route::post('/keranjang/tambah', 'tambahKeranjang')->name('keranjang.tambah');
});


// =================================================================
// 3. AUTHENTICATION (CUSTOMER & SELLER)
// =================================================================
Route::controller(AuthController::class)->group(function () {
    
    // --- CUSTOMER ---
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.process');
    Route::view('/register', 'auth.register_customer')->name('register'); 
    Route::post('/register', 'register')->name('register.process');

    // --- SELLER ---
    Route::get('/seller/login', 'showLoginSeller')->name('seller.login');
    Route::post('/seller/login', 'loginSeller')->name('seller.login.process');
    Route::get('/seller/register', 'showRegisterSeller')->name('seller.register');
    Route::post('/seller/register', 'registerSeller')->name('seller.register.process');

    // --- LOGOUT ---
    Route::post('/logout', 'logout')->name('logout');
});


// =================================================================
// 4. AREA SELLER (DASHBOARD, PRODUK, & SIDEBAR MENU)
// =================================================================
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    
    // A. DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // B. MANAJEMEN PRODUK
    Route::resource('products', ProductController::class);
    // Route Khusus AJAX Toggle Status
    Route::post('/products/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle');

    // --- ROUTE PLACEHOLDER UNTUK SIDEBAR (Agar tidak error Route Not Defined) ---
    // Nanti Anda bisa mengganti `function() {...}` dengan Controller asli saat fiturnya dibuat.

    // C. PESANAN
    Route::prefix('orders')->name('orders.')->group(function() {
        Route::get('/', function() { return "Halaman Daftar Pesanan (Coming Soon)"; })->name('index');
        Route::get('/return', function() { return "Halaman Pengembalian (Coming Soon)"; })->name('return');
        Route::get('/shipping', function() { return "Halaman Pengaturan Pengiriman (Coming Soon)"; })->name('shipping');
    });

    // D. PUSAT PROMOSI
    Route::prefix('promotion')->name('promotion.')->group(function() {
        Route::get('/discounts', function() { return "Halaman Diskon Produk (Coming Soon)"; })->name('discounts');
        Route::get('/vouchers', function() { return "Halaman Voucher Toko (Coming Soon)"; })->name('vouchers');
    });

    // E. LAYANAN PEMBELI
    Route::prefix('service')->name('service.')->group(function() {
        Route::get('/chat', function() { return "Halaman Chat (Coming Soon)"; })->name('chat');
        Route::get('/reviews', function() { return "Halaman Penilaian Toko (Coming Soon)"; })->name('reviews');
    });

    // F. KEUANGAN
    Route::prefix('finance')->name('finance.')->group(function() {
        Route::get('/income', function() { return "Halaman Penghasilan Toko (Coming Soon)"; })->name('income');
        Route::get('/bank', function() { return "Halaman Rekening Bank (Coming Soon)"; })->name('bank');
    });

    // G. DATA / STATISTIK
    Route::prefix('data')->name('data.')->group(function() {
        Route::get('/performance', function() { return "Halaman Performa Toko (Coming Soon)"; })->name('performance');
        Route::get('/health', function() { return "Halaman Kesehatan Toko (Coming Soon)"; })->name('health');
    });

    // H. PENGATURAN TOKO
    Route::prefix('shop')->name('shop.')->group(function() {
        Route::get('/profile', function() { return "Halaman Profil Toko (Coming Soon)"; })->name('profile');
        Route::get('/decoration', function() { return "Halaman Dekorasi Toko (Coming Soon)"; })->name('decoration');
        Route::get('/settings', function() { return "Halaman Pengaturan Toko (Coming Soon)"; })->name('settings');
    });

});


// =================================================================
// 5. API & AJAX ROUTES
// =================================================================
Route::get('/api/cities/{province_id}', [AuthController::class, 'getCities']);
Route::get('/api/districts/{city_id}', [AuthController::class, 'getDistricts']);

// Chatbot
Route::post('/api/chat', function() {
    return response()->json(['reply' => 'Halo! Saya POTA (Versi Laravel).']);
})->name('api.chat');


// =================================================================
// 6. PLACEHOLDER LAINNYA
// =================================================================
Route::get('/auth/google', function() { return "Fitur Login Google (Coming Soon)"; });
Route::get('/lupa-password', function() { return "Halaman Lupa Password (Coming Soon)"; });