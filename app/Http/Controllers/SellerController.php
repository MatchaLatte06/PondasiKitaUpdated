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
        
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();

        if (!$toko) {
            return redirect()->route('home')->with('error', 'Anda belum memiliki toko.');
        }

        $tokoId = $toko->id;

        $totalPenjualan = DB::table('tb_detail_transaksi')
            ->where('toko_id', $tokoId)
            ->sum('subtotal');

        $totalPesanan = DB::table('tb_detail_transaksi')
            ->where('toko_id', $tokoId)
            ->distinct('transaksi_id')
            ->count('transaksi_id');

        $totalItemTerjual = DB::table('tb_detail_transaksi')
            ->where('toko_id', $tokoId)
            ->sum('jumlah');

        $totalProdukAktif = DB::table('tb_barang')
            ->where('toko_id', $tokoId)
            ->where('is_active', 1)
            ->count();

        $tahunSekarang = date('Y');
        $penjualanTahunan = array_fill(1, 12, 0); 

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

        return view('seller.dashboard', compact(
            'toko', 'totalPenjualan', 'totalPesanan', 'totalItemTerjual', 'totalProdukAktif',
            'labelsBulan', 'penjualanTahunan', 'topProdukLabels', 'topProdukData', 'tahunSekarang'
        ));
    }

    // =========================================================================
    // 2. HALAMAN MANAJEMEN PESANAN MASUK
    // =========================================================================
    public function pesanan(Request $request)
    {
        $user = Auth::user();
        
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        $pesananRaw = DB::table('tb_detail_transaksi as d')
            ->join('tb_transaksi as t', 'd.transaksi_id', '=', 't.id')
            ->join('tb_barang as b', 'd.barang_id', '=', 'b.id')
            ->join('tb_user as u', 't.user_id', '=', 'u.id') 
            ->where('d.toko_id', $toko->id)
            ->select(
                'd.id as detail_id', 'd.jumlah', 'd.subtotal', 'd.status_pesanan_item', 
                't.kode_invoice', 't.tanggal_transaksi',
                'b.nama_barang', 'b.gambar_utama', 
                'u.nama as nama_pelanggan'
            )
            ->orderByRaw("FIELD(d.status_pesanan_item, 'siap_kirim', 'diproses', 'dikirim', 'sampai_tujuan', 'dibatalkan', 'ditolak') DESC")
            ->orderBy('t.tanggal_transaksi', 'desc')
            ->get();

        $groupedOrders = $pesananRaw->groupBy('kode_invoice');

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

        return view('seller.pesanan', compact('groupedOrders', 'statusMap', 'currentFilter'));
    }

    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'detail_id' => 'required|integer',
            'status_baru' => 'required|string'
        ]);

        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        DB::table('tb_detail_transaksi')
            ->where('id', $request->detail_id)
            ->where('toko_id', $toko->id)
            ->update(['status_pesanan_item' => $request->status_baru]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function massUpdateOrderStatus(Request $request)
    {
        if (!$request->has('detail_ids') || empty($request->detail_ids)) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu pesanan untuk diproses.');
        }

        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

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

        $currentFilter = $request->query('status', '');

        // Query Selaras dengan Database Pondasikita
        $query = DB::table('tb_komplain as k') 
            ->join('tb_transaksi as t', 'k.transaksi_id', '=', 't.id')
            ->join('tb_user as u', 'k.user_id', '=', 'u.id')
            ->where('k.toko_id', $toko->id)
            ->select(
                'k.id as id_return', 
                'k.alasan_komplain as alasan', // DISESUAIKAN DENGAN NAMA KOLOM DB
                'k.bukti_foto_1 as bukti_foto', // DISESUAIKAN DENGAN NAMA KOLOM DB
                'k.status_komplain as status', 
                'k.created_at as tanggal_pengajuan',
                't.kode_invoice',
                'u.nama as nama_pelanggan',
                DB::raw("'Material Retur' as nama_barang"),
                DB::raw("'default.jpg' as gambar_utama"),
                DB::raw("1 as jumlah"),
                't.total_final as total_pengembalian'
            )
            ->orderBy('k.created_at', 'desc');

        // Menerapkan Filter UI ke Enum Database
        if ($currentFilter != '') {
            if($currentFilter == 'menunggu_respon') {
                $query->whereIn('k.status_komplain', ['investigasi', 'menunggu_tanggapan_toko']);
            } elseif ($currentFilter == 'disetujui') {
                $query->where('k.status_komplain', 'refund_pembeli');
            } elseif ($currentFilter == 'ditolak') {
                $query->whereIn('k.status_komplain', ['teruskan_dana_toko', 'selesai']); 
            }
        }

        $returnsRaw = $query->get();

        // Menerjemahkan Enum Database kembali ke Format View
        $returns = $returnsRaw->map(function($item) {
            if(in_array($item->status, ['investigasi', 'menunggu_tanggapan_toko'])) {
                $item->status = 'menunggu_respon';
            } elseif ($item->status == 'refund_pembeli') {
                $item->status = 'disetujui';
            } elseif (in_array($item->status, ['teruskan_dana_toko', 'selesai'])) {
                $item->status = 'ditolak'; 
            }
            return $item;
        });

        // Fallback Dummy Data jika tabel kosong (Untuk keperluan UI Testing)
        if ($returns->isEmpty()) {
            $returns = [
                (object)[
                    'id_return' => 'RET-001', 'kode_invoice' => 'INV/202310/001', 'tanggal_pengajuan' => '2023-10-25 14:30:00',
                    'nama_pelanggan' => 'PT. Bangun Persada', 'nama_barang' => 'Semen Tiga Roda 40kg', 'gambar_utama' => 'default.jpg',
                    'jumlah' => 10, 'total_pengembalian' => 500000,
                    'alasan' => 'Semen mengeras karena kehujanan saat pengiriman armada toko.',
                    'bukti_foto' => 'default.jpg', 'status' => 'menunggu_respon' 
                ],
                (object)[
                    'id_return' => 'RET-002', 'kode_invoice' => 'INV/202310/088', 'tanggal_pengajuan' => '2023-10-24 09:15:00',
                    'nama_pelanggan' => 'Budi Mandor', 'nama_barang' => 'Keramik Roman 40x40', 'gambar_utama' => 'default.jpg',
                    'jumlah' => 5, 'total_pengembalian' => 300000,
                    'alasan' => 'Satu dus pecah semua di ujung.',
                    'bukti_foto' => 'default.jpg', 'status' => 'disetujui'
                ]
            ];
            
            if($currentFilter != '') {
                $returns = array_filter($returns, function($r) use ($currentFilter) {
                    return $r->status == $currentFilter;
                });
            }
        }

        return view('seller.pengembalian', compact('returns', 'currentFilter'));
    }

    public function processPengembalian(Request $request)
    {
        $request->validate([
            'id_return' => 'required',
            'action' => 'required|in:approve,reject'
        ]);

        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();
        
        // Mapping action UI ke Enum Database
        // approve = Refund ke pembeli
        // reject = Tolak, teruskan dana ke dompet toko
        $statusBaru = $request->action == 'approve' ? 'refund_pembeli' : 'teruskan_dana_toko';

        if(DB::getSchemaBuilder()->hasTable('tb_komplain')) {
            DB::table('tb_komplain')
                ->where('id', $request->id_return)
                ->where('toko_id', $toko->id)
                ->update(['status_komplain' => $statusBaru, 'updated_at' => now()]);
        }

        $msg = $request->action == 'approve' 
               ? 'Pengembalian dana disetujui. Dana akan direfund ke pembeli.' 
               : 'Komplain ditolak. Dana transaksi akan diteruskan ke saldo toko Anda.';

        return redirect()->back()->with('success', $msg);
    }

