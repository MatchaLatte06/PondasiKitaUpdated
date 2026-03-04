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
        
        // 1. Ambil ID Toko
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        if (!$toko) {
            return redirect()->route('home')->with('error', 'Toko tidak ditemukan.');
        }
        $tokoId = $toko->id;

        // 2. DATA STATISTIK UTAMA (Untuk Performa Toko)
        $total_penjualan = DB::table('tb_detail_transaksi')->where('toko_id', $tokoId)->whereNotIn('status_pesanan_item', ['dibatalkan'])->sum('subtotal');
        $total_pesanan   = DB::table('tb_detail_transaksi')->where('toko_id', $tokoId)->distinct('transaksi_id')->count('transaksi_id');
        $total_item_terjual = DB::table('tb_detail_transaksi')->where('toko_id', $tokoId)->whereNotIn('status_pesanan_item', ['dibatalkan'])->sum('jumlah');
        $total_produk_aktif = DB::table('tb_barang')->where('toko_id', $tokoId)->where('is_active', 1)->count();

        // 3. KARTU "YANG PERLU DILAKUKAN" (Data Real-Time)
        $perlu_diproses = DB::table('tb_detail_transaksi')
            ->where('toko_id', $tokoId)
            ->whereIn('status_pesanan_item', ['menunggu_pembayaran', 'diproses'])
            ->count();
            
        $telah_diproses = DB::table('tb_detail_transaksi')
            ->where('toko_id', $tokoId)
            ->where('status_pesanan_item', 'siap_kirim')
            ->count();
            
        $pengembalian = DB::table('tb_komplain')
            ->where('toko_id', $tokoId)
            ->whereIn('status_komplain', ['investigasi', 'menunggu_tanggapan_toko'])
            ->count();

        $dibatalkan = DB::table('tb_detail_transaksi')
            ->where('toko_id', $tokoId)
            ->where('status_pesanan_item', 'dibatalkan')
            ->count();

        // 4. DATA GRAFIK BULANAN
        $labels_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $tahunSekarang = Carbon::now()->year;

        $dataPenjualan = DB::table('tb_detail_transaksi as d')
            ->join('tb_transaksi as t', 'd.transaksi_id', '=', 't.id')
            ->select(DB::raw('MONTH(t.tanggal_transaksi) as bulan'), DB::raw('SUM(d.subtotal) as total'))
            ->where('d.toko_id', $tokoId)
            ->whereYear('t.tanggal_transaksi', $tahunSekarang)
            ->whereIn('d.status_pesanan_item', ['dikirim', 'sampai_tujuan', 'selesai'])
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $penjualan_tahunan = [];
        for ($i = 1; $i <= 12; $i++) {
            $penjualan_tahunan[] = $dataPenjualan[$i] ?? 0;
        }

        // 5. TOP PRODUK
        $topProdukQuery = DB::table('tb_detail_transaksi as d')
            ->join('tb_barang as b', 'd.barang_id', '=', 'b.id')
            ->select('b.nama_barang', DB::raw('SUM(d.jumlah) as total_terjual'))
            ->where('d.toko_id', $tokoId)
            ->whereNotIn('d.status_pesanan_item', ['dibatalkan'])
            ->groupBy('d.barang_id', 'b.nama_barang')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $top_produk_keys = $topProdukQuery->pluck('nama_barang')->toArray();
        $top_produk_values = $topProdukQuery->pluck('total_terjual')->toArray();

        // Hitung Konversi (Contoh sederhana: Pesanan / Item Terjual * 100)
        $konversi = $total_item_terjual > 0 ? round(($total_pesanan / $total_item_terjual) * 100, 2) : 0;

        return view('seller.dashboard', compact(
            'user', 'toko',
            'perlu_diproses', 'telah_diproses', 'pengembalian', 'dibatalkan',
            'total_penjualan', 'total_pesanan', 'total_item_terjual', 'total_produk_aktif', 'konversi',
            'labels_bulan', 'penjualan_tahunan',
            'top_produk_keys', 'top_produk_values'
        ));
    }
}