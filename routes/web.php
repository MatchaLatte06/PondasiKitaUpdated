<?php

use Illuminate\Support\Facades\Route;

// --- IMPORT CONTROLLER UTAMA ---
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;

// --- IMPORT CONTROLLER SELLER ---
use App\Http\Controllers\SellerController; 
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController;


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

    // --- ADMIN LOGIN ---
    Route::get('/admin/login', 'showLoginAdmin')->name('admin.login');
    Route::post('/admin/login', 'loginAdmin')->name('admin.login.process');

    // --- LOGOUT ---
    Route::post('/logout', 'logout')->name('logout');
});


// =================================================================
// 4. AREA SELLER (DASHBOARD, PRODUK, PESANAN & SIDEBAR MENU)
// =================================================================
// Menggunakan middleware 'auth' (dan 'role:seller' jika Anda sudah membuat middleware tersebut)
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    
    // --- A. DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- B. MANAJEMEN PRODUK ---
    Route::resource('products', ProductController::class);
    // Route Khusus AJAX Toggle Status Produk
    Route::post('/products/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle');

    // --- C. MANAJEMEN PESANAN ---
    Route::prefix('orders')->name('orders.')->group(function() {
        // 1. Daftar Pesanan Masuk
        Route::get('/', [SellerController::class, 'pesanan'])->name('index');
        // 2. Pengembalian & Pembatalan
        Route::get('/return', [SellerController::class, 'pengembalian'])->name('return');
        // 3. Aksi Update Status
        Route::post('/update-status', [SellerController::class, 'updateOrderStatus'])->name('updateStatus');
        Route::post('/mass-update', [SellerController::class, 'massUpdateOrderStatus'])->name('massUpdate');
    });

    // --- D. PENGATURAN PENGIRIMAN ---
    Route::prefix('pengaturan')->name('pengaturan.')->group(function() {
        Route::get('/pengiriman', [SellerController::class, 'pengaturanPengiriman'])->name('pengiriman');
        Route::post('/pengiriman/store', [SellerController::class, 'storePengiriman'])->name('pengiriman.store');
        Route::post('/pengiriman/toggle', [SellerController::class, 'togglePengiriman'])->name('pengiriman.toggle');
        Route::delete('/pengiriman/{id}', [SellerController::class, 'destroyPengiriman'])->name('pengiriman.destroy');
    });

    // --- E. PUSAT PROMOSI ---
    Route::prefix('promotion')->name('promotion.')->group(function() {
        Route::get('/discounts', [SellerController::class, 'promosi'])->name('discounts');
        Route::get('/vouchers', [SellerController::class, 'voucher'])->name('vouchers');
    });

    // --- F. LAYANAN PEMBELI ---
    Route::prefix('service')->name('service.')->group(function() {
        Route::get('/chat', [SellerController::class, 'chat'])->name('chat');
        Route::get('/chat/list', [SellerController::class, 'getChatList'])->name('chat.list');
        Route::get('/chat/messages/{chatId}', [SellerController::class, 'getMessages'])->name('chat.messages');
        Route::post('/chat/send', [SellerController::class, 'sendMessage'])->name('chat.send');
        Route::get('/reviews', function() { return "Halaman Penilaian Toko (Coming Soon)"; })->name('reviews');
    });

    // --- G. KEUANGAN ---
    Route::prefix('finance')->name('finance.')->group(function() {
        Route::get('/income', function() { return "Halaman Penghasilan Toko (Coming Soon)"; })->name('income');
        Route::get('/bank', function() { return "Halaman Rekening Bank (Coming Soon)"; })->name('bank');
    });

    // --- H. DATA / STATISTIK ---
    Route::prefix('data')->name('data.')->group(function() {
        Route::get('/performance', function() { return "Halaman Performa Toko (Coming Soon)"; })->name('performance');
        Route::get('/health', function() { return "Halaman Kesehatan Toko (Coming Soon)"; })->name('health');
    });

    // --- I. PENGATURAN TOKO UMUM ---
    Route::prefix('shop')->name('shop.')->group(function() {
        Route::get('/profile', function() { return "Halaman Profil Toko (Coming Soon)"; })->name('profile');
        Route::get('/decoration', function() { return "Halaman Dekorasi Toko (Coming Soon)"; })->name('decoration');
        Route::get('/settings', function() { return "Halaman Pengaturan Toko (Coming Soon)"; })->name('settings');
    });

    // --- J. POINT OF SALE (KASIR) ---
    Route::prefix('pos')->name('pos.')->group(function() {
        Route::get('/', [\App\Http\Controllers\SellerController::class, 'pos'])->name('index');
        
        // API untuk AJAX POS
        Route::get('/api/products', [\App\Http\Controllers\SellerController::class, 'getPosProducts'])->name('api.products');
        Route::get('/api/categories', [\App\Http\Controllers\SellerController::class, 'getPosCategories'])->name('api.categories');
        Route::post('/api/checkout', [\App\Http\Controllers\SellerController::class, 'processPosCheckout'])->name('api.checkout');
    });
});


// =================================================================
// 5. AREA ADMIN (DASHBOARD)
// =================================================================
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            // Nanti diganti memanggil AdminController
            return "Selamat datang di Dashboard Admin!"; 
        })->name('dashboard');
    });
});


// =================================================================
// 6. API & AJAX ROUTES (GLOBAL)
// =================================================================
Route::get('/api/cities/{province_id}', [AuthController::class, 'getCities']);
Route::get('/api/districts/{city_id}', [AuthController::class, 'getDistricts']);

// Chatbot
Route::post('/api/chat', function() {
    return response()->json(['reply' => 'Halo! Saya POTA (Versi Laravel).']);
})->name('api.chat');


// =================================================================
// 7. PLACEHOLDER LAINNYA
// =================================================================
Route::get('/auth/google', function() { return "Fitur Login Google (Coming Soon)"; });
Route::get('/lupa-password', function() { return "Halaman Lupa Password (Coming Soon)"; });