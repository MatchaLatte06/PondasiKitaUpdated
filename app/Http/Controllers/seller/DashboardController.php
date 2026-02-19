<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil ID Toko
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        if (!$toko) {
            return redirect()->route('home')->with('error', 'Toko tidak ditemukan.');
        }
        $tokoId = $toko->id;

        // --- 1. AMBIL DATA STATISTIK (Sama persis dengan native) ---
        $total_penjualan = DB::table('tb_detail_transaksi')->where('toko_id', $tokoId)->sum('subtotal');
        $total_pesanan   = DB::table('tb_detail_transaksi')->where('toko_id', $tokoId)->distinct('transaksi_id')->count('transaksi_id');
        $total_item_terjual = DB::table('tb_detail_transaksi')->where('toko_id', $tokoId)->sum('jumlah');
        $total_produk_aktif = DB::table('tb_barang')->where('toko_id', $tokoId)->where('is_active', 1)->count();

        // --- 2. DATA GRAFIK BULANAN ---
        $labels_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $tahunSekarang = Carbon::now()->year;

        $dataPenjualan = DB::table('tb_detail_transaksi as d')
            ->join('tb_transaksi as t', 'd.transaksi_id', '=', 't.id')
            ->select(DB::raw('MONTH(t.tanggal_transaksi) as bulan'), DB::raw('SUM(d.subtotal) as total'))
            ->where('d.toko_id', $tokoId)
            ->whereYear('t.tanggal_transaksi', $tahunSekarang)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Mapping array 1-12
        $penjualan_tahunan = [];
        for ($i = 1; $i <= 12; $i++) {
            $penjualan_tahunan[] = $dataPenjualan[$i] ?? 0;
        }

        // --- 3. TOP PRODUK ---
        $topProdukQuery = DB::table('tb_detail_transaksi as d')
            ->join('tb_barang as b', 'd.barang_id', '=', 'b.id')
            ->select('b.nama_barang', DB::raw('SUM(d.jumlah) as total_terjual'))
            ->where('d.toko_id', $tokoId)
            ->groupBy('d.barang_id', 'b.nama_barang')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $top_produk_keys = $topProdukQuery->pluck('nama_barang')->toArray();
        $top_produk_values = $topProdukQuery->pluck('total_terjual')->toArray();

        // Kirim ke View
        return view('seller.dashboard', compact(
            'user', 
            'total_penjualan', 
            'total_pesanan', 
            'total_item_terjual', 
            'total_produk_aktif',
            'labels_bulan',
            'penjualan_tahunan',
            'top_produk_keys',
            'top_produk_values'
        ));
    }
}