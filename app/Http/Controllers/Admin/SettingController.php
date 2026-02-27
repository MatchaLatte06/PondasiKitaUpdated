<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman Pengaturan
     */
    public function index()
    {
        // Ambil semua pengaturan dan jadikan key-value pair
        $settingsData = DB::table('tb_pengaturan')->get();
        $settings = [];
        foreach ($settingsData as $row) {
            $settings[$row->setting_nama] = $row->setting_nilai;
        }

        // Daftar Kurir Bawaan untuk Integrasi Logistik
        $couriers = [
            'jne' => 'JNE Express', 'pos' => 'POS Indonesia', 'tiki' => 'TIKI',
            'sicepat' => 'SiCepat', 'jnt' => 'J&T Express', 'ninja' => 'Ninja Xpress',
            'anteraja' => 'AnterAja', 'gosend' => 'GoSend', 'grab' => 'GrabExpress'
        ];

        return view('admin.settings.index', compact('settings', 'couriers'));
    }

    /**
     * Menyimpan semua pembaruan pengaturan secara massal (Bulk Update)
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'couriers']);

        // Handle array khusus (seperti pilihan kurir)
        if ($request->has('couriers')) {
            $data['rajaongkir_active_couriers'] = json_encode($request->couriers);
        } else {
            $data['rajaongkir_active_couriers'] = json_encode([]);
        }

        // Handle Checkbox/Toggle Switch (Karena unchecked checkbox tidak terkirim via POST)
        $toggles = ['midtrans_is_production', 'auto_approve_products', 'auto_approve_stores'];
        foreach ($toggles as $toggle) {
            if (!isset($data[$toggle])) {
                $data[$toggle] = '0';
            }
        }

        // Looping dan Update/Insert ke tb_pengaturan
        foreach ($data as $key => $value) {
            DB::table('tb_pengaturan')->updateOrInsert(
                ['setting_nama' => $key],
                ['setting_nilai' => $value]
            );
        }

        return back()->with('success', 'Konfigurasi platform berhasil diperbarui.');
    }

    /**
     * Fitur Sinkronisasi Data Wilayah Komerce (RajaOngkir)
     */
    public function syncKomerce()
    {
        $apiKey = DB::table('tb_pengaturan')->where('setting_nama', 'rajaongkir_api_key')->value('setting_nilai');

        if (empty($apiKey)) {
            return back()->with('error', 'Kunci API Komerce belum diatur. Silakan isi di tab API & Integrasi.');
        }

        try {
            DB::beginTransaction();

            // 1. Ambil & Simpan Provinsi
            $provResponse = Http::withHeaders(['accept' => 'application/json', 'key' => $apiKey])
                ->timeout(30)->get('https://rajaongkir.komerce.id/api/v1/destination/province');
            
            if (!$provResponse->successful() || $provResponse->json('status') !== 'success') {
                throw new \Exception('Gagal mengambil data provinsi: ' . $provResponse->json('message', 'Unknown Error'));
            }

            foreach ($provResponse->json('data') as $prov) {
                DB::table('provinces')->updateOrInsert(
                    ['id' => $prov['id']],
                    ['name' => $prov['name']]
                );
            }

            // 2. Ambil & Simpan Kota
            $cityResponse = Http::withHeaders(['accept' => 'application/json', 'key' => $apiKey])
                ->timeout(30)->get('https://rajaongkir.komerce.id/api/v1/destination/city');

            if (!$cityResponse->successful() || $cityResponse->json('status') !== 'success') {
                throw new \Exception('Gagal mengambil data kota: ' . $cityResponse->json('message', 'Unknown Error'));
            }

            // Hapus kota lama agar bersih (Hati-hati jika tabel ini terelasi restrict dengan alamat)
            // Alternatif aman: UpdateOrInsert
            foreach ($cityResponse->json('data') as $city) {
                DB::table('cities')->updateOrInsert(
                    ['id' => $city['id']],
                    [
                        'province_id' => $city['province_id'],
                        'name' => $city['name']
                    ]
                );
            }

            // Catat waktu sinkronisasi terakhir
            DB::table('tb_pengaturan')->updateOrInsert(
                ['setting_nama' => 'rajaongkir_last_sync'],
                ['setting_nilai' => now()->format('Y-m-d H:i:s')]
            );

            DB::commit();
            return back()->with('success', 'Berhasil menyinkronkan data '. count($provResponse->json('data')) .' Provinsi dan '. count($cityResponse->json('data')) .' Kota.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Sinkronisasi Gagal: ' . $e->getMessage());
        }
    }
}