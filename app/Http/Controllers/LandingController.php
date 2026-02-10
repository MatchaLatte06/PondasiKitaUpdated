<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        // ==========================================
        // 1. DATA USER & LOKASI
        // ==========================================
        $user = Auth::user(); 
        $cityId = 0;
        $districtId = 0;
        $tokoSectionTitle = "Toko Populer Nasional";

        if ($user) {
            // Cek alamat utama user untuk personalisasi konten
            $alamatUtama = DB::table('tb_user_alamat')
                ->where('user_id', $user->id)
                ->where('is_utama', 1)
                ->first();

            if ($alamatUtama) {
                $cityId = $alamatUtama->city_id;
                $districtId = $alamatUtama->district_id;
            }
        }

        // ==========================================
        // 2. KATEGORI (Limit 8)
        // ==========================================
        $categories = DB::table('tb_kategori')->limit(8)->get();

        // ==========================================
        // 3. TOKO POPULER
        // ==========================================
        $queryToko = DB::table('tb_toko as t')
            ->join('cities as c', 't.city_id', '=', 'c.id')
            ->select('t.id', 't.nama_toko', 't.slug', 't.logo_toko', 't.banner_toko', 'c.name as kota')
            ->selectSub(function ($query) {
                // Subquery hitung jumlah produk aktif
                $query->from('tb_barang')
                    ->whereColumn('toko_id', 't.id')
                    ->where('is_active', 1)
                    ->where('status_moderasi', 'approved')
                    ->selectRaw('count(id)');
            }, 'jumlah_produk_aktif')
            ->where('t.status', 'active')
            ->where('t.status_operasional', 'Buka');

        // Filter Lokasi (Jika User Punya Alamat)
        if ($cityId > 0) {
            $tokoSectionTitle = "Toko di Wilayah Anda";
            $queryToko->where(function($q) use ($cityId, $districtId) {
                $q->where('t.city_id', $cityId)
                  ->orWhere('t.district_id', $districtId);
            });
            // Prioritaskan toko lokal yang produknya banyak
            $queryToko->orderByDesc('jumlah_produk_aktif')->orderBy('t.nama_toko');
        } else {
            $queryToko->orderByDesc('jumlah_produk_aktif');
        }

        $listToko = $queryToko->limit(4)->get();

        // Inject Data Tambahan (Warna & Inisial) untuk Tampilan
        foreach ($listToko as $toko) {
            $toko->initials = $this->getStoreInitials($toko->nama_toko);
            $toko->color = $this->getStoreColor($toko->nama_toko);
        }

        // ==========================================
        // 4. PRODUK TERLARIS (Lokal & Nasional)
        // ==========================================
        
        // Produk Lokal (Hanya jika ada lokasi user)
        $listProdukLokal = [];
        if ($cityId > 0) {
            $listProdukLokal = $this->getBestSellingProducts($cityId, $districtId);
        }

        // Produk Nasional
        $listProdukNasional = $this->getBestSellingProducts();

        // ==========================================
        // 5. RETURN VIEW
        // ==========================================
        return view('landing', compact(
            'categories', 
            'listToko', 
            'tokoSectionTitle', 
            'listProdukLokal', 
            'listProdukNasional',
            'user'
        ));
    }

    // --- PRIVATE HELPER FUNCTIONS ---

    /**
     * Mengambil produk terlaris berdasarkan jumlah terjual di detail transaksi
     */
    private function getBestSellingProducts($cityId = null, $districtId = null)
    {
        $query = DB::table('tb_barang as b')
            ->join('tb_toko as t', 'b.toko_id', '=', 't.id')
            ->select('b.id', 'b.nama_barang', 'b.harga', 'b.gambar_utama', 't.nama_toko', 't.slug as slug_toko')
            ->where('b.is_active', 1)
            ->where('b.status_moderasi', 'approved');

        // Filter Lokasi jika ada parameter
        if ($cityId) {
            $query->where(function($q) use ($cityId, $districtId) {
                $q->where('t.city_id', $cityId)
                  ->orWhere('t.district_id', $districtId);
            });
        }

        // Hitung total terjual untuk sorting (Menggunakan selectSub agar kompatibel semua DB)
        $query->selectSub(function ($q) {
            $q->from('tb_detail_transaksi')
              ->whereColumn('barang_id', 'b.id')
              ->selectRaw('COALESCE(SUM(jumlah), 0)');
        }, 'total_terjual');

        $query->orderByDesc('total_terjual');

        return $query->limit(8)->get();
    }

    /**
     * Membuat inisial nama toko (Contoh: "Sumber Jaya" -> "SJ")
     */
    private function getStoreInitials($nama)
    {
        if (empty($nama)) return "TK";
        $words = explode(" ", $nama);
        $acronym = "";
        foreach ($words as $w) {
            $acronym .= mb_substr($w, 0, 1);
        }
        return strtoupper(substr($acronym, 0, 2));
    }

    /**
     * Membuat warna acak yang konsisten berdasarkan nama toko
     */
    private function getStoreColor($nama)
    {
        $colors = ['#e53935', '#d81b60', '#8e24aa', '#5e35b1', '#3949ab', '#1e88e5', '#039be5', '#00acc1', '#00897b', '#43a047', '#7cb342', '#c0ca33', '#fdd835', '#ffb300', '#fb8c00', '#f4511e'];
        $index = crc32($nama) % count($colors);
        return $colors[$index];
    }
}