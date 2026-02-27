<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Setup Filter Rentang Waktu (Default: 30 Hari Terakhir)
        $start_date = $request->get('start_date', Carbon::now()->subDays(29)->format('Y-m-d'));
        $end_date = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // 2. Query KPI (Key Performance Indicators)
        // Menghitung GMV (Total Penjualan Kotor) dari transaksi yang berhasil dibayar
        $gmv = DB::table('tb_transaksi')
            ->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$start_date, $end_date])
            ->whereIn('status_pembayaran', ['paid'])
            ->sum('total_final');

        // Menghitung Total Pendapatan Platform (Komisi)
        $revenue = DB::table('tb_komisi')
            ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])
            ->where('status', 'paid')
            ->sum('jumlah_komisi');

        // Total Transaksi Berhasil
        $total_orders = DB::table('tb_transaksi')
            ->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$start_date, $end_date])
            ->whereIn('status_pembayaran', ['paid'])
            ->count();

        // Rata-rata Nilai Pesanan (AOV - Average Order Value)
        $aov = $total_orders > 0 ? $gmv / $total_orders : 0;

        $stats = [
            'gmv' => $gmv,
            'revenue' => $revenue,
            'total_orders' => $total_orders,
            'aov' => $aov
        ];

        // 3. Persiapkan Data Grafik (Tren Penjualan Harian)
        $chart_data = DB::table('tb_transaksi')
            ->select(DB::raw('DATE(tanggal_transaksi) as date'), DB::raw('SUM(total_final) as total_sales'))
            ->whereBetween(DB::raw('DATE(tanggal_transaksi)'), [$start_date, $end_date])
            ->where('status_pembayaran', 'paid')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $chart_labels = [];
        $chart_values = [];
        
        // Mengisi array untuk Chart.js
        foreach ($chart_data as $data) {
            $chart_labels[] = Carbon::parse($data->date)->format('d M');
            $chart_values[] = $data->total_sales;
        }

        // 4. Ambil Daftar Transaksi Terbaru (High-Density Table)
        $recent_transactions = DB::table('tb_transaksi as t')
            ->join('tb_user as u', 't.user_id', '=', 'u.id')
            ->select('t.*', 'u.nama as nama_pembeli')
            ->whereBetween(DB::raw('DATE(t.tanggal_transaksi)'), [$start_date, $end_date])
            ->orderBy('t.tanggal_transaksi', 'DESC')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact(
            'stats', 
            'recent_transactions', 
            'chart_labels', 
            'chart_values', 
            'start_date', 
            'end_date'
        ));
    }
}