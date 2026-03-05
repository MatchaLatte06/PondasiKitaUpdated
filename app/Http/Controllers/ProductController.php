<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // =========================================================================
    // HALAMAN DETAIL PRODUK
    // =========================================================================
    public function detail($id)
    {
        // 1. Ambil Data Produk + Kategori + Toko + Kota
        $product = DB::table('tb_barang as p')
            ->leftJoin('tb_kategori as k', 'p.kategori_id', '=', 'k.id')
            ->join('tb_toko as t', 'p.toko_id', '=', 't.id')
            ->leftJoin('cities as c', 't.city_id', '=', 'c.id')
            ->select(
                'p.*',
                'k.nama_kategori',
                't.id AS toko_id', 't.nama_toko', 't.slug AS slug_toko', 't.logo_toko',
                'c.name as nama_kota_toko'
            )
            ->where('p.id', $id)
            ->where('p.is_active', 1)
            ->where('p.status_moderasi', 'approved')
            ->first();

        // Jika produk tidak ada / tidak aktif, lempar ke halaman 404
        if (!$product) {
            abort(404, 'Produk Tidak Ditemukan atau Tidak Aktif.');
        }

        // 2. Ambil Galeri Gambar
        $gallery_images = DB::table('tb_gambar_barang')
            ->where('barang_id', $id)
            ->orderByDesc('is_utama')
            ->orderBy('id')
            ->pluck('nama_file')
            ->toArray();

        // Fallback jika gambar kosong
        if (empty($gallery_images) && !empty($product->gambar_utama)) {
            $gallery_images[] = $product->gambar_utama;
        }
        if (empty($gallery_images)) {
            $gallery_images[] = 'default.jpg';
        }

        // 3. Ambil Produk Terkait dari Toko yang Sama
        $related_products = DB::table('tb_barang')
            ->where('toko_id', $product->toko_id)
            ->where('id', '!=', $id)
            ->where('is_active', 1)
            ->limit(5)
            ->get();

        // 4. Ambil Ulasan Produk
        $reviews = DB::table('tb_review_produk as r')
            ->join('tb_user as u', 'r.user_id', '=', 'u.id')
            ->where('r.barang_id', $id)
            ->select('r.*', 'u.nama AS username')
            ->orderByDesc('r.created_at')
            ->get();

        $jumlah_ulasan = $reviews->count();
        $avg_rating = $jumlah_ulasan > 0 ? $reviews->avg('rating') : 0;

        // 5. Visual Toko (Inisial & Warna)
        $storeColor = $this->getStoreColor($product->nama_toko);
        $storeInitials = $this->getStoreInitials($product->nama_toko);

        return view('pages.produk.detail', compact(
            'product', 'gallery_images', 'related_products',
            'reviews', 'jumlah_ulasan', 'avg_rating',
            'storeColor', 'storeInitials'
        ));
    }

    // --- HELPER FUNCTIONS ---
    private function getStoreInitials($nama_toko) {
        if (empty($nama_toko)) return "TK";
        $words = explode(" ", $nama_toko);
        $acronym = "";
        foreach ($words as $w) {
            $acronym .= mb_substr($w, 0, 1);
        }
        return strtoupper(substr($acronym, 0, 2));
    }

    private function getStoreColor($nama_toko) {
        $colors = ['#e53935', '#d81b60', '#8e24aa', '#5e35b1', '#3949ab', '#1e88e5', '#039be5', '#00acc1', '#00897b', '#43a047', '#7cb342', '#c0ca33', '#fdd835', '#ffb300', '#fb8c00', '#f4511e'];
        $index = crc32($nama_toko) % count($colors);
        return $colors[$index];
    }
}
