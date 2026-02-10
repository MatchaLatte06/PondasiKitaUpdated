<?php

use App\Http\Controllers\LandingController;

// Ganti route '/' yang tadi kita buat test, menjadi ini:
Route::get('/', [LandingController::class, 'index'])->name('home');