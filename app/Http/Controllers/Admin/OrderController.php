<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Menampilkan Dasbor Global Orders (Multi-Vendor Logic)
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'semua');
        $search = $request->get('search');

        // 1. Statistik Live berdasarkan detail transaksi (Karena 1 invoice bisa >1 toko)
        $stats = [
            'total' => DB::table('tb_detail_transaksi')->count(),
            'perlu_dikirim' => DB::table('tb_detail_transaksi')->whereIn('status_pesanan_item', ['diproses', 'siap_kirim'])->count(),
            'sedang_dikirim' => DB::table('tb_detail_transaksi')->where('status_pesanan_item', 'dikirim')->count(),
            'komplain' => DB::table('tb_transaksi')->where('status_pesanan_global', 'komplain')->count(), 
        ];

        // 2. Query Utama: Join dari tb_detail_transaksi ke tb_transaksi, tb_toko, dan tb_user
        $query = DB::table('tb_detail_transaksi as dt')
            ->join('tb_transaksi as trx', 'dt.transaksi_id', '=', 'trx.id')
            ->join('tb_toko as t', 'dt.toko_id', '=', 't.id')
            ->join('tb_user as u', 'trx.user_id', '=', 'u.id')
            ->select(
                'dt.id', // ID sub-pesanan
                'trx.id as transaksi_id',
                'trx.kode_invoice',
                'trx.tanggal_transaksi',
                // Total nilai yang harus dipenuhi oleh toko ini (Subtotal barang + Ongkir khusus toko ini)
                DB::raw('(dt.subtotal + dt.biaya_pengiriman_item) as total_final'), 
                'trx.status_pembayaran',
                'dt.status_pesanan_item as status_pesanan', // Status pengiriman spesifik toko
                't.nama_toko',
                'u.nama as nama_pembeli',
                'u.email as email_pembeli',
                'dt.kurir_terpilih as kurir_pengiriman',
                'dt.resi_pengiriman as nomor_resi'
            );

        // Filter Tab Status
        if ($status !== 'semua') {
            $query->where('dt.status_pesanan_item', $status);
        }

        // Pencarian (Invoice, Nama Pembeli, atau Nama Toko)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('trx.kode_invoice', 'LIKE', "%$search%")
                  ->orWhere('u.nama', 'LIKE', "%$search%")
                  ->orWhere('t.nama_toko', 'LIKE', "%$search%");
            });
        }

        // Urutkan dari transaksi terbaru
        $orders = $query->latest('trx.tanggal_transaksi')->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders', 'status', 'search', 'stats'));
    }

    /**
     * Detail Pesanan (Akan menampilkan rincian barang, resi, dll)
     */
    public function show($id)
    {
        return "Halaman Detail Pesanan (Sedang dalam pengembangan)";
    }
}