// =========================================================================
    // 4. PENGATURAN PENGIRIMAN (LOGISTIK B2B)
    // =========================================================================
    public function pengaturanPengiriman()
    {
        $user = Auth::user();
        
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        $kurirList = DB::table('tb_kurir_toko')
            ->where('toko_id', $toko->id)
            ->orderBy('tipe_kurir', 'asc') // TOKO akan muncul lebih dulu
            ->orderBy('nama_kurir')
            ->get();

        // KITA SELARASKAN DENGAN ENUM DATABASE ('TOKO', 'PIHAK_KETIGA')
        $tipeOrder = [
            'TOKO' => 'Armada Toko (Khusus Material Berat/Curah)',
            'PIHAK_KETIGA' => 'Kurir Ekspedisi (Barang Ringan/Kecil)'
        ];

        $groupedKurir = [];
        foreach ($kurirList as $kurir) {
            $groupedKurir[$kurir->tipe_kurir][] = $kurir;
        }

        return view('seller.pengaturan_pengiriman', compact('groupedKurir', 'tipeOrder'));
    }

    public function storePengiriman(Request $request)
    {
        $request->validate([
            'nama_kurir' => 'required|string|max:100',
            'tipe_kurir' => 'required|in:TOKO,PIHAK_KETIGA', // Validasi keamananan ENUM
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
            DB::table('tb_kurir_toko')
                ->where('id', $request->kurir_id)
                ->where('toko_id', $toko->id) 
                ->update($data);
            $msg = 'Layanan pengiriman berhasil diperbarui.';
        } else {
            DB::table('tb_kurir_toko')->insert($data);
            $msg = 'Layanan pengiriman berhasil ditambahkan.';
        }

        return redirect()->route('seller.pengaturan.pengiriman')->with('success', $msg);
    }

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
    // 5. PUSAT PROMOSI (MANAJEMEN HARGA CORET)
    // =========================================================================
    public function promosi(Request $request)
    {
        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        $query = DB::table('tb_barang')->where('toko_id', $toko->id);

        // Pencarian Nama Barang
        if($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%'.$request->search.'%');
        }

        // Filter Tab (Status Diskon)
        $currentTab = $request->query('tab', 'semua');
        $now = now();

        if ($currentTab == 'aktif') {
            $query->whereNotNull('nilai_diskon')->where('nilai_diskon', '>', 0)
                  ->where('diskon_mulai', '<=', $now)->where('diskon_berakhir', '>=', $now);
        } elseif ($currentTab == 'akan_datang') {
            $query->whereNotNull('nilai_diskon')->where('nilai_diskon', '>', 0)
                  ->where('diskon_mulai', '>', $now);
        } elseif ($currentTab == 'tidak_aktif') {
            $query->where(function($q) use ($now) {
                $q->whereNull('nilai_diskon')->orWhere('nilai_diskon', 0)
                  ->orWhere('diskon_berakhir', '<', $now);
            });
        }

        $products = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('seller.promosi', compact('products', 'currentTab'));
    }

    // API: Menyimpan Diskon Massal & Satuan (Via AJAX)
    public function updateDiscount(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'tipe_diskon' => 'nullable|in:NOMINAL,PERSEN',
            'nilai_diskon' => 'nullable|numeric|min:0',
            'diskon_mulai' => 'nullable|date',
            'diskon_berakhir' => 'nullable|date|after:diskon_mulai'
        ]);

        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        // Logika: Jika nilai diskon 0 atau null, artinya user menghapus/menonaktifkan diskon
        if(empty($request->nilai_diskon) || $request->nilai_diskon == 0) {
            DB::table('tb_barang')
                ->whereIn('id', $request->product_ids)
                ->where('toko_id', $toko->id)
                ->update([
                    'tipe_diskon' => null, 'nilai_diskon' => null,
                    'diskon_mulai' => null, 'diskon_berakhir' => null,
                    'updated_at' => now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Diskon berhasil dihapus / dinonaktifkan.']);
        }

        // Logika: Update Harga Coret
        DB::table('tb_barang')
            ->whereIn('id', $request->product_ids)
            ->where('toko_id', $toko->id)
            ->update([
                'tipe_diskon' => $request->tipe_diskon,
                'nilai_diskon' => $request->nilai_diskon,
                'diskon_mulai' => $request->diskon_mulai,
                'diskon_berakhir' => $request->diskon_berakhir,
                'updated_at' => now()
            ]);

        return response()->json(['status' => 'success', 'message' => 'Promo Harga Coret berhasil diterapkan.']);
    }
// =========================================================================
    // 6. HALAMAN VOUCHER TOKO (ENTERPRISE LOGIC)
    // =========================================================================
    public function voucher(Request $request)
    {
        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // Statistik Cepat untuk Dashboard Voucher
        $stats = [
            'aktif' => DB::table('vouchers')->where('toko_id', $toko->id)->where('status', 'AKTIF')->count(),
            'terpakai' => DB::table('vouchers')->where('toko_id', $toko->id)->sum('kuota_terpakai') ?? 0,
        ];

        // Query Ambil Voucher
        $query = DB::table('vouchers')->where('toko_id', $toko->id);

        // Filter Pencarian
        if($request->has('search') && $request->search != '') {
            $query->where('kode_voucher', 'like', '%'.$request->search.'%')
                  ->orWhere('deskripsi', 'like', '%'.$request->search.'%');
        }

        // Filter Status Tab
        $currentTab = $request->query('tab', 'semua');
        if($currentTab == 'aktif') {
            $query->where('status', 'AKTIF')->where('tanggal_berakhir', '>=', now());
        } elseif($currentTab == 'habis') {
            $query->whereRaw('kuota_terpakai >= kuota');
        } elseif($currentTab == 'nonaktif') {
            $query->where('status', 'TIDAK_AKTIF')->orWhere('tanggal_berakhir', '<', now());
        }

        $voucher_list = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('seller.voucher', compact('voucher_list', 'stats', 'currentTab'));
    }

    // API: Simpan Voucher Baru
    public function storeVoucher(Request $request)
    {
        $request->validate([
            'kode_voucher' => 'required|string|max:12|unique:vouchers,kode_voucher',
            'deskripsi' => 'required|string|max:255',
            'tipe_diskon' => 'required|in:RUPIAH,PERSEN',
            'nilai_diskon' => 'required|numeric|min:1',
            'min_pembelian' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
        ]);

        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        // Keamanan Bisnis B2B: 
        // Jika diskon persen, wajib ada limit/maksimal diskon. Jika tidak diisi, set default aman.
        $maksDiskon = null;
        if ($request->tipe_diskon == 'PERSEN') {
            if ($request->nilai_diskon > 100) return back()->with('error', 'Diskon persen tidak boleh lebih dari 100%');
            $maksDiskon = $request->maks_diskon > 0 ? $request->maks_diskon : null; 
        }

        DB::table('vouchers')->insert([
            'toko_id' => $toko->id,
            'kode_voucher' => strtoupper($request->kode_voucher),
            'deskripsi' => $request->deskripsi,
            'tipe_diskon' => $request->tipe_diskon,
            'nilai_diskon' => $request->nilai_diskon,
            'maks_diskon' => $maksDiskon,
            'min_pembelian' => $request->min_pembelian,
            'kuota' => $request->kuota,
            'kuota_terpakai' => 0,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
            'status' => 'AKTIF'
        ]);

        return redirect()->route('seller.promotion.vouchers')->with('success', 'Voucher berhasil diterbitkan!');
    }

    // API: Toggle Status Voucher (Aktif/Nonaktif) via AJAX
    public function toggleVoucher(Request $request)
    {
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();
        
        $status_baru = $request->is_active ? 'AKTIF' : 'TIDAK_AKTIF';

        $updated = DB::table('vouchers')
            ->where('id', $request->voucher_id)
            ->where('toko_id', $toko->id)
            ->update(['status' => $status_baru]);

        if($updated) return response()->json(['status' => 'success']);
        return response()->json(['status' => 'error'], 400);
    }



// =========================================================================
    // 7. MANAJEMEN CHAT (ENTERPRISE GRADE)
    // =========================================================================
    public function chat()
    {
        return view('seller.chat');
    }

    public function getChatList()
    {
        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();

        if (!$toko) return response()->json(['status' => 'error', 'message' => 'Toko tidak ditemukan']);

        // Query Kompleks untuk mengambil daftar chat beserta pesan terakhirnya
        $chats = DB::table('chats as c')
            ->join('tb_user as u', 'c.customer_id', '=', 'u.id')
            ->where('c.toko_id', $toko->id)
            ->select(
                'c.id',
                'u.nama as nama_pelanggan',
                // Subquery untuk pesan terakhir
                DB::raw('(SELECT message_text FROM messages m WHERE m.chat_id = c.id ORDER BY timestamp DESC LIMIT 1) as last_message'),
                // Subquery untuk waktu pesan terakhir
                DB::raw('(SELECT timestamp FROM messages m WHERE m.chat_id = c.id ORDER BY timestamp DESC LIMIT 1) as last_time')
            )
            ->orderByRaw('last_time DESC NULLS LAST') // Urutkan yang terbaru di atas
            ->get();

        // Format waktu agar lebih cantik di frontend
        $formattedChats = $chats->map(function($chat) {
            if ($chat->last_time) {
                $date = \Carbon\Carbon::parse($chat->last_time);
                $chat->time_display = $date->isToday() ? $date->format('H:i') : $date->format('d/m/y');
            } else {
                $chat->time_display = '';
            }
            return $chat;
        });

        return response()->json(['status' => 'success', 'data' => $formattedChats]);
    }

    public function getMessages($chatId)
    {
        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();

        // Keamanan: Pastikan chat ini benar-benar milik toko yang sedang login
        $validChat = DB::table('chats')->where('id', $chatId)->where('toko_id', $toko->id)->exists();
        if (!$validChat) return response()->json(['status' => 'error', 'message' => 'Unauthorized']);

        // Ambil pesan dari yang terlama ke terbaru (ASC) agar urut di UI dari atas ke bawah
        $messages = DB::table('messages')
            ->where('chat_id', $chatId)
            ->orderBy('timestamp', 'asc')
            ->get();

        $formattedMessages = $messages->map(function($msg) use ($user) {
            return [
                'id' => $msg->id,
                'is_mine' => ($msg->sender_id == $user->id), // True jika yang kirim adalah Seller
                'text' => $msg->message_text,
                'time' => \Carbon\Carbon::parse($msg->timestamp)->format('H:i')
            ];
        });

        return response()->json(['status' => 'success', 'data' => $formattedMessages]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|integer',
            'message_text' => 'required|string'
        ]);

        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();

        // Verifikasi kepemilikan chat
        $validChat = DB::table('chats')->where('id', $request->chat_id)->where('toko_id', $toko->id)->exists();
        if (!$validChat) return response()->json(['status' => 'error'], 403);

        // Simpan ke database messages
        DB::table('messages')->insert([
            'chat_id' => $request->chat_id,
            'sender_id' => $user->id, // ID Penjual
            'message_text' => $request->message_text,
            'timestamp' => now()
        ]);

        return response()->json(['status' => 'success']);
    }
    // =========================================================================
    // 8. POINT OF SALE (KASIR)
    // =========================================================================
    
    public function pos()
    {
        return view('seller.pos');
    }

    public function getPosCategories()
    {
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        $categories = DB::table('tb_kategori')
            ->whereIn('id', function($query) use ($toko) {
                $query->select('kategori_id')
                      ->from('tb_barang')
                      ->where('toko_id', $toko->id);
            })->get();

        return response()->json($categories);
    }

    public function getPosProducts()
    {
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();
        
        $products = DB::table('tb_barang')
            ->where('toko_id', $toko->id)
            ->select('id', 'kode_barang', 'nama_barang', 'harga', 'stok', 'kategori_id')
            ->where('stok', '>', 0)
            ->orderBy('nama_barang', 'asc')
            ->get();

        return response()->json($products);
    }

    public function processPosCheckout(Request $request)
    {
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();
        
        try {
            DB::beginTransaction();

            $invoice = 'POS-' . strtoupper(substr($toko->nama_toko, 0, 3)) . '-' . date('ymdHis');
            
            $transaksiId = DB::table('tb_transaksi')->insertGetId([
                'kode_invoice' => $invoice,
                'user_id' => null, 
                'total_harga' => $request->total,
                'total_final' => $request->total, 
                'metode_pembayaran' => $request->payment_method, 
                'status_pembayaran' => 'paid',
                'status_pesanan_global' => 'selesai', 
                'tanggal_transaksi' => now(),
                'catatan' => 'Pelanggan Walk-In: ' . ($request->customer_name ?? 'Umum'),
                'midtrans_fee' => 0, 
                'customer_service_fee' => 0,
                'customer_handling_fee' => 0
            ]);

            foreach ($request->cart as $item) {
                DB::table('tb_detail_transaksi')->insert([
                    'transaksi_id' => $transaksiId,
                    'toko_id' => $toko->id,
                    'barang_id' => $item['id'],
                    'jumlah' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['harga'] * $item['qty'],
                    'biaya_pengiriman_item' => 0,
                    'kurir_terpilih' => 'Ambil Sendiri',
                    'status_pesanan_item' => 'selesai', 
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('tb_barang')->where('id', $item['id'])->decrement('stok', $item['qty']);
            }

            DB::commit(); 

            return response()->json(['status' => 'success', 'message' => 'Transaksi berhasil!', 'invoice' => $invoice]);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()], 500);
        }
    }

 // =========================================================================
    // 9. PENILAIAN TOKO (REVIEWS - ENTERPRISE GRADE)
    // =========================================================================
    public function reviews(Request $request)
    {
        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // 1. Ambil Summary Rata-Rata Rating
        $summary = DB::table('tb_toko_review')
            ->where('toko_id', $toko->id)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(id) as total_reviews')
            ->first();

        // 2. [BARU] Ambil Breakdown Rating (Hitung jumlah masing-masing bintang)
        $ratingCountsRaw = DB::table('tb_toko_review')
            ->where('toko_id', $toko->id)
            ->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->pluck('total', 'rating')->toArray();

        $ratingCounts = [
            5 => $ratingCountsRaw[5] ?? 0,
            4 => $ratingCountsRaw[4] ?? 0,
            3 => $ratingCountsRaw[3] ?? 0,
            2 => $ratingCountsRaw[2] ?? 0,
            1 => $ratingCountsRaw[1] ?? 0,
        ];

        // 3. Performa Layanan Pelanggan
        $performa = [
            'chat_response_rate' => "95%",
            'chat_response_time' => "≈ 1 jam",
            'cancellation_rate' => "0.5%",
            'late_shipment_rate' => "1.2%"
        ];

        $starFilter = $request->query('star', 'all');

        // 4. Query List Review
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

        if ($starFilter !== 'all' && is_numeric($starFilter)) {
            $query->where('r.rating', $starFilter);
        }

        $reviews = $query->paginate(10); // Gunakan Paginate agar web tidak berat jika review ribuan

        return view('seller.reviews', compact('summary', 'ratingCounts', 'performa', 'reviews', 'starFilter'));
    }

    public function replyReview(Request $request)
    {
        $request->validate([
            'review_id' => 'required|integer',
            'balasan' => 'required|string|max:500'
        ]);

        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        DB::table('tb_toko_review')
            ->where('id', $request->review_id)
            ->where('toko_id', $toko->id)
            ->update([
                'balasan_penjual' => $request->balasan,
                'updated_at' => now() 
            ]);

        return redirect()->back()->with('success', 'Balasan ulasan berhasil dipublikasikan!');
    }
