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

// --- IMPORT CONTROLLER ADMIN ---
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\StoreController as AdminStoreController;
use App\Http\Controllers\Admin\ProductModerationController as AdminProductModerationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\DisputeController as AdminDisputeController;


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

    // --- LOGOUT UMUM ---
    Route::post('/logout', 'logout')->name('logout');
});


// =================================================================
// 4. AREA SELLER (DASHBOARD, PRODUK, PESANAN & SIDEBAR MENU)
// =================================================================
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    
    // --- A. DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- B. MANAJEMEN PRODUK ---
    Route::resource('products', ProductController::class);
    Route::post('/products/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle');

    // --- C. MANAJEMEN PESANAN ---
    Route::prefix('orders')->name('orders.')->group(function() {
        Route::get('/', [SellerController::class, 'pesanan'])->name('index');
        Route::get('/return', [SellerController::class, 'pengembalian'])->name('return');
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
        Route::get('/reviews', [SellerController::class, 'reviews'])->name('reviews');
        Route::post('/reviews/reply', [SellerController::class, 'replyReview'])->name('reviews.reply');
    });

    // --- G. KEUANGAN ---
    Route::prefix('finance')->name('finance.')->group(function() {
        Route::get('/income', [SellerController::class, 'income'])->name('income');
        Route::get('/bank', [SellerController::class, 'bank'])->name('bank');
    });

   // --- H. DATA / STATISTIK ---
   Route::prefix('data')->name('data.')->group(function() {
       Route::get('/performance', [SellerController::class, 'performance'])->name('performance');
       Route::get('/health', [SellerController::class, 'health'])->name('health');
    });

    // --- I. PENGATURAN TOKO UMUM ---
    Route::prefix('shop')->name('shop.')->group(function() {
        Route::get('/profile', function() { return "Halaman Profil Toko (Coming Soon)"; })->name('profile');
        Route::get('/decoration', function() { return "Halaman Dekorasi Toko (Coming Soon)"; })->name('decoration');
        Route::get('/settings', function() { return "Halaman Pengaturan Toko (Coming Soon)"; })->name('settings');
    });

    // --- J. POINT OF SALE (KASIR) ---
    Route::prefix('pos')->name('pos.')->group(function() {
        Route::get('/', [SellerController::class, 'pos'])->name('index');
        Route::get('/api/products', [SellerController::class, 'getPosProducts'])->name('api.products');
        Route::get('/api/categories', [SellerController::class, 'getPosCategories'])->name('api.categories');
        Route::post('/api/checkout', [SellerController::class, 'processPosCheckout'])->name('api.checkout');
    });
});


// =================================================================
// 5. AREA ADMIN (GHOST SYSTEM) DENGAN ROLE-BASED ACCESS CONTROL
// =================================================================

// 5A. PINTU BELAKANG ADMIN (SECRET LOGIN)
Route::get('/kunci-brankas-pks', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/kunci-brankas-pks', [AdminAuthController::class, 'login'])->name('admin.login.submit');

// 5B. ROUTE ADMIN TERLINDUNGI
Route::prefix('portal-rahasia-pks')->name('admin.')->middleware(['admin'])->group(function () {
    
    // --- BISA DIAKSES OLEH SEMUA KASTA ADMIN ---
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('admin.role:super,finance,cs');

    // --- GRUP KHUSUS SUPER ADMIN & ADMIN CS (Customer Service) ---
    Route::middleware(['admin.role:super,cs'])->group(function () {
        
        // Kelola Pengguna (Directory)
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/export', [AdminUserController::class, 'exportCsv'])->name('users.export');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::post('/users/{id}/update', [AdminUserController::class, 'update'])->name('users.update'); 
        Route::post('/users/{id}/toggle-ban', [AdminUserController::class, 'toggleBan'])->name('users.toggleBan');
        
        // Kelola Toko
        Route::get('/stores', [AdminStoreController::class, 'index'])->name('stores.index');
        Route::post('/stores/{id}/verify', [AdminStoreController::class, 'verify'])->name('stores.verify');
        Route::post('/stores/{id}/tier', [AdminStoreController::class, 'updateTier'])->name('stores.updateTier');

        // Moderasi Produk (Material)
        Route::get('/products', [AdminProductModerationController::class, 'index'])->name('products.index');
        Route::get('/products/{id}', [AdminProductModerationController::class, 'show'])->name('products.show');
        Route::post('/products/{id}/process', [AdminProductModerationController::class, 'process'])->name('products.process');
        
        // Pemantauan Pesanan & Pusat Resolusi
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::get('/disputes', [AdminDisputeController::class, 'index'])->name('disputes.index');
        Route::post('/disputes/{id}/resolve', [AdminDisputeController::class, 'resolve'])->name('disputes.resolve');
    });

    // --- GRUP KHUSUS SUPER ADMIN & ADMIN FINANCE ---
    Route::middleware(['admin.role:super,finance'])->group(function () {
        
        // Laporan & Keuangan (Payouts)
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/payouts', [\App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('payouts.index');
        Route::post('/payouts/{id}/process', [\App\Http\Controllers\Admin\PayoutController::class, 'process'])->name('payouts.process');

        // Pengaturan Sistem
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/update', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/sync-komerce', [\App\Http\Controllers\Admin\SettingController::class, 'syncKomerce'])->name('settings.syncKomerce');
        
        // Logistik
        Route::get('/logistics', [\App\Http\Controllers\Admin\LogisticSettingController::class, 'index'])->name('logistics.index');
        Route::post('/logistics/update', [\App\Http\Controllers\Admin\LogisticSettingController::class, 'update'])->name('logistics.update');
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