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
     * Menyimpan semua pembaruan pengaturan (Upload Gambar & Data)
     */
    public function update(Request $request)
    {
        // 1. Ambil semua inputan kecuali token dan method
        $settings = $request->except(['_token', '_method']);

        // 2. LOGIKA UPLOAD GAMBAR (Banner & Popup)
        $imageFields = ['hero_image_1', 'hero_image_2', 'hero_image_3', 'hero_image_4', 'popup_image'];

        foreach ($imageFields as $field) {
            // Jika ada gambar baru yang diunggah
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                
                // Simpan gambar ke folder storage/app/public/banners
                $path = $file->storeAs('banners', $filename, 'public');
                
                // Masukkan path ke array settings agar ikut tersimpan ke database
                $settings[$field] = $path;
            }
        }

        // 3. Keamanan Checkbox: Jika toggle dimatikan, paksa nilainya jadi '0'
        $toggles = [
            'maintenance_mode',
            'enable_welcome_popup',
            'show_top_stores',
            'show_best_selling',
            'midtrans_is_production', 
            'auto_approve_products', 
            'auto_approve_stores', 
            'enable_dp_system'
        ];
        
        foreach ($toggles as $toggle) {
            if (!isset($settings[$toggle])) {
                $settings[$toggle] = '0';
            }
        }

        // 4. Keamanan Array: Ubah pilihan kurir menjadi format JSON agar bisa disimpan di 1 kolom
        if (isset($settings['couriers'])) {
            $settings['rajaongkir_active_couriers'] = json_encode($settings['couriers']);
            unset($settings['couriers']); // Hapus array asli agar tidak error saat di-looping
        } else {
            $settings['rajaongkir_active_couriers'] = json_encode([]);
        }

        // 5. Looping Simpan ke Database (Disesuaikan dengan tabel tb_pengaturan)
        foreach ($settings as $key => $value) {
            // Pastikan data yang disimpan bukan array atau object (kecuali yang sudah diubah ke JSON)
            if (is_array($value)) {
                $value = json_encode($value);
            }

            if (!is_object($value)) {
                DB::table('tb_pengaturan')
                    ->updateOrInsert(
                        ['setting_nama' => $key],
                        ['setting_nilai' => $value]
                    );
            }
        }

        return redirect()->back()->with('success', 'Pengaturan sistem & tampilan website berhasil diperbarui!');
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

            foreach ($cityResponse->json('data') as $city) {
                DB::table('cities')->updateOrInsert(
                    ['id' => $city['id']],
                    [
                        'province_id' => $city['province_id'],
                        'name' => $city['name']
                    ]
                );
            }

            // 3. Catat waktu sinkronisasi terakhir
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