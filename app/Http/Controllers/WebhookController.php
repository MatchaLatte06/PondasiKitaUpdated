<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Untuk mencatat riwayat transaksi di sistem

class WebhookController extends Controller
{
    /**
     * Handler untuk menerima notifikasi otomatis dari Midtrans (Webhook)
     */
    public function midtransHandler(Request $request)
    {
        // 1. Ambil Server Key dari tabel pengaturan (Dinamis dari Inputan Super Admin)
        $serverKey = DB::table('tb_pengaturan')->where('setting_nama', 'midtrans_server_key')->value('setting_nilai');

        // 2. Tangkap seluruh data Payload (Notifikasi) yang dikirim Midtrans
        $payload = $request->all();
        
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? 'accept';

        // 3. RUMUS KEAMANAN (Validasi Keaslian Signature Key)
        // Mencegah hacker memalsukan status pembayaran dengan menembak API webhook palsu
        $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($mySignature !== $signatureKey) {
            Log::warning('MIDTRANS HACK ATTEMPT / INVALID SIGNATURE: ' . $orderId);
            return response()->json(['status' => 'error', 'message' => 'Invalid Signature'], 403);
        }

        // 4. Cari Data Transaksi di Database
        $transaksi = DB::table('tb_transaksi')->where('kode_invoice', $orderId)->first();
        
        if (!$transaksi) {
            Log::error('MIDTRANS ERROR: Pesanan tidak ditemukan - ' . $orderId);
            return response()->json(['status' => 'error', 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        // KUNCI KEAMANAN STOK: 
        // Jangan proses lagi jika pesanan sudah berstatus 'paid' (lunas) atau 'failed' (batal).
        // Ini mencegah stok berkurang/bertambah 2x lipat jika Midtrans mengirim notif berulang.
        if (in_array($transaksi->status_pembayaran, ['paid', 'failed'])) {
            return response()->json(['status' => 'success', 'message' => 'Notifikasi sudah diproses sebelumnya'], 200);
        }

        // 5. Mulai Proses Perubahan Status & Manajemen Stok
        DB::beginTransaction();
        try {
            // A. JIKA PEMBAYARAN BERHASIL (LUNAS)
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'accept') {
                    
                    // Update Status Pesanan Jadi Lunas & Diproses
                    DB::table('tb_transaksi')->where('id', $transaksi->id)->update([
                        'status_pembayaran' => 'paid',
                        'status_pesanan_global' => 'diproses',
                        'updated_at' => now()
                    ]);

                    // --- FITUR NGURANGIN STOK BARANG ---
                    // Ambil semua barang yang dibeli di transaksi ini
                    $items = DB::table('tb_detail_transaksi')->where('transaksi_id', $transaksi->id)->get();
                    
                    foreach ($items as $item) {
                        // Kurangi stok fisik di tabel barang
                        DB::table('tb_barang')
                            ->where('id', $item->barang_id)
                            ->decrement('stok', $item->jumlah);
                    }
                }
            } 
            // B. JIKA PEMBAYARAN GAGAL / KADALUWARSA / DIBATALKAN
            else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                
                // Update Status Pesanan Jadi Gagal & Batal
                DB::table('tb_transaksi')->where('id', $transaksi->id)->update([
                    'status_pembayaran' => 'failed',
                    'status_pesanan_global' => 'dibatalkan',
                    'updated_at' => now()
                ]);

                // Batalkan juga status pengiriman di setiap item toko
                DB::table('tb_detail_transaksi')->where('transaksi_id', $transaksi->id)->update([
                    'status_pesanan_item' => 'dibatalkan',
                    'updated_at' => now()
                ]);

                // --- FITUR RETURN BARANG (KEMBALIKAN STOK) ---
                // PENTING: Aktifkan kode ini HANYA JIKA Anda sudah memotong stok pembeli di awal (saat mereka klik tombol checkout). 
                // Jika stok baru dipotong saat LUNAS (di blok kode A atas), biarkan ini di-comment agar stok tidak nambah terus.
                
                /*
                $items = DB::table('tb_detail_transaksi')->where('transaksi_id', $transaksi->id)->get();
                foreach ($items as $item) {
                    DB::table('tb_barang')
                        ->where('id', $item->barang_id)
                        ->increment('stok', $item->jumlah); // Tambahkan kembali stoknya
                }
                */
            }

            DB::commit();
            Log::info('MIDTRANS PAYMENT SUCCESS: Invoice ' . $orderId . ' is now ' . $transactionStatus);
            
            // Wajib balas HTTP 200 OK ke Midtrans agar mereka tahu sistem kita sukses memprosesnya
            return response()->json(['status' => 'success', 'message' => 'Webhook berhasil diproses'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MIDTRANS WEBHOOK DATABASE ERROR: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Sistem gagal memproses database'], 500);
        }
    }
}