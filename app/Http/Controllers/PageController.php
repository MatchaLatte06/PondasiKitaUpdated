<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    // =================================================================
    // 1. HALAMAN DAFTAR PRODUK (Lengkap dengan Filter)
    // =================================================================
    public function produk(Request $request)
    {
        // A. AMBIL DATA FILTER (Kategori & Lokasi)
        $categories = DB::table('tb_kategori')
            ->orderBy('nama_kategori', 'ASC')
            ->get();

        $locations = DB::table('tb_toko as t')
            ->join('cities as c', 't.city_id', '=', 'c.id')
            ->select('t.city_id', 'c.name as nama_kota')
            ->distinct()
            ->where('t.status', 'active')
            ->orderBy('c.name', 'ASC')
            ->get();

        // B. QUERY UTAMA BARANG
        $query = DB::table('tb_barang as b')
            ->join('tb_toko as t', 'b.toko_id', '=', 't.id')
            ->leftJoin('cities as c', 't.city_id', '=', 'c.id')
            ->select(
                'b.id', 
                'b.nama_barang', 
                'b.harga', 
                'b.gambar_utama', 
                'b.satuan_unit',
                't.nama_toko', 
                't.slug as toko_slug', 
                'c.name as nama_kota'
            )
            ->where('b.is_active', 1)
            ->where('b.status_moderasi', 'approved')
            ->where('t.status', 'active');

        // C. TERAPKAN FILTER
        
        // 1. Filter Kategori
        if ($request->has('kategori') && is_array($request->kategori)) {
            $query->whereIn('b.kategori_id', $request->kategori);
        }

        // 2. Filter Lokasi
        if ($request->filled('lokasi')) {
            $query->where('t.city_id', $request->lokasi);
        }

        // 3. Filter Harga
        if ($request->filled('harga_min')) {
            $query->where('b.harga', '>=', $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $query->where('b.harga', '<=', $request->harga_max);
        }

        // 4. Filter Pencarian (Search Bar)
        if ($request->filled('query')) {
            $keyword = '%' . $request->query('query') . '%';
            $query->where(function($q) use ($keyword) {
                $q->where('b.nama_barang', 'like', $keyword)
                  ->orWhere('t.nama_toko', 'like', $keyword);
            });
        }

        // D. EKSEKUSI (Pagination)
        $products = $query->paginate(12)->withQueryString();

        return view('pages.produk', compact('categories', 'locations', 'products'));
    }

    // =================================================================
    // 2. HALAMAN DETAIL PRODUK
    // =================================================================
    public function detailProduk(Request $request)
    {
        $id = $request->query('id');

        // Ambil Data Produk
        $produk = DB::table('tb_barang as b')
            ->join('tb_toko as t', 'b.toko_id', '=', 't.id')
            ->leftJoin('cities as c', 't.city_id', '=', 'c.id') // Join kota biar tidak error
            ->select('b.*', 't.nama_toko', 't.slug as toko_slug', 'c.name as kota_toko', 't.logo_toko')
            ->where('b.id', $id)
            ->first();

        if (!$produk) {
            return redirect()->route('produk.index')->with('error', 'Produk tidak ditemukan.');
        }

        // Ambil Ulasan (Opsional)
        $ulasan = DB::table('tb_review_produk as r')
            ->join('tb_user as u', 'r.user_id', '=', 'u.id')
            ->select('r.*', 'u.nama as nama_user')
            ->where('r.barang_id', $id)
            ->orderByDesc('r.created_at')
            ->limit(5)
            ->get();

        return view('pages.detail_produk', compact('produk', 'ulasan'));
    }

    // =================================================================
    // 3. PROSES KERANJANG (Sementara)
    // =================================================================
    public function tambahKeranjang(Request $request)
    {
        // Logika simpan ke database akan kita buat di tahap selanjutnya
        // Untuk sekarang return text dulu agar tidak error 404/500
        return "Berhasil menambahkan Barang ID: " . $request->barang_id . " Sejumlah: " . $request->jumlah . " ke keranjang (Simulasi).";
    }

    // =================================================================
    // 4. HALAMAN LAINNYA (Placeholder)
    // =================================================================
    
    public function semuaToko() {
        return "Halaman Semua Toko (Sedang dibuat)";
        // Nanti: return view('pages.semua_toko');
    }

    public function detailToko(Request $request) {
        return "Halaman Profil Toko: " . $request->query('slug');
        // Nanti: return view('pages.detail_toko');
    }

    public function keranjang() {
        return "Halaman Keranjang (Sedang dibuat)";
        // Nanti: return view('pages.keranjang');
    }

    public function search(Request $request) {
        // Redirect ke halaman produk saja dengan parameter query
        return redirect()->route('produk.index', ['query' => $request->query('query')]);
    }
}