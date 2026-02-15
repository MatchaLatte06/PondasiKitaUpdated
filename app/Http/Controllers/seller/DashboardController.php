<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller {
    public function index() {
        $user = Auth::user();
        
        // Ambil data toko milik user yang sedang login
        $toko = Toko::where('user_id', $user->id)->first();

        if (!$toko) {
            return redirect()->route('home')->with('error', 'Data toko tidak ditemukan.');
        }

        $tokoId = $toko->id;

        // 1. Statistik Utama (Metrics) - Sesuai dengan $stats di view
        $stats = [
            'total_penjualan'   => DB::table('tb_detail_transaksi')->where('toko_id', $tokoId)->sum('subtotal'),
            'total_pesanan'     => DB::table('tb_detail_transaksi')->where('toko_id', $tokoId)->distinct('transaksi_id')->count(),
            'total_item'        => DB::table('tb_detail_transaksi')->where('toko_id', $tokoId)->sum('jumlah'),
            'total_produk_aktif'=> Barang::where('toko_id', $tokoId)->where('is_active', 1)->count(),
        ];

        // 2. Persiapan Data Grafik (Sesuai variabel di view Anda)
        $labels_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        
        $tahunSekarang = Carbon::now()->year;
        $dataPenjualan = DB::table('tb_detail_transaksi as d')
            ->join('tb_transaksi as t', 'd.transaksi_id', '=', 't.id')
            ->select(DB::raw('MONTH(t.tanggal_transaksi) as bulan'), DB::raw('SUM(d.subtotal) as total'))
            ->where('d.toko_id', $tokoId)
            ->whereYear('t.tanggal_transaksi', $tahunSekarang)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')->toArray();

        // Mapping ke variabel $penjualan_tahunan agar tidak error Undefined
        $penjualan_tahunan = [];
        for ($i = 1; $i <= 12; $i++) {
            $penjualan_tahunan[] = $dataPenjualan[$i] ?? 0;
        }

        // 3. Top 5 Produk Terlaris (Opsional untuk digunakan di view)
        $topProduk = DB::table('tb_detail_transaksi as d')
            ->join('tb_barang as b', 'd.barang_id', '=', 'b.id')
            ->select('b.nama_barang', DB::raw('SUM(d.jumlah) as total_terjual'))
            ->where('d.toko_id', $tokoId)
            ->groupBy('d.barang_id', 'b.nama_barang')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Mengirimkan variabel dengan nama yang tepat ke view
        return view('seller.dashboard', compact('stats', 'labels_bulan', 'penjualan_tahunan', 'topProduk', 'user'));
    }
}