// =========================================================================
    // 10. PENGHASILAN TOKO & DOMPET (FINANCE - ENTERPRISE)
    // =========================================================================
    public function income(Request $request)
    {
        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // 1. Dana Tertahan (Pending): Pesanan sudah dibayar pembeli, tapi barang belum sampai
        $penghasilan_pending = DB::table('tb_detail_transaksi as d')
            ->join('tb_transaksi as t', 'd.transaksi_id', '=', 't.id')
            ->where('d.toko_id', $toko->id)
            ->whereIn('d.status_pesanan_item', ['diproses', 'siap_kirim', 'dikirim'])
            ->where('t.status_pembayaran', 'paid')
            ->sum('d.subtotal');

        // 2. Penghasilan Kotor (Dana dari Pesanan Selesai)
        $penghasilan_kotor = DB::table('tb_detail_transaksi')
            ->where('toko_id', $toko->id)
            ->where('status_pesanan_item', 'sampai_tujuan')
            ->sum('subtotal');

        // 3. Dana yang sudah pernah ditarik (Payout) atau sedang diproses admin
        $dana_ditarik = DB::table('tb_payouts')
            ->where('toko_id', $toko->id)
            ->whereIn('status', ['pending', 'completed'])
            ->sum('jumlah_payout');

        // 4. Saldo Aktif (Bisa Ditarik) = Kotor - Yang Sudah Ditarik
        $saldo_aktif = $penghasilan_kotor - $dana_ditarik;

        // 5. Analitik Mingguan & Bulanan
        $dilepas_minggu_ini = DB::table('tb_detail_transaksi as d')
            ->join('tb_transaksi as t', 'd.transaksi_id', '=', 't.id')
            ->where('d.toko_id', $toko->id)
            ->where('d.status_pesanan_item', 'sampai_tujuan')
            ->whereBetween('t.tanggal_transaksi', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('d.subtotal');

        $dilepas_bulan_ini = DB::table('tb_detail_transaksi as d')
            ->join('tb_transaksi as t', 'd.transaksi_id', '=', 't.id')
            ->where('d.toko_id', $toko->id)
            ->where('d.status_pesanan_item', 'sampai_tujuan')
            ->whereBetween('t.tanggal_transaksi', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('d.subtotal');

        // 6. Query Tabel Rincian Transaksi (Dengan Filter)
        $tab = $request->query('tab', 'dilepas'); 
        $query = DB::table('tb_detail_transaksi as d')
            ->join('tb_transaksi as t', 'd.transaksi_id', '=', 't.id')
            ->where('d.toko_id', $toko->id);

        if ($tab == 'pending') {
            $query->whereIn('d.status_pesanan_item', ['diproses', 'siap_kirim', 'dikirim'])->where('t.status_pembayaran', 'paid');
        } else {
            $query->where('d.status_pesanan_item', 'sampai_tujuan'); // dilepas
        }

        // Search by Invoice
        if ($request->search) {
            $query->where('t.kode_invoice', 'like', '%'.$request->search.'%');
        }
        // Filter by Date
        if ($request->date) {
            $query->whereDate('t.tanggal_transaksi', $request->date);
        }

        $transaksi_list = $query->select('t.kode_invoice', 't.tanggal_transaksi', 'd.status_pesanan_item', 't.metode_pembayaran', 'd.subtotal')
                                ->orderBy('t.tanggal_transaksi', 'desc')
                                ->paginate(10);

        // 7. Riwayat Penarikan Dana (Widget Kanan)
        $riwayat_payout = DB::table('tb_payouts')->where('toko_id', $toko->id)->orderBy('tanggal_request', 'desc')->limit(5)->get();

        return view('seller.income', compact(
            'penghasilan_pending', 'saldo_aktif', 'penghasilan_kotor',
            'dilepas_minggu_ini', 'dilepas_bulan_ini', 
            'transaksi_list', 'tab', 'riwayat_payout'
        ));
    }

    // API: Proses Request Tarik Dana
    public function requestPayout(Request $request)
    {
        $request->validate([
            'jumlah_payout' => 'required|numeric|min:50000'
        ]);

        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();

        // Validasi Saldo Ganda di Backend (Mencegah Hack/Manipulasi Input)
        $penghasilan_kotor = DB::table('tb_detail_transaksi')->where('toko_id', $toko->id)->where('status_pesanan_item', 'sampai_tujuan')->sum('subtotal');
        $dana_ditarik = DB::table('tb_payouts')->where('toko_id', $toko->id)->whereIn('status', ['pending', 'completed'])->sum('jumlah_payout');
        $saldo_aktif = $penghasilan_kotor - $dana_ditarik;

        if ($request->jumlah_payout > $saldo_aktif) {
            return back()->with('error', 'Penarikan ditolak! Nominal melebihi Saldo Aktif Anda.');
        }

        // Insert ke tabel payouts (Akan diverifikasi oleh Admin Pusat)
        DB::table('tb_payouts')->insert([
            'toko_id' => $toko->id,
            'jumlah_payout' => $request->jumlah_payout,
            'status' => 'pending',
            'tanggal_request' => now()
        ]);

        return back()->with('success', 'Berhasil! Permintaan pencairan dana sedang diproses oleh admin.');
    }
// =========================================================================
    // 11. REKENING BANK (ENTERPRISE GRADE)
    // =========================================================================
    public function bank()
    {
        $user = Auth::user();
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();
        
        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data toko tidak ditemukan.');
        }

        // Daftar Bank Resmi Nasional
        $daftar_bank = [
            'BCA', 'Bank Mandiri', 'BNI', 'BRI', 'BSI (Bank Syariah Indonesia)', 
            'CIMB Niaga', 'Bank Permata', 'Bank Danamon', 'SeaBank', 'Bank Jago', 
            'BNC (Bank Neo Commerce)', 'Bank Raya'
        ];

        return view('seller.bank', compact('toko', 'daftar_bank'));
    }

    // API: Simpan / Ubah Rekening
    public function updateBank(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:50',
            'no_rekening' => 'required|string|max:50|regex:/^[0-9]+$/',
            'nama_pemilik' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        
        DB::table('tb_toko')->where('user_id', $user->id)->update([
            'rekening_bank' => $request->nama_bank,
            'nomor_rekening' => $request->no_rekening,
            'atas_nama_rekening' => strtoupper($request->nama_pemilik),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Data Rekening Bank berhasil disimpan dan siap digunakan untuk pencairan dana.');
    }

    // API: Hapus Rekening
    public function destroyBank()
    {
        $user = Auth::user();
        
        // Hapus data (Set null)
        DB::table('tb_toko')->where('user_id', $user->id)->update([
            'rekening_bank' => null,
            'nomor_rekening' => null,
            'atas_nama_rekening' => null,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Rekening bank berhasil dihapus.');
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
            'kriteria', 'chart_labels', 'chart_data', 'saluran', 'pembeli', 'pembeli_donut_chart'
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
            'status_kesehatan', 'top_summary', 'metrics', 'poin_penalti_kuartal_ini', 
            'pelanggaran_penalti', 'masalah_perlu_diselesaikan'
        ));
    }
}