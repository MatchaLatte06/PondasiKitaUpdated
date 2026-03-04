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
        // 1. Ambil Tugas Moderasi / Antrean
        $tugas = [
            'toko_pending' => DB::table('tb_toko')->where('status', 'pending')->count(),
            'produk_pending' => DB::table('tb_barang')->where('status_moderasi', 'pending')->count(),
            // 'payout_pending' => DB::table('tb_payouts')->where('status', 'pending')->count(), // Buka jika tabel payout sudah aktif
            'payout_pending' => 0, 
            'komplain_aktif' => DB::table('tb_komplain')->whereIn('status_komplain', ['investigasi', 'menunggu_tanggapan_toko'])->count(),
        ];

        // 2. Ambil Statistik Global Platform
        $statistik = [
            'total_penjualan' => DB::table('tb_transaksi')->where('status_pesanan_global', 'selesai')->sum('total_final'),
            'total_pengguna' => DB::table('tb_user')->count(),
            'total_toko' => DB::table('tb_toko')->where('status', 'active')->count(),
            'total_produk' => DB::table('tb_barang')
                                ->where('status_moderasi', 'approved')
                                ->where('is_active', 1)
                                ->count(),
        ];

        // 3. Data Grafik (Pengguna Baru 7 Hari Terakhir)
        $chart_labels = [];
        $chart_values = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chart_labels[] = $date->format('D'); 
            $chart_values[] = DB::table('tb_user')->whereDate('created_at', $date)->count();
        }

        // 4. Ambil Top Performance Toko Bangunan (Mendukung Multi-Vendor)
        $topToko = DB::table('tb_toko as t')
            ->join('tb_detail_transaksi as dt', 't.id', '=', 'dt.toko_id')
            ->join('tb_transaksi as trx', 'dt.transaksi_id', '=', 'trx.id')
            ->leftJoin('cities as c', 't.city_id', '=', 'c.id')
            ->select(
                't.nama_toko', 
                'c.name as nama_kota', 
                DB::raw('SUM(dt.subtotal) as total_gmv'), // Hitung GMV dari subtotal masing-masing toko
                DB::raw('COUNT(DISTINCT trx.id) as total_order') // Hitung jumlah invoice unik
            )
            ->where('trx.status_pesanan_global', 'selesai')
            ->groupBy('t.id', 't.nama_toko', 'c.name')
            ->orderByDesc('total_gmv')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('tugas', 'statistik', 'chart_labels', 'chart_values', 'topToko'));
    }
}