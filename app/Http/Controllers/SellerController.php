<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    // =========================================================================
    // 1. HALAMAN DASHBOARD SELLER
    // =========================================================================
    public function index()
    {
        $user = Auth::user();
        
        // 1. Ambil Data Toko dari User yang login
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();

        if (!$toko) {
            return redirect()->route('home')->with('error', 'Anda belum memiliki toko.');
        }

        $tokoId = $toko->id;

        // 2. Total Penjualan (Omset)
        $totalPenjualan = DB::table('tb_detail_transaksi')
            ->where('toko_id', $tokoId)
            ->sum('subtotal');

        // 3. Pesanan Diterima (Count Transaksi Unik)
        $totalPesanan = DB::table('tb_detail_transaksi')
            ->where('toko_id', $tokoId)
            ->distinct('transaksi_id')
            ->count('transaksi_id');

        // 4. Total Item Terjual
        $totalItemTerjual = DB::table('tb_detail_transaksi')
            ->where('toko_id', $tokoId)
            ->sum('jumlah');

        // 5. Total Produk Aktif
        $totalProdukAktif = DB::table('tb_barang')
            ->where('toko_id', $tokoId)
            ->where('is_active', 1)
            ->count();

        // 6. Grafik Penjualan Bulanan (Tahun Ini)
        $tahunSekarang = date('Y');
        $penjualanTahunan = array_fill(1, 12, 0); // Array bulan 1-12 isi 0

        $dataGrafik = DB::table('tb_detail_transaksi as d')
            ->join('tb_transaksi as t', 'd.transaksi_id', '=', 't.id')
            ->selectRaw('MONTH(t.tanggal_transaksi) as bulan, SUM(d.subtotal) as total')
            ->where('d.toko_id', $tokoId)
            ->whereYear('t.tanggal_transaksi', $tahunSekarang)
            ->groupBy('bulan')
            ->get();

        foreach ($dataGrafik as $data) {
            $penjualanTahunan[$data->bulan] = (float) $data->total;
        }

        $labelsBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        // 7. Top 5 Produk Terlaris
        $topProduk = DB::table('tb_detail_transaksi as d')
            ->join('tb_barang as b', 'd.barang_id', '=', 'b.id')
            ->select('b.nama_barang', DB::raw('SUM(d.jumlah) as total_terjual'))
            ->where('d.toko_id', $tokoId)
            ->groupBy('d.barang_id', 'b.nama_barang') 
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $topProdukLabels = $topProduk->pluck('nama_barang');
        $topProdukData = $topProduk->pluck('total_terjual');

        // Kirim semua data ke View Dashboard
        return view('seller.dashboard', compact(
            'toko',
            'totalPenjualan',
            'totalPesanan',
            'totalItemTerjual',
            'totalProdukAktif',
            'labelsBulan',
            'penjualanTahunan',
            'topProdukLabels',
            'topProdukData',
            'tahunSekarang'
        ));
    }

    // =========================================================================
    // 2. HALAMAN MANAJEMEN PESANAN MASUK
    // =========================================================================
    public function pesanan(Request $request)
    {
        $user = Auth::user();
        
        // 1. Ambil Data Toko
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // 2. Query Pengambilan Data Pesanan Khusus Toko Ini
        // Menggabungkan tb_detail_transaksi, tb_transaksi, tb_barang, dan tb_user
        $pesananRaw = DB::table('tb_detail_transaksi as d')
            ->join('tb_transaksi as t', 'd.transaksi_id', '=', 't.id')
            ->join('tb_barang as b', 'd.barang_id', '=', 'b.id')
            ->join('tb_user as u', 't.user_id', '=', 'u.id') // Untuk ambil nama pelanggan
            ->where('d.toko_id', $toko->id)
            ->select(
                'd.id as detail_id', 'd.jumlah', 'd.subtotal', 'd.status_pesanan_item', 
                't.kode_invoice', 't.tanggal_transaksi',
                'b.nama_barang', 'b.gambar_utama', 
                'u.nama as nama_pelanggan'
            )
            // Urutkan status 'siap_kirim' & 'diproses' di paling atas
            ->orderByRaw("FIELD(d.status_pesanan_item, 'siap_kirim', 'diproses', 'dikirim', 'sampai_tujuan', 'dibatalkan', 'ditolak') DESC")
            ->orderBy('t.tanggal_transaksi', 'desc')
            ->get();

        // 3. Grouping data berdasarkan kode_invoice 
        // Fitur bawaan Laravel Collection agar per-invoice bisa digabungkan jadi satu tabel
        $groupedOrders = $pesananRaw->groupBy('kode_invoice');

        // Kirim data Map Status untuk Tabs (Pilihan status di UI)
        $statusMap = [
            'Semua' => 'Semua',
            'Belum Bayar' => 'menunggu_pembayaran',
            'Perlu Diproses' => 'diproses',
            'Siap Kirim' => 'siap_kirim',
            'Dikirim' => 'dikirim',
            'Selesai' => 'sampai_tujuan',
            'Dibatalkan' => 'dibatalkan'
        ];
        
        $currentFilter = $request->query('status', '');

        // Kirim ke View Pesanan
        return view('seller.pesanan', compact('groupedOrders', 'statusMap', 'currentFilter'));
    }
    // Memperbarui status pesanan SATUAN (Tombol Perbarui)
    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'detail_id' => 'required|integer',
            'status_baru' => 'required|string'
        ]);

        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        // Update status di database
        DB::table('tb_detail_transaksi')
            ->where('id', $request->detail_id)
            ->where('toko_id', $toko->id) // Keamanan: Pastikan pesanan milik toko ini
            ->update(['status_pesanan_item' => $request->status_baru]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // Memperbarui status pesanan MASSAL (Tombol Proses Pengiriman Massal)
    public function massUpdateOrderStatus(Request $request)
    {
        if (!$request->has('detail_ids') || empty($request->detail_ids)) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu pesanan untuk diproses.');
        }

        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        // Update semua ID yang diceklis menjadi 'dikirim'
        DB::table('tb_detail_transaksi')
            ->whereIn('id', $request->detail_ids)
            ->where('toko_id', $toko->id)
            ->update(['status_pesanan_item' => 'dikirim']);

        return redirect()->back()->with('success', count($request->detail_ids) . ' Pesanan berhasil diproses ke pengiriman!');
    }
    // =========================================================================
    // 3. HALAMAN PENGEMBALIAN PESANAN (RETURN/REFUND)
    // =========================================================================
    public function pengembalian(Request $request)
    {
        $user = Auth::user();
        
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // TODO: Ganti dengan Query Database Asli Anda (Tabel tb_pengembalian / tb_komplain)
        // Contoh Data Dummy untuk menampilkan UI
        $returns = [
            (object)[
                'id_return' => 'RET-001',
                'kode_invoice' => 'INV/202310/001',
                'tanggal_pengajuan' => '2023-10-25 14:30:00',
                'nama_pelanggan' => 'Budi Santoso',
                'nama_barang' => 'Sepatu Sneakers Pria Hitam',
                'gambar_utama' => 'default.jpg',
                'jumlah' => 1,
                'total_pengembalian' => 250000,
                'alasan' => 'Barang cacat / sobek di bagian samping.',
                'bukti_foto' => 'default.jpg',
                'status' => 'menunggu_respon' // menunggu_respon, disetujui, ditolak
            ],
            (object)[
                'id_return' => 'RET-002',
                'kode_invoice' => 'INV/202310/088',
                'tanggal_pengajuan' => '2023-10-24 09:15:00',
                'nama_pelanggan' => 'Siti Aminah',
                'nama_barang' => 'Tas Selempang Wanita',
                'gambar_utama' => 'default.jpg',
                'jumlah' => 2,
                'total_pengembalian' => 300000,
                'alasan' => 'Warna tidak sesuai dengan yang di foto (Minta hitam, dikirim navy).',
                'bukti_foto' => 'default.jpg',
                'status' => 'disetujui'
            ]
        ];

        $currentFilter = $request->query('status', '');

        return view('seller.pengembalian', compact('returns', 'currentFilter'));
    }
    // =========================================================================
    // 4. PENGATURAN PENGIRIMAN
    // =========================================================================
    
    // Menampilkan halaman pengaturan pengiriman
    public function pengaturanPengiriman()
    {
        $user = Auth::user();
        
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // Ambil data kurir dan urutkan
        $kurirList = DB::table('tb_kurir_toko')
            ->where('toko_id', $toko->id)
            ->orderBy('tipe_kurir')
            ->orderBy('nama_kurir')
            ->get();

        $tipeOrder = [
            'REGULAR' => 'Reguler (Cashless)',
            'HEMAT' => 'Hemat',
            'KARGO' => 'Kargo',
            'INSTANT' => 'Instan',
            'SAME_DAY' => 'Same Day',
            'NEXT_DAY' => 'Next Day',
            'AMBIL_DI_TEMPAT' => 'Ambil di Tempat',
            'PILIHAN_LAINNYA' => 'Pilihan Jasa Kirim Lainnya'
        ];

        // Kelompokkan kurir berdasarkan tipe
        $groupedKurir = [];
        foreach ($kurirList as $kurir) {
            $groupedKurir[$kurir->tipe_kurir][] = $kurir;
        }

        return view('seller.pengaturan_pengiriman', compact('groupedKurir', 'tipeOrder'));
    }

    // Menyimpan atau Mengupdate Data Kurir
    public function storePengiriman(Request $request)
    {
        $request->validate([
            'nama_kurir' => 'required|string|max:100',
            'tipe_kurir' => 'required|string',
            'estimasi_waktu' => 'required|string|max:50',
            'biaya' => 'required|numeric|min:0',
        ]);

        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        $data = [
            'toko_id' => $toko->id,
            'nama_kurir' => $request->nama_kurir,
            'tipe_kurir' => $request->tipe_kurir,
            'estimasi_waktu' => $request->estimasi_waktu,
            'biaya' => $request->biaya,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->action == 'update' && $request->kurir_id) {
            // Proses Edit
            DB::table('tb_kurir_toko')
                ->where('id', $request->kurir_id)
                ->where('toko_id', $toko->id) // Security check
                ->update($data);
            $msg = 'Layanan pengiriman berhasil diperbarui.';
        } else {
            // Proses Tambah Baru
            DB::table('tb_kurir_toko')->insert($data);
            $msg = 'Layanan pengiriman berhasil ditambahkan.';
        }

        return redirect()->route('seller.pengaturan.pengiriman')->with('success', $msg);
    }

    // Mengubah Status Aktif/Nonaktif Kurir via AJAX
    public function togglePengiriman(Request $request)
    {
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        $updated = DB::table('tb_kurir_toko')
            ->where('id', $request->kurir_id)
            ->where('toko_id', $toko->id)
            ->update(['is_active' => $request->is_active]);

        if($updated) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error'], 400);
    }

    // Menghapus Kurir
    public function destroyPengiriman($id)
    {
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        DB::table('tb_kurir_toko')
            ->where('id', $id)
            ->where('toko_id', $toko->id)
            ->delete();

        return redirect()->route('seller.pengaturan.pengiriman')->with('success', 'Layanan pengiriman berhasil dihapus.');
    }
    // =========================================================================
    // 5. PUSAT PROMOSI
    // =========================================================================
    public function promosi()
    {
        // TODO: Nanti tambahkan query untuk mengambil daftar promo toko dari database
        $promosi_list = []; // Dibuat array kosong dulu untuk memunculkan Empty State

        return view('seller.promosi', compact('promosi_list'));
    }
    // =========================================================================
    // 6. HALAMAN VOUCHER TOKO
    // =========================================================================
    public function voucher()
    {
        // TODO: Ganti dengan query pemanggilan data voucher dari database
        $voucher_list = []; // Kosongkan dulu untuk menampilkan Empty State

        return view('seller.voucher', compact('voucher_list'));
    }

    // =========================================================================
    // 7. MANAJEMEN CHAT
    // =========================================================================
    
    // Menampilkan UI Halaman Chat
    public function chat()
    {
        return view('seller.chat');
    }

    // [API] Mendapatkan daftar percakapan (List Kiri)
    public function getChatList()
    {
        // TODO: Ganti dengan Query Tabel Chat Asli Anda
        // Ini adalah DUMMY RESPONSE agar UI bisa ditesting
        $dummyChats = [
            ['id' => 1, 'nama_pelanggan' => 'Budi Santoso', 'last_message' => 'Apakah barang ini ready?'],
            ['id' => 2, 'nama_pelanggan' => 'Siti Aminah', 'last_message' => 'Terima kasih, paket sudah sampai.'],
            ['id' => 3, 'nama_pelanggan' => 'Ahmad Reza', 'last_message' => 'Bisa dikirim hari ini kak?']
        ];

        return response()->json([
            'status' => 'success',
            'data' => $dummyChats
        ]);
    }

    // [API] Mendapatkan isi pesan dari satu chat
    public function getMessages($chatId)
    {
        // TODO: Ganti dengan Query Tabel Pesan Asli Anda berdasarkan $chatId
        $userId = Auth::id(); // ID Seller
        
        // DUMMY RESPONSE
        $dummyMessages = [];
        if($chatId == 1) {
            $dummyMessages = [
                ['sender_id' => 999, 'message_text' => 'Apakah barang ini ready?'], // 999 = Customer
                ['sender_id' => $userId, 'message_text' => 'Ready kak, silakan diorder ya.'], // Seller
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => array_reverse($dummyMessages) // Di-reverse karena UI chat biasanya bottom-up
        ]);
    }

    // [API] Mengirim pesan baru
    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required',
            'message_text' => 'required|string'
        ]);

        // TODO: Insert data ke tabel pesan database Anda
        // DB::table('tb_chat_messages')->insert([
        //     'chat_id' => $request->chat_id,
        //     'sender_id' => Auth::id(),
        //     'message_text' => $request->message_text,
        //     'created_at' => now()
        // ]);

        return response()->json(['status' => 'success']);
    }

    // =========================================================================
    // 8. POINT OF SALE (KASIR)
    // =========================================================================
    
    public function pos()
    {
        // Halaman POS biasanya butuh layar penuh, kita kirim variabel penanda
        return view('seller.pos');
    }

    // API: Ambil Data Produk Toko
    public function getPosProducts()
    {
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();
        
        $products = DB::table('tb_barang')
            ->where('toko_id', $toko->id)
            ->select('id', 'nama_barang', 'harga', 'stok', 'gambar_utama', 'kategori_id')
            ->where('stok', '>', 0) // Hanya tampilkan yang ada stoknya
            ->get();

        return response()->json($products);
    }

    // API: Ambil Data Kategori Toko
    public function getPosCategories()
    {
        // Ambil kategori yang HANYA dimiliki oleh produk di toko ini
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        $categories = DB::table('tb_kategori')
            ->whereIn('id', function($query) use ($toko) {
                $query->select('kategori_id')
                      ->from('tb_barang')
                      ->where('toko_id', $toko->id);
            })->get();

        return response()->json($categories);
    }

    // API: Proses Transaksi Kasir
    public function processPosCheckout(Request $request)
    {
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();
        
        // 1. Buat Data Transaksi Induk
        $invoice = 'POS/' . date('Ymd') . '/' . strtoupper(uniqid());
        $transaksiId = DB::table('tb_transaksi')->insertGetId([
            'kode_invoice' => $invoice,
            'user_id' => Auth::id(), // Atau biarkan null jika pembeli offline
            'total_harga' => $request->total,
            'metode_pembayaran' => $request->payment_method,
            'status_transaksi' => 'lunas',
            'tanggal_transaksi' => now(),
            'catatan' => 'Pelanggan: ' . ($request->customer_name ?? 'Offline')
        ]);

        // 2. Looping Keranjang dan Insert Detail Transaksi
        foreach ($request->cart as $item) {
            DB::table('tb_detail_transaksi')->insert([
                'transaksi_id' => $transaksiId,
                'toko_id' => $toko->id,
                'barang_id' => $item['id'],
                'jumlah' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['harga'] * $item['qty'],
                'status_pesanan_item' => 'sampai_tujuan' // Langsung selesai karena dibeli di tempat
            ]);

            // 3. Kurangi Stok Barang
            DB::table('tb_barang')->where('id', $item['id'])->decrement('stok', $item['qty']);
        }

        return response()->json(['status' => 'success', 'message' => 'Transaksi berhasil!', 'invoice' => $invoice]);
    }

    // =========================================================================
    // 9. PENILAIAN TOKO (REVIEWS)
    // =========================================================================
    
    public function reviews(Request $request)
    {
        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // 1. Ambil data ringkasan rating
        $summary = DB::table('tb_toko_review')
            ->where('toko_id', $toko->id)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(id) as total_reviews')
            ->first();

        // Placeholder data performa
        $performa = [
            'chat_response_rate' => "95%",
            'chat_response_time' => "≈ 1 jam",
            'cancellation_rate' => "0.5%",
            'late_shipment_rate' => "1.2%"
        ];

        // 2. Query Detail Review (Sama seperti native, tapi pakai query builder Laravel)
        // Ambil filter bintang dari request (contoh: ?star=5)
        $starFilter = $request->query('star', 'all');

        $query = DB::table('tb_toko_review as r')
            ->join('tb_user as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('tb_detail_transaksi as dt', function($join) {
                $join->on('r.transaksi_id', '=', 'dt.transaksi_id')
                     ->on('r.toko_id', '=', 'dt.toko_id');
            })
            ->leftJoin('tb_barang as b', 'dt.barang_id', '=', 'b.id')
            ->where('r.toko_id', $toko->id)
            ->select(
                'r.id', 'r.rating', 'r.ulasan', 'r.balasan_penjual', 'r.created_at',
                'u.nama as nama_user',
                DB::raw('ANY_VALUE(b.nama_barang) as nama_barang'),
                DB::raw('ANY_VALUE(b.gambar_utama) as gambar_barang')
            )
            ->groupBy('r.id', 'r.rating', 'r.ulasan', 'r.balasan_penjual', 'r.created_at', 'u.nama')
            ->orderBy('r.created_at', 'desc');

        // Jika ada filter bintang
        if ($starFilter !== 'all' && is_numeric($starFilter)) {
            $query->where('r.rating', $starFilter);
        }

        $reviews = $query->get();

        return view('seller.reviews', compact('summary', 'performa', 'reviews', 'starFilter'));
    }

    // Proses membalas ulasan
    public function replyReview(Request $request)
    {
        $request->validate([
            'review_id' => 'required|integer',
            'balasan' => 'required|string|max:500'
        ]);

        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        // Pastikan review yang dibalas benar-benar milik toko ini
        DB::table('tb_toko_review')
            ->where('id', $request->review_id)
            ->where('toko_id', $toko->id)
            ->update([
                'balasan_penjual' => $request->balasan,
                'updated_at' => now() // Opsional jika Anda punya kolom ini
            ]);

        return redirect()->route('seller.service.reviews')->with('success', 'Balasan berhasil dikirim.');
    }

    // =========================================================================
    // 10. PENGHASILAN TOKO (FINANCE)
    // =========================================================================
    
    public function income()
    {
        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // TODO: Ganti dengan Query Database Asli Anda
        // Data Placeholder
        $penghasilan_pending = 0;
        $penghasilan_dilepas = 52459320;
        $dilepas_minggu_ini = 4500000;
        $dilepas_bulan_ini = 15230000;

        // Placeholder untuk daftar transaksi
        $transaksi_dilepas = []; // Nanti diisi query tabel transaksi

        return view('seller.income', compact(
            'penghasilan_pending', 
            'penghasilan_dilepas', 
            'dilepas_minggu_ini', 
            'dilepas_bulan_ini', 
            'transaksi_dilepas'
        ));
    }

    // =========================================================================
    // 11. REKENING BANK
    // =========================================================================
    
    public function bank()
    {
        $user = Auth::user();
        
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // TODO: Ganti dengan Query Database Asli (Tabel Rekening Toko)
        // Data Placeholder rekening yang sudah tersimpan
        $rekening_tersimpan = [
            (object)[
                'id' => 1,
                'nama_bank' => 'BCA',
                'no_rekening' => '**** **** **** 1234',
                'nama_pemilik' => 'Prabu A. T. S.',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia_logo.svg'
            ]
        ];

        // Daftar bank untuk dropdown TomSelect
        $daftar_bank = ['SeaBank', 'BCA', 'BRI', 'BNI', 'Bank Mandiri', 'Bank Raya Indonesia', 'CIMB Niaga', 'Bank Syariah Indonesia'];

        return view('seller.bank', compact('rekening_tersimpan', 'daftar_bank'));
    }
    
    // =========================================================================
    // 12. DATA PERFORMA TOKO (STATISTIK GRAFIK)
    // =========================================================================
    
    public function performance()
    {
        $user = Auth::user();
        
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // TODO: Ganti dengan Query Database Asli (Tabel Transaksi, dll)
        // Data Placeholder untuk Grafik & Analitik
        $kriteria = [
            'penjualan' => ['nilai' => 8500000, 'perbandingan' => 15.2],
            'pesanan' => ['nilai' => 120, 'perbandingan' => 5.1],
            'tingkat_konversi' => ['nilai' => 1.75, 'perbandingan' => 0.2],
            'pengunjung' => ['nilai' => 6857, 'perbandingan' => 12.8]
        ];

        $chart_labels = ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'];
        
        $chart_data = [
            'penjualan' => [150000, 200000, 180000, 500000, 1200000, 2500000, 3500000, 8500000],
            'pesanan' => [5, 8, 7, 15, 30, 40, 80, 120],
            'pengunjung' => [200, 250, 230, 800, 1500, 2500, 4500, 6857]
        ];

        $saluran = [
            'halaman_produk' => ['nilai' => 7500000, 'perbandingan' => 12.1],
            'live' => ['nilai' => 850000, 'perbandingan' => -5.5],
            'video' => ['nilai' => 150000, 'perbandingan' => 20.3]
        ];

        $pembeli = [
            'pembeli_saat_ini_persen' => 68,
            'total_pembeli' => 85,
            'pembeli_baru' => 58,
            'potensi_pembeli' => 1205,
            'tingkat_pembeli_berulang' => 31.7
        ];
        
        $pembeli_donut_chart = ['baru' => 58, 'berulang' => 27];

        return view('seller.performance', compact(
            'kriteria', 
            'chart_labels', 
            'chart_data', 
            'saluran', 
            'pembeli', 
            'pembeli_donut_chart'
        ));
    }

    // =========================================================================
    // 13. KESEHATAN TOKO
    // =========================================================================
    
    public function health()
    {
        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // TODO: Ganti dengan Query Database Asli (Tabel Penalti/Performa)
        $status_kesehatan = "Sangat baik";
        
        $top_summary = [
            'pesanan_terselesaikan' => 0,
            'produk_dilarang' => 0,
            'pelayanan_pembeli' => 0
        ];

        $metrics = [
            'Pesanan Terselesaikan' => [
                ['nama' => 'Tingkat Pesanan Tidak Terselesaikan', 'sekarang' => '0.00%', 'target' => '<10.00%', 'sebelumnya' => '0.00%'],
                ['nama' => 'Tingkat Keterlambatan Pengiriman', 'sekarang' => '0.00%', 'target' => '<10.00%', 'sebelumnya' => '0.00%'],
                ['nama' => 'Masa Pengemasan', 'sekarang' => '0.00 hari', 'target' => '<2.00 hari', 'sebelumnya' => '0.00 hari'],
            ],
            'Produk yang Dilarang' => [
                ['nama' => 'Pelanggaran Produk Berat', 'sekarang' => 0, 'target' => 0, 'sebelumnya' => 0],
                ['nama' => 'Produk Pre-order', 'sekarang' => '0.00%', 'target' => '<20.00%', 'sebelumnya' => '0.00%'],
            ],
            'Pelayanan Pembeli' => [
                ['nama' => 'Persentase Chat Dibalas', 'sekarang' => '0.00%', 'target' => '≥70.00%', 'sebelumnya' => '0.00%'],
            ]
        ];

        $poin_penalti_kuartal_ini = 0;
        $pelanggaran_penalti = [
            'Pesanan Tidak Terpenuhi' => 0,
            'Pengiriman Terlambat' => 0,
            'Produk yang Dilarang' => 0,
            'Pelanggaran Lainnya' => 0,
        ];

        $masalah_perlu_diselesaikan = [
            'produk_bermasalah' => 0,
            'keterlambatan_pengiriman' => 0,
        ];

        return view('seller.health', compact(
            'status_kesehatan', 
            'top_summary', 
            'metrics', 
            'poin_penalti_kuartal_ini', 
            'pelanggaran_penalti', 
            'masalah_perlu_diselesaikan'
        ));
    }
}