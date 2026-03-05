<?php

use Illuminate\Support\Facades\Route;

// --- IMPORT CONTROLLER UTAMA (FRONTEND & AUTH) ---
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController as FrontProductController; // ALIAS PENTING MENCEGAH BENTROK

// --- IMPORT CONTROLLER SELLER ---
use App\Http\Controllers\SellerController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController as SellerProductController; // ALIAS PENTING

// --- IMPORT CONTROLLER ADMIN ---
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\StoreController as AdminStoreController;
use App\Http\Controllers\Admin\ProductModerationController as AdminProductModerationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\DisputeController as AdminDisputeController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\PayoutController as AdminPayoutController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\LogisticSettingController as AdminLogisticSettingController;


/*
|--------------------------------------------------------------------------
| Web Routes (Enterprise E-Commerce Structure)
|--------------------------------------------------------------------------
*/

// =================================================================
// 1. HALAMAN UTAMA (LANDING PAGE)
// =================================================================
Route::get('/', [LandingController::class, 'index'])->name('home');


// =================================================================
// 2. HALAMAN FRONTEND (CUSTOMER JOURNEY)
// =================================================================

// A. Halaman Detail Produk (URL Bersih Khusus SEO)
Route::get('/produk/{id}', [FrontProductController::class, 'detail'])->name('produk.detail');

// B. Halaman Lainnya (Katalog, Toko, Keranjang)
Route::controller(PageController::class)->group(function () {
    Route::get('/pages/produk', 'produk')->name('produk.index');
    Route::get('/pages/semua_toko', 'semuaToko')->name('toko.index');
    Route::get('/pages/toko', 'detailToko')->name('toko.detail');
    Route::get('/pages/keranjang', 'keranjang')->name('keranjang.index');
    Route::get('/pages/search', 'search')->name('search');

    // Route untuk interaksi keranjang (dipanggil via AJAX SweetAlert)
    Route::post('/api/keranjang/tambah', 'tambahKeranjang')->name('keranjang.tambah');
});


// =================================================================
// 3. AUTHENTICATION (CUSTOMER & SELLER)
// =================================================================
Route::controller(AuthController::class)->group(function () {

    // --- CUSTOMER AUTH ---
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.process');
    Route::view('/register', 'auth.register_customer')->name('register');
    Route::post('/register', 'register')->name('register.process');

    // --- SELLER AUTH ---
    Route::get('/seller/login', 'showLoginSeller')->name('seller.login');
    Route::post('/seller/login', 'loginSeller')->name('seller.login.process');
    Route::get('/seller/register', 'showRegisterSeller')->name('seller.register');
    Route::post('/seller/register', 'registerSeller')->name('seller.register.process');

    // --- LOGOUT UMUM ---
    Route::post('/logout', 'logout')->name('logout');
});


