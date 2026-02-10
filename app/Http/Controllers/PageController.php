<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function produk() {
        return "Halaman Produk (Sedang dibuat)";
        // Nanti ganti jadi: return view('pages.produk');
    }

    public function semuaToko() {
        return "Halaman Semua Toko (Sedang dibuat)";
    }

    public function detailProduk() {
        return "Halaman Detail Produk (Sedang dibuat)";
    }

    public function detailToko() {
        return "Halaman Profil Toko (Sedang dibuat)";
    }

    public function keranjang() {
        return "Halaman Keranjang (Sedang dibuat)";
    }

    public function search(Request $request) {
        return "Hasil Pencarian: " . $request->query('query');
    }
}