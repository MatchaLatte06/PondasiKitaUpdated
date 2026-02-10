<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Tambahkan ini
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Illuminate\Support\Facades\DB;   // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // === LOGIC NAVBAR (Keranjang & User) ===
        // "composer('*', ...)" artinya jalankan logic ini di SEMUA halaman
        View::composer('*', function ($view) {
            
            $total_item_keranjang = 0;

            // Cek jika user login dan bukan admin
            if (Auth::check() && Auth::user()->level !== 'admin') {
                $total_item_keranjang = DB::table('tb_keranjang')
                    ->where('user_id', Auth::id())
                    ->sum('jumlah');
            }

            // Kirim variabel $total_item_keranjang ke semua View
            $view->with('total_item_keranjang', $total_item_keranjang);
        });
    }
}