<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- 1. TUGAS ADMIN (Perlu Tindakan) ---
        $tugas = [
            'toko_pending'   => DB::table('tb_toko')->where('status', 'pending')->count(),
            'produk_pending' => DB::table('tb_barang')->where('status_moderasi', 'pending')->count(),
            'payout_pending' => DB::table('tb_payouts')->where('status', 'pending')->count(), 
        ];

        // --- 2. STATISTIK UTAMA ---
        $statistik = [
            // Mengambil total penjualan dari transaksi yang sudah dibayar (paid)
            'total_penjualan' => DB::table('tb_transaksi')->where('status_pembayaran', 'paid')->sum('total_final') ?? 0,
            
            // Menggunakan tb_user sesuai struktur database pondasikita
            'total_pengguna'  => DB::table('tb_user')->where('level', '!=', 'bot')->count(), 
            
            'total_toko'      => DB::table('tb_toko')->where('status', 'active')->count(),
            'total_produk'    => DB::table('tb_barang')->where('status_moderasi', 'approved')->count(),
        ];

        // --- 3. DATA GRAFIK (Pertumbuhan Pengguna 7 Hari Terakhir) ---
        $chart_labels = [];
        $chart_values = [];

        // Looping dari 6 hari yang lalu hingga hari ini
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->toDateString(); // Format Y-m-d

            // Hitung user yang mendaftar pada tanggal tersebut (menggunakan tb_user)
            $count = DB::table('tb_user')
                ->whereDate('created_at', $dateString)
                ->count();

            $chart_labels[] = $date->translatedFormat('d M'); // Format misal: 21 Feb
            $chart_values[] = $count;
        }

        // Kirim semua data ke View
        return view('admin.dashboard', compact('tugas', 'statistik', 'chart_labels', 'chart_values'));
    }
}