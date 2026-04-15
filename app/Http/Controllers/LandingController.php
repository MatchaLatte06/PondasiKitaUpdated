<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function index()
    {
        // ==========================================
        // 0. PENGATURAN SISTEM & WEBSITE
        // ==========================================
        // Mengambil semua pengaturan dari tb_pengaturan agar bisa dipakai di Blade
        $settingsData = DB::table('tb_pengaturan')->get();
        $settings = [];
        foreach ($settingsData as $s) {
            $settings[$s->setting_nama] = $s->setting_nilai;
        }

        // ==========================================
        // 1. DATA USER & LOKASI
        // ==========================================
        $user = Auth::user();
        $cityId = 0;
        $districtId = 0;
        $tokoSectionTitle = "Mitra Toko Populer";

        if ($user) {
            // Cek alamat utama user untuk personalisasi konten (Rekomendasi Terdekat)
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
        // 2. DYNAMIC BANNER HERO
        // ==========================================
        // Data dummy berbentuk Object. Jika Bos sudah buat CRUD Banner di Admin,
        // ganti ini dengan: $promoBanners = DB::table('tb_banners')->where('is_active', 1)->get();
        $promoBanners = [
            (object)[
                'title' => 'Pekan Diskon Baja',
                'desc' => 'Dapatkan potongan harga khusus untuk pembelian baja ringan volume besar minggu ini.',
                'img' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=1000&auto=format&fit=crop',
                'link' => '#'
            ],
            (object)[
                'title' => 'Gratis Ongkir Se-Jawa',
                'desc' => 'Subsidi ongkos kirim hingga Rp500.000 untuk minimal transaksi 50 Juta.',
                'img' => 'https://images.unsplash.com/photo-1587293852726-70cdb56c2866?q=80&w=1000&auto=format&fit=crop',
                'link' => '#'
            ],
            (object)[
                'title' => 'Mitra Baru: Semen Tiga Roda',
                'desc' => 'Kini tersedia semen kualitas premium langsung dari pabrik dengan harga termurah.',
                'img' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=1000&auto=format&fit=crop',
                'link' => '#'
            ]
        ];

        // ==========================================
        // 3. KATEGORI UTAMA
        // ==========================================
        // Hanya ambil kategori utama (parent_id kosong) agar tidak nyampur sama sub-kategori
        $kategoriUtama = DB::table('tb_kategori')->whereNull('parent_id')->get();
        $kategoriAnak = DB::table('tb_kategori')->whereNotNull('parent_id')->get();

        // Menyisipkan anak ke dalam induknya masing-masing
        foreach ($kategoriUtama as $utama) {
            $utama->subkategori = $kategoriAnak->where('parent_id', $utama->id)->values();
        }

        $categories = $kategoriUtama;

        // ==========================================
        // 4. TOKO POPULER
        // ==========================================
        $queryToko = DB::table('tb_toko as t')
            ->join('cities as c', 't.city_id', '=', 'c.id')
            // PERBAIKAN: Menambahkan t.tier_toko ke dalam select
            ->select('t.id', 't.nama_toko', 't.slug', 't.logo_toko', 't.banner_toko', 't.tier_toko', 'c.name as kota')
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
            $tokoSectionTitle = "Toko Terdekat di Wilayah Anda";
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
        // 5. PRODUK TERLARIS (Lokal & Nasional)
        // ==========================================

        // Produk Lokal (Hanya jika ada lokasi user)
        $listProdukLokal = [];
        if ($cityId > 0) {
            $listProdukLokal = $this->getBestSellingProducts($cityId, $districtId);
        }

        // Produk Nasional
        $listProdukNasional = $this->getBestSellingProducts();

        // ==========================================
        // 6. FLASH SALE (Integrasi)
        // ==========================================
        $flashSaleEvent = DB::table('tb_flash_sale_events')
            ->where('is_active', 1)
            ->where('tanggal_mulai', '<=', Carbon::now())
            ->where('tanggal_berakhir', '>=', Carbon::now())
            ->first();

        $flashSaleProducts = [];
        $flashSaleEndTime = null;

        if ($flashSaleEvent) {
            $flashSaleEndTime = $flashSaleEvent->tanggal_berakhir;
            $flashSaleProducts = DB::table('tb_flash_sale_produk as fsp')
                ->join('tb_barang as b', 'fsp.barang_id', '=', 'b.id')
                ->select('b.id', 'b.nama_barang', 'b.harga', 'b.gambar_utama', 'fsp.harga_flash_sale', 'fsp.stok_flash_sale')
                ->where('fsp.event_id', $flashSaleEvent->id)
                ->where('fsp.status_moderasi', 'approved')
                ->where('b.is_active', 1)
                ->limit(10)->get();
        }

        // ==========================================
        // 7. RETURN VIEW
        // ==========================================
        return view('landing', compact(
            'settings', // Data Pengaturan Website
            'promoBanners',
            'categories',
            'listToko',
            'tokoSectionTitle',
            'listProdukLokal',
            'listProdukNasional',
            'flashSaleProducts', // Data Produk Flash Sale
            'flashSaleEndTime',  // Waktu Habis Flash Sale
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
            ->leftJoin('cities as c', 't.city_id', '=', 'c.id')
            ->select(
                'b.id', 'b.nama_barang', 'b.harga', 'b.gambar_utama', 'b.tipe_diskon', 'b.nilai_diskon',
                't.nama_toko', 't.slug as slug_toko', 'c.name as kota_toko'
            )
            ->where('b.is_active', 1)
            ->where('b.status_moderasi', 'approved');

        // Filter Lokasi jika ada parameter
        if ($cityId) {
            $query->where(function($q) use ($cityId, $districtId) {
                $q->where('t.city_id', $cityId)
                  ->orWhere('t.district_id', $districtId);
            });
        }

        // PERBAIKAN: Hitung total terjual dialiaskan sebagai stok_terjual agar dibaca oleh Blade
        $query->selectSub(function ($q) {
            $q->from('tb_detail_transaksi')
              ->whereColumn('barang_id', 'b.id')
              ->where('status_pesanan_item', 'sampai_tujuan') // Hitung yg sudah selesai
              ->selectRaw('COALESCE(SUM(jumlah), 0)');
        }, 'stok_terjual');

        $query->orderByDesc('stok_terjual');

        return $query->limit(10)->get(); // Limit 10 untuk grid
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