// =================================================================
// 4. AREA SELLER (DASHBOARD, PRODUK, PESANAN, PROMOSI, KEUANGAN)
// =================================================================
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {

    // --- A. DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- B. MANAJEMEN PRODUK ---
    Route::resource('products', SellerProductController::class);
    Route::post('/products/toggle-status', [SellerProductController::class, 'toggleStatus'])->name('products.toggle');

    // --- C. MANAJEMEN PESANAN & RETUR ---
    Route::prefix('orders')->name('orders.')->group(function() {
        Route::get('/', [SellerController::class, 'pesanan'])->name('index');
        Route::post('/update-status', [SellerController::class, 'updateOrderStatus'])->name('updateStatus');
        Route::post('/mass-update', [SellerController::class, 'massUpdateOrderStatus'])->name('massUpdate');

        Route::get('/return', [SellerController::class, 'pengembalian'])->name('return');
        Route::post('/return/process', [SellerController::class, 'processPengembalian'])->name('return.process');
    });

    // --- D. PENGATURAN PENGIRIMAN ---
    Route::prefix('pengaturan')->name('pengaturan.')->group(function() {
        Route::get('/pengiriman', [SellerController::class, 'pengaturanPengiriman'])->name('pengiriman');
        Route::post('/pengiriman/store', [SellerController::class, 'storePengiriman'])->name('pengiriman.store');
        Route::post('/pengiriman/toggle', [SellerController::class, 'togglePengiriman'])->name('pengiriman.toggle');
        Route::delete('/pengiriman/{id}', [SellerController::class, 'destroyPengiriman'])->name('pengiriman.destroy');
    });

    // --- E. PUSAT PROMOSI (MARKETING HUB) ---
    Route::prefix('promotion')->name('promotion.')->group(function() {
        Route::get('/discounts', [SellerController::class, 'promosi'])->name('discounts');
        Route::post('/discounts/update', [SellerController::class, 'updateDiscount'])->name('discounts.update');

        Route::get('/vouchers', [SellerController::class, 'voucher'])->name('vouchers');
        Route::post('/vouchers/store', [SellerController::class, 'storeVoucher'])->name('vouchers.store');
        Route::post('/vouchers/toggle', [SellerController::class, 'toggleVoucher'])->name('vouchers.toggle');
        Route::delete('/vouchers/{id}', [SellerController::class, 'destroyVoucher'])->name('vouchers.destroy');
    });

    // --- F. LAYANAN PEMBELI (CHAT & REVIEW) ---
    Route::prefix('service')->name('service.')->group(function() {
        Route::get('/chat', [SellerController::class, 'chat'])->name('chat');
        Route::get('/chat/list', [SellerController::class, 'getChatList'])->name('chat.list');
        Route::get('/chat/messages/{chatId}', [SellerController::class, 'getMessages'])->name('chat.messages');
        Route::post('/chat/send', [SellerController::class, 'sendMessage'])->name('chat.send');

        Route::get('/reviews', [SellerController::class, 'reviews'])->name('reviews');
        Route::post('/reviews/reply', [SellerController::class, 'replyReview'])->name('reviews.reply');
    });

    // --- G. KEUANGAN (DOMPET PENJUAL) ---
    Route::prefix('finance')->name('finance.')->group(function() {
        Route::get('/income', [SellerController::class, 'income'])->name('income');
        Route::post('/payout', [SellerController::class, 'requestPayout'])->name('payout');

        Route::get('/bank', [SellerController::class, 'bank'])->name('bank');
        Route::post('/bank/update', [SellerController::class, 'updateBank'])->name('bank.update');
        Route::post('/bank/destroy', [SellerController::class, 'destroyBank'])->name('bank.destroy');
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

    // --- J. POINT OF SALE (KASIR OFFLINE) ---
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

        // Kelola Pengguna
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/export', [AdminUserController::class, 'exportCsv'])->name('users.export');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::post('/users/{id}/update', [AdminUserController::class, 'update'])->name('users.update');
        Route::post('/users/{id}/toggle-ban', [AdminUserController::class, 'toggleBan'])->name('users.toggleBan');

        // Kelola Toko
        Route::get('/stores', [AdminStoreController::class, 'index'])->name('stores.index');
        Route::post('/stores/{id}/verify', [AdminStoreController::class, 'verify'])->name('stores.verify');
        Route::post('/stores/{id}/tier', [AdminStoreController::class, 'updateTier'])->name('stores.updateTier');

        // Moderasi Produk
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

        // Laporan & Keuangan
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/payouts', [AdminPayoutController::class, 'index'])->name('payouts.index');
        Route::post('/payouts/{id}/process', [AdminPayoutController::class, 'process'])->name('payouts.process');

        // Pengaturan Sistem
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/update', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/sync-komerce', [AdminSettingController::class, 'syncKomerce'])->name('settings.syncKomerce');

        // Logistik
        Route::get('/logistics', [AdminLogisticSettingController::class, 'index'])->name('logistics.index');
        Route::post('/logistics/update', [AdminLogisticSettingController::class, 'update'])->name('logistics.update');
    });

});


// =================================================================
// 6. API & AJAX ROUTES (GLOBAL)
// =================================================================
Route::get('/api/cities/{province_id}', [AuthController::class, 'getCities']);
Route::get('/api/districts/{city_id}', [AuthController::class, 'getDistricts']);

// Chatbot POTA
Route::post('/api/chat', function() {
    return response()->json(['reply' => 'Halo! Saya POTA (Versi Laravel).']);
})->name('api.chat');


// =================================================================
// 7. FITUR TAMBAHAN (COMING SOON)
// =================================================================
Route::get('/auth/google', function() { return "Fitur Login Google (Coming Soon)"; });
Route::get('/lupa-password', function() { return "Halaman Lupa Password (Coming Soon)"; });
