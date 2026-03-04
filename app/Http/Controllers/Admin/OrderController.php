<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Menampilkan Dasbor Global Orders (Multi-Vendor & B2B Logic)
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'semua');
        $search = $request->get('search');

        // 1. Statistik Live berdasarkan detail transaksi
        $stats = [
            'total' => DB::table('tb_detail_transaksi')->count(),
            'perlu_dikirim' => DB::table('tb_detail_transaksi')->whereIn('status_pesanan_item', ['diproses', 'siap_kirim'])->count(),
            'sedang_dikirim' => DB::table('tb_detail_transaksi')->where('status_pesanan_item', 'dikirim')->count(),
            'komplain' => DB::table('tb_transaksi')->where('status_pesanan_global', 'komplain')->count(), 
        ];

        // 2. Query Utama: Join Multi-Vendor dengan Logika DP B2B
        $query = DB::table('tb_detail_transaksi as dt')
            ->join('tb_transaksi as trx', 'dt.transaksi_id', '=', 'trx.id')
            ->join('tb_toko as t', 'dt.toko_id', '=', 't.id')
            ->join('tb_user as u', 'trx.user_id', '=', 'u.id')
            ->select(
                'dt.id', 
                'trx.id as transaksi_id',
                'trx.kode_invoice',
                'trx.tanggal_transaksi',
                'trx.status_pembayaran',
                'dt.status_pesanan_item as status_pesanan',
                't.nama_toko',
                'u.nama as nama_pembeli',
                'u.email as email_pembeli',
                'dt.kurir_terpilih as kurir_pengiriman',
                'dt.resi_pengiriman as nomor_resi',
                
                // Mendukung Sistem DP B2B
                'trx.tipe_pembayaran', 
                'trx.jumlah_dp',       
                'trx.sisa_tagihan',    
                
                // Total nilai per-toko (Subtotal barang + Ongkir khusus item ini)
                DB::raw('(dt.subtotal + dt.biaya_pengiriman_item) as total_final')
            );

        // Filter Tab Status
        if ($status !== 'semua') {
            $mapStatus = [
                'pending'  => 'menunggu_pembayaran',
                'diproses' => 'diproses',
                'dikirim'  => 'dikirim',
                'selesai'  => 'selesai',
                'komplain' => 'komplain'
            ];
            
            if (isset($mapStatus[$status])) {
                $query->where('dt.status_pesanan_item', $mapStatus[$status]);
            }
        }

        // Pencarian dinamis
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('trx.kode_invoice', 'LIKE', "%$search%")
                  ->orWhere('u.nama', 'LIKE', "%$search%")
                  ->orWhere('t.nama_toko', 'LIKE', "%$search%");
            });
        }

        $orders = $query->latest('trx.tanggal_transaksi')->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders', 'status', 'search', 'stats'));
    }

    /**
     * Menampilkan Detail Pesanan yang Sangat Terperinci (Mewah)
     */
    public function show($id)
    {
        // 1. Ambil data utama pesanan (Item spesifik toko tertentu)
        $order = DB::table('tb_detail_transaksi as dt')
            ->join('tb_transaksi as trx', 'dt.transaksi_id', '=', 'trx.id')
            ->join('tb_toko as t', 'dt.toko_id', '=', 't.id')
            ->join('tb_user as u', 'trx.user_id', '=', 'u.id')
            ->select(
                'dt.*', 
                'trx.kode_invoice', 'trx.tanggal_transaksi', 'trx.status_pembayaran', 
                'trx.tipe_pembayaran', 'trx.jumlah_dp', 'trx.sisa_tagihan',
                'trx.metode_pembayaran', 'trx.customer_service_fee', 'trx.customer_handling_fee',
                't.nama_toko', 't.no_telepon as telp_toko', 'u.nama as nama_pembeli', 'u.email as email_pembeli'
            )
            ->where('dt.id', $id)
            ->first();

        if (!$order) { 
            abort(404, 'Pesanan tidak ditemukan'); 
        }

        // 2. Ambil list produk apa saja yang dibeli di toko tersebut dalam invoice ini
        // Menggunakan tb_barang sesuai struktur database Anda
        $items = DB::table('tb_detail_transaksi')
            ->join('tb_barang', 'tb_detail_transaksi.barang_id', '=', 'tb_barang.id')
            ->where('transaksi_id', $order->transaksi_id)
            ->where('toko_id', $order->toko_id)
            ->select('tb_detail_transaksi.*', 'tb_barang.nama_barang', 'tb_barang.foto_barang')
            ->get();

        return view('admin.orders.show', compact('order', 'items'));
    }
}