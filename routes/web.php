<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PageController;
// use App\Http\Controllers\AuthController; // Uncomment nanti jika sudah buat AuthController

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =================================================================
// 1. HALAMAN UTAMA (LANDING PAGE)
// =================================================================
// Memanggil LandingController function index()
Route::get('/', [LandingController::class, 'index'])->name('home');


// =================================================================
// 2. HALAMAN USER (PRODUK, TOKO, DLL)
// =================================================================
// Menggunakan Grouping Controller agar lebih rapi
Route::controller(PageController::class)->group(function () {
    
    // Link: website.com/pages/produk
    Route::get('/pages/produk', 'produk');

    // Link: website.com/pages/semua_toko
    Route::get('/pages/semua_toko', 'semuaToko');

    // Link: website.com/pages/detail_produk?id=1
    Route::get('/pages/detail_produk', 'detailProduk');

    // Link: website.com/pages/toko?slug=nama-toko
    Route::get('/pages/toko', 'detailToko');

    // Link: website.com/pages/keranjang (Dari Navbar)
    Route::get('/pages/keranjang', 'keranjang');

    // Link: website.com/pages/search (Dari Search Bar)
    Route::get('/pages/search', 'search');

});


// =================================================================
// 3. AUTHENTICATION (LOGIN/REGISTER)
// =================================================================
// Ini route sementara agar Navbar tidak error saat diklik.
// Nanti ganti dengan AuthController buatan Anda.

Route::get('/login', function () {
    return "Halaman Login (Belum Dibuat)";
})->name('login');

Route::get('/register', function () {
    return "Halaman Register (Belum Dibuat)";
})->name('register');

// Route Logout wajib POST demi keamanan (sesuai form di navbar)
Route::post('/logout', function () {
    // Auth::logout();
    return redirect('/');
})->name('logout');


// =================================================================
// 4. API ROUTES (Opsional jika error 404 API Chat)
// =================================================================
// Jika Fetch Chatbot di blade error 404, Anda bisa daftarkan routenya disini juga
// Route::post('/api/chat', [App\Http\Controllers\ChatController::class, 'sendMessage']);