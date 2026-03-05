<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
   // =================================================================
    // 1. HALAMAN DAFTAR PRODUK (Katalog Utama Lengkap dengan Filter)
    // =================================================================
    public function produk(Request $request)
    {
        // A. AMBIL DATA FILTER
        $categories = DB::table('tb_kategori')->orderBy('nama_kategori', 'ASC')->get();

        $locations = DB::table('tb_toko as t')
            ->join('cities as c', 't.city_id', '=', 'c.id')
            ->select('t.city_id', 'c.name as nama_kota')
            ->where('t.status', 'active')
            ->whereNotNull('t.city_id')
            ->distinct()
            ->orderBy('c.name', 'ASC')
            ->get();

        // B. QUERY UTAMA BARANG
        $query = DB::table('tb_barang as b')
            ->join('tb_toko as t', 'b.toko_id', '=', 't.id')
            ->leftJoin('cities as c', 't.city_id', '=', 'c.id')
            ->select(
                'b.id', 'b.nama_barang', 'b.harga', 'b.gambar_utama', 'b.satuan_unit',
                't.nama_toko', 't.slug as toko_slug', 'c.name as nama_kota'
            )
            ->where('b.is_active', 1)
            ->where('b.status_moderasi', 'approved')
            ->where('t.status', 'active');

        // C. TERAPKAN FILTER
        
        // --- PERBAIKAN LOGIKA KATEGORI ---
        // Tangkap input, pastikan selalu menjadi Array agar in_array() di Blade tidak error
        $raw_kategori = $request->kategori;
        $filter_kategori = [];
        
        if (is_array($raw_kategori)) {
            $filter_kategori = $raw_kategori;
        } elseif (!empty($raw_kategori)) {
            $filter_kategori = [$raw_kategori]; // Ubah string "1" menjadi array ["1"]
        }

        // Terapkan ke Query Database
        if (!empty($filter_kategori)) {
            $query->whereIn('b.kategori_id', $filter_kategori);
        }
        // ---------------------------------

        if ($request->filled('lokasi')) {
            $query->where('t.city_id', $request->lokasi);
        }
        if ($request->filled('harga_min')) {
            $query->where('b.harga', '>=', $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $query->where('b.harga', '<=', $request->harga_max);
        }
        if ($request->filled('query')) {
            $keyword = '%' . $request->query('query') . '%';
            $query->where(function($q) use ($keyword) {
                $q->where('b.nama_barang', 'like', $keyword)
                  ->orWhere('t.nama_toko', 'like', $keyword);
            });
        }

        // D. EKSEKUSI (Pagination)
        $query->orderByDesc('b.created_at'); 
        $products = $query->paginate(12)->withQueryString();

        $filter_lokasi = $request->lokasi ?? '';
        $filter_harga_min = $request->harga_min ?? '';
        $filter_harga_max = $request->harga_max ?? '';

        return view('pages.produk', compact(
            'categories', 'locations', 'products', 
            'filter_kategori', 'filter_lokasi', 'filter_harga_min', 'filter_harga_max'
        ));
    }
    
    // =================================================================
    // 2. HALAMAN HASIL PENCARIAN (Search Bar & Filter Kategori)
    // =================================================================
    public function search(Request $request) 
    {
        // Tangkap parameter dari URL (?query=semen&kategori=1)
        $keyword = $request->input('query');
        $kategoriId = $request->input('kategori');

        // Ambil semua kategori untuk ditampilkan di Sidebar Filter Kiri
        $categories = DB::table('tb_kategori')->orderBy('nama_kategori', 'ASC')->get();

        // Mulai Query pencarian ke tabel barang
        $query = DB::table('tb_barang as b')
            ->join('tb_toko as t', 'b.toko_id', '=', 't.id')
            ->leftJoin('cities as c', 't.city_id', '=', 'c.id')
            ->select('b.id', 'b.nama_barang', 'b.harga', 'b.gambar_utama', 't.nama_toko', 't.slug as slug_toko', 'c.name as kota_toko')
            ->where('b.is_active', 1)
            ->where('b.status_moderasi', 'approved')
            ->where('t.status', 'active'); // Pastikan toko sedang aktif

        // 1. Filter Berdasarkan Kata Kunci (Nama Barang ATAU Nama Toko)
        if (!empty($keyword)) {
            $query->where(function($q) use ($keyword) {
                $q->where('b.nama_barang', 'like', '%' . $keyword . '%')
                  ->orWhere('t.nama_toko', 'like', '%' . $keyword . '%');
            });
        }

        // 2. Filter Berdasarkan Kategori (Jika user mengklik sidebar kategori)
        if (!empty($kategoriId)) {
            $query->where('b.kategori_id', $kategoriId);
        }

        // Eksekusi query dengan paginasi (12 produk per halaman)
        // ->appends() agar saat user klik "Next Page", kata kuncinya tidak hilang
        $products = $query->paginate(12)->appends($request->query());

        // Kirim data ke View khusus pencarian (search.blade.php)
        return view('pages.search', compact('products', 'categories', 'keyword', 'kategoriId'));
    }

    // =================================================================
    // 3. HALAMAN DETAIL PRODUK
    // =================================================================
    public function detailProduk(Request $request)
    {
        $id = $request->query('id');

        $produk = DB::table('tb_barang as b')
            ->join('tb_toko as t', 'b.toko_id', '=', 't.id')
            ->leftJoin('cities as c', 't.city_id', '=', 'c.id')
            ->select('b.*', 't.nama_toko', 't.slug as toko_slug', 'c.name as kota_toko', 't.logo_toko')
            ->where('b.id', $id)
            ->first();

        if (!$produk) {
            return redirect()->route('produk.index')->with('error', 'Produk tidak ditemukan.');
        }

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
    // 5. HALAMAN LAINNYA (Placeholder)
    // =================================================================

    public function semuaToko(Request $request) 
    {
        // 1. Tangkap filter lokasi dari URL (?lokasi=12)
        $filter_lokasi = $request->query('lokasi', 'semua');

        // 2. Ambil data kota yang ada tokonya (Untuk Dropdown Filter)
        $locations = DB::table('tb_toko as t')
            ->join('cities as c', 't.city_id', '=', 'c.id')
            ->select('t.city_id', 'c.name as city_name')
            ->where('t.status', 'active')
            ->whereNotNull('t.city_id')
            ->distinct()
            ->orderBy('c.name', 'ASC')
            ->get();

        // 3. Query Utama: Ambil daftar toko beserta jumlah produk & rating
        $query = DB::table('tb_toko as t')
            ->join('cities as c', 't.city_id', '=', 'c.id')
            ->select(
                't.id', 't.nama_toko', 't.slug', 't.deskripsi_toko', 
                't.logo_toko', 't.banner_toko', 't.city_id', 'c.name as city_name'
            )
            // Subquery: Hitung jumlah produk yang aktif & disetujui di toko ini
            ->selectSub(function ($q) {
                $q->from('tb_barang')
                  ->whereColumn('toko_id', 't.id')
                  ->where('is_active', 1)
                  ->where('status_moderasi', 'approved')
                  ->selectRaw('COUNT(id)');
            }, 'jumlah_produk')
            // Subquery: Hitung rata-rata rating toko ini
            ->selectSub(function ($q) {
                $q->from('tb_toko_review')
                  ->whereColumn('toko_id', 't.id')
                  ->selectRaw('COALESCE(AVG(rating), 0)');
            }, 'rating')
            ->where('t.status', 'active');

        // 4. Terapkan Filter Lokasi (Jika user memilih selain "Semua Kota")
        if ($filter_lokasi !== 'semua' && !empty($filter_lokasi)) {
            $query->where('t.city_id', $filter_lokasi);
        }

        // 5. Eksekusi Query dengan Paginasi (12 Toko per halaman)
        $query->orderBy('t.nama_toko', 'ASC');
        $stores = $query->paginate(12)->withQueryString();

        // 6. Return ke View
        return view('pages.semua_toko', compact('locations', 'stores', 'filter_lokasi'));
    }

   // =================================================================
    // 5. HALAMAN PROFIL TOKO (Katalog Toko)
    // =================================================================
    public function detailToko(Request $request) 
    {
        $slug = $request->query('slug');

        // 1. Ambil Data Toko beserta nama kotanya
        $toko = DB::table('tb_toko as t')
            ->leftJoin('cities as c', 't.city_id', '=', 'c.id')
            ->select('t.*', 'c.name as kota')
            ->where('t.slug', $slug)
            ->where('t.status', 'active')
            ->first();

        // Jika toko tidak ada atau tidak aktif, lempar error 404
        if (!$toko) {
            abort(404, 'Toko tidak ditemukan atau sedang tidak aktif.');
        }

        // 2. Generate Inisial dan Warna (Jika tidak punya logo)
        $colors = ['#e53935', '#d81b60', '#8e24aa', '#5e35b1', '#3949ab', '#1e88e5', '#039be5', '#00acc1', '#00897b', '#43a047', '#7cb342', '#c0ca33', '#fdd835', '#ffb300', '#fb8c00', '#f4511e'];
        $storeColor = $colors[crc32($toko->nama_toko) % count($colors)];
        
        $words = explode(" ", $toko->nama_toko);
        $acronym = "";
        foreach ($words as $w) { $acronym .= mb_substr($w, 0, 1); }
        $storeInitials = strtoupper(substr($acronym, 0, 2));
        if (empty($storeInitials)) { $storeInitials = "TK"; }

        // 3. Ambil Semua Produk Milik Toko Ini
        $products = DB::table('tb_barang as b')
            ->where('b.toko_id', $toko->id)
            ->where('b.is_active', 1)
            ->where('b.status_moderasi', 'approved')
            ->paginate(12);

        // 4. Return ke View
        return view('pages.detail_toko', compact('toko', 'products', 'storeColor', 'storeInitials'));
    }

    // =================================================================
    // HALAMAN KERANJANG
    // =================================================================
    public function keranjang() 
    {
        // LOGIKA 1: Jika belum login, kirim penanda ke view
        if (!Auth::check()) {
            return view('pages.keranjang', ['is_guest' => true]);
        }

        // Ambil data keranjang, JOIN dengan barang dan toko
        $cartItems = DB::table('tb_keranjang as k')
            ->join('tb_barang as b', 'k.barang_id', '=', 'b.id')
            ->join('tb_toko as t', 'b.toko_id', '=', 't.id')
            ->select(
                'k.id as cart_id', 'k.jumlah', 
                'b.id as barang_id', 'b.nama_barang', 'b.harga', 'b.gambar_utama', 'b.stok', 
                't.nama_toko', 't.id as toko_id'
            )
            ->where('k.user_id', Auth::id())
            ->orderBy('t.nama_toko', 'ASC')
            ->get();

        // Kelompokkan barang berdasarkan nama toko (Standar Marketplace)
        $groupedCart = $cartItems->groupBy('nama_toko');

        return view('pages.keranjang', compact('groupedCart', 'cartItems'));
    }

    // =================================================================
    // API KERANJANG (TAMBAH, UPDATE, HAPUS)
    // =================================================================
    
    public function tambahKeranjang(Request $request)
    {
        // LOGIKA 2: Cegah Guest menambahkan barang
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Silakan masuk (login) terlebih dahulu untuk menambah barang ke keranjang.'
            ], 401);
        }

        $userId = Auth::id();
        $barangId = $request->barang_id;
        $jumlah = $request->jumlah ?? 1;

        // Cek apakah barang sudah ada di keranjang user ini
        $existing = DB::table('tb_keranjang')
            ->where('user_id', $userId)
            ->where('barang_id', $barangId)
            ->first();

        if ($existing) {
            DB::table('tb_keranjang')->where('id', $existing->id)->update(['jumlah' => $existing->jumlah + $jumlah]);
        } else {
            DB::table('tb_keranjang')->insert([
                'user_id' => $userId,
                'barang_id' => $barangId,
                'jumlah' => $jumlah,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Barang berhasil ditambahkan!']);
    }

    public function updateKeranjang(Request $request)
    {
        if (!Auth::check()) return response()->json(['status' => 'error'], 401);

        DB::table('tb_keranjang')
            ->where('id', $request->cart_id)
            ->where('user_id', Auth::id())
            ->update(['jumlah' => $request->jumlah]);

        return response()->json(['status' => 'success']);
    }

    public function hapusKeranjang(Request $request)
    {
        if (!Auth::check()) return response()->json(['status' => 'error'], 401);

        DB::table('tb_keranjang')
            ->where('id', $request->cart_id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json(['status' => 'success']);
    }
    // =================================================================
    // 8. HALAMAN CHECKOUT
    // =================================================================
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk untuk melanjutkan checkout.');
        }

        $userId = Auth::id();
        $userEmail = Auth::user()->email ?? 'customer@example.com';

        // 1. Ambil Alamat Utama Profil
        $alamatUser = DB::table('tb_user_alamat as ua')
            ->leftJoin('provinces as p', 'ua.province_id', '=', 'p.id')
            ->leftJoin('cities as c', 'ua.city_id', '=', 'c.id')
            ->leftJoin('districts as d', 'ua.district_id', '=', 'd.id')
            ->select('ua.*', 'p.name as province_name', 'c.name as city_name', 'd.name as district_name')
            ->where('ua.user_id', $userId)
            ->where('ua.is_utama', 1)
            ->first();

        $isAlamatIncomplete = !$alamatUser || empty($alamatUser->nama_penerima) || empty($alamatUser->alamat_lengkap);

        // 2. Siapkan Keranjang / Produk Langsung
        $itemsPerToko = [];
        $totalProduk = 0;
        $isDirectPurchase = $request->has('product_id');

        if ($isDirectPurchase) {
            // BELI LANGSUNG (Tombol dari halaman Detail Produk)
            $productId = $request->input('product_id');
            $jumlah = $request->input('jumlah', 1);

            $item = DB::table('tb_barang as b')
                ->join('tb_toko as t', 'b.toko_id', '=', 't.id')
                ->leftJoin('cities as c', 't.city_id', '=', 'c.id')
                ->select('b.id as barang_id', 'b.nama_barang', 'b.harga', 'b.gambar_utama', 'b.stok', 't.id as toko_id', 't.nama_toko', 'c.name as kota_toko')
                ->where('b.id', $productId)
                ->first();

            if ($item) {
                $item->jumlah = $jumlah;
                $itemsPerToko[$item->toko_id] = [
                    'nama_toko' => $item->nama_toko, 'kota_toko' => $item->kota_toko, 'items' => [$item]
                ];
                $totalProduk += $item->harga * $jumlah;
            }
        } else {
            // DARI KERANJANG (Menangkap Array/String dari POST form keranjang)
            $selectedItems = $request->input('selected_items');
            
            // Format string "1,2,3" menjadi array [1,2,3]
            if (is_string($selectedItems)) {
                $selectedItems = explode(',', $selectedItems);
            }

            if (empty($selectedItems)) {
                return redirect()->route('keranjang.index')->with('error', 'Tidak ada barang yang dipilih untuk checkout.');
            }

            $items = DB::table('tb_keranjang as k')
                ->join('tb_barang as b', 'k.barang_id', '=', 'b.id')
                ->join('tb_toko as t', 'b.toko_id', '=', 't.id')
                ->leftJoin('cities as c', 't.city_id', '=', 'c.id')
                ->select('k.id as keranjang_id', 'b.id as barang_id', 'b.nama_barang', 'b.harga', 'b.gambar_utama', 'k.jumlah', 't.id as toko_id', 't.nama_toko', 'c.name as kota_toko')
                ->where('k.user_id', $userId)
                ->whereIn('k.id', $selectedItems)
                ->get();

            foreach ($items as $item) {
                if (!isset($itemsPerToko[$item->toko_id])) {
                    $itemsPerToko[$item->toko_id] = [
                        'nama_toko' => $item->nama_toko, 'kota_toko' => $item->kota_toko, 'items' => []
                    ];
                }
                $itemsPerToko[$item->toko_id]['items'][] = $item;
                $totalProduk += $item->harga * $item->jumlah;
            }
        }

        if (empty($itemsPerToko)) {
            return redirect()->route('keranjang.index')->with('error', 'Data produk tidak valid.');
        }

        return view('pages.checkout', compact('userEmail', 'alamatUser', 'isAlamatIncomplete', 'itemsPerToko', 'totalProduk', 'isDirectPurchase', 'request'));
    }

    public function prosesCheckout(Request $request)
    {
        // Fungsi placeholder untuk menyimpan order ke Database / Midtrans nantinya
        // Untuk sekarang kita lempar pesan berhasil saja
        return redirect()->route('pesanan.index')->with('success', 'Pesanan Anda berhasil dibuat! (Simulasi)');
    }
    // =================================================================
    // 9. HALAMAN PROFIL CUSTOMER
    // =================================================================
    public function profil()
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        // Ambil data user yang sedang login
        $user = Auth::user();

        // Ambil data alamat utama
        $alamatUtama = DB::table('tb_user_alamat as ua')
            ->leftJoin('districts as d', 'ua.district_id', '=', 'd.id')
            ->leftJoin('cities as c', 'ua.city_id', '=', 'c.id')
            ->leftJoin('provinces as p', 'ua.province_id', '=', 'p.id')
            ->select(
                'ua.alamat_lengkap', 'ua.kode_pos',
                'd.name as district_name', 'c.name as city_name', 'p.name as province_name'
            )
            ->where('ua.user_id', $user->id)
            ->where('ua.is_utama', 1)
            ->first();

        // Format alamat jika ada
        $alamatLengkapFormatted = '-';
        if ($alamatUtama) {
            $alamatLengkapFormatted = 
                $alamatUtama->alamat_lengkap . '<br>' .
                'Kec. ' . ($alamatUtama->district_name ?? 'Tidak Diketahui') . ', ' . 
                ($alamatUtama->city_name ?? 'Tidak Diketahui') . ',<br>' .
                ($alamatUtama->province_name ?? 'Tidak Diketahui') . 
                (!empty($alamatUtama->kode_pos) ? ', ' . $alamatUtama->kode_pos : '');
        }

        return view('pages.profil', compact('user', 'alamatLengkapFormatted'));
    }
   // =================================================================
    // 10. HALAMAN EDIT PROFIL & ALAMAT
    // =================================================================
    public function editProfil()
    {
        if (!Auth::check()) return redirect()->route('login');
        
        $user = Auth::user();
        
        // Ambil Data Alamat Utama
        $alamatUtama = DB::table('tb_user_alamat')
            ->where('user_id', $user->id)
            ->where('is_utama', 1)
            ->first();

        // Ambil Data Provinsi untuk Dropdown Pertama
        $provinces = DB::table('provinces')->orderBy('name', 'ASC')->get();
        
        // Ambil Kota & Kecamatan jika user sudah punya alamat (Untuk Selected Data)
        $cities = [];
        $districts = [];
        if ($alamatUtama && $alamatUtama->province_id) {
            $cities = DB::table('cities')->where('province_id', $alamatUtama->province_id)->orderBy('name', 'ASC')->get();
        }
        if ($alamatUtama && $alamatUtama->city_id) {
            $districts = DB::table('districts')->where('city_id', $alamatUtama->city_id)->orderBy('name', 'ASC')->get();
        }

        return view('pages.edit_profil', compact('user', 'alamatUtama', 'provinces', 'cities', 'districts'));
    }

    public function updateProfil(Request $request)
    {
        if (!Auth::check()) return redirect()->route('login');

        $user = Auth::user();

        // 1. Validasi Input (Data Diri & Alamat)
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Validasi Alamat
            'label_alamat' => 'nullable|string|max:50',
            'nama_penerima' => 'nullable|string|max:255',
            'telepon_penerima' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string',
            'province_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'kode_pos' => 'nullable|string|max:10',
        ]);

        // 2. Handle Upload Foto
        $namaFoto = $user->profile_picture_url; 

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/uploads/avatars'), $namaFoto);

            if ($user->profile_picture_url && $user->profile_picture_url != 'person.png') {
                $oldPath = public_path('assets/uploads/avatars/' . $user->profile_picture_url);
                if (file_exists($oldPath)) unlink($oldPath);
            }
        }

        // 3. Update Data Diri (tb_user)
        DB::table('tb_user')->where('id', $user->id)->update([
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'profile_picture_url' => $namaFoto,
            'updated_at' => now(),
        ]);

        // 4. Update atau Insert Alamat Utama (tb_user_alamat)
        if ($request->filled('alamat_lengkap')) {
            $cekAlamat = DB::table('tb_user_alamat')->where('user_id', $user->id)->where('is_utama', 1)->first();

            $dataAlamat = [
                'user_id' => $user->id,
                'label_alamat' => $request->label_alamat ?? 'Rumah',
                'nama_penerima' => $request->nama_penerima ?? $request->nama,
                'telepon_penerima' => $request->telepon_penerima ?? $request->no_telepon,
                'alamat_lengkap' => $request->alamat_lengkap,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'district_id' => $request->district_id,
                'kode_pos' => $request->kode_pos,
                'is_utama' => 1,
            ];

            if ($cekAlamat) {
                DB::table('tb_user_alamat')->where('id', $cekAlamat->id)->update($dataAlamat);
            } else {
                DB::table('tb_user_alamat')->insert($dataAlamat);
            }
        }

        return redirect()->route('profil.index')->with('success', 'Profil dan Alamat berhasil diperbarui!');
    }
    // =================================================================
    // 11. STATUS PESANAN SAYA
    // =================================================================
    public function pesanan()
    {
        if (!Auth::check()) return redirect()->route('login');

        $orders =DB::table('tb_transaksi')
            ->where('user_id', Auth::id())
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        return view('pages.pesanan_index', compact('orders'));
    }

    // =================================================================
    // 12. LACAK PENGIRIMAN (DETAIL PESANAN)
    // =================================================================
    public function lacakPesanan($kode_pesanan)
    {
        if (!Auth::check()) return redirect()->route('login');

        // Ambil data pesanan
        $order = DB::table('tb_transaksi')
            ->where('user_id', Auth::id())
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        if (!$order) abort(404);

        // Simulasi History Perjalanan (Nantinya data ini dari tabel tb_pesanan_log)
        // Kita buat dummy data untuk tampilan modern
        $trackingLogs = [
            ['status' => 'Selesai', 'desc' => 'Pesanan telah diterima oleh pembeli', 'time' => '05 Mar 2026 14:00'],
            ['status' => 'Dikirim', 'desc' => 'Pesanan sedang dibawa oleh kurir', 'time' => '04 Mar 2026 09:00'],
            ['status' => 'Diproses', 'desc' => 'Penjual sedang menyiapkan barang', 'time' => '03 Mar 2026 10:30'],
            ['status' => 'Menunggu', 'desc' => 'Pesanan berhasil dibuat', 'time' => '03 Mar 2026 08:00'],
        ];

        return view('pages.pesanan_lacak', compact('order', 'trackingLogs'));
    }
}