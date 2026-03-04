<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogisticSettingController extends Controller
{
    public function index()
    {
        // Ambil semua pengaturan logistik dari database
        $settingsData = DB::table('tb_pengaturan')->get();
        $settings = [];
        foreach ($settingsData as $row) {
            $settings[$row->setting_nama] = $row->setting_nilai;
        }

        // Daftar Kurir Pihak Ketiga (Reguler & Kargo)
        $api_couriers = [
            'jne' => ['name' => 'JNE Express', 'type' => 'Reguler & Kargo', 'icon' => 'mdi-truck-fast'],
            'jnt' => ['name' => 'J&T Express', 'type' => 'Reguler', 'icon' => 'mdi-truck-delivery'],
            'sicepat' => ['name' => 'SiCepat', 'type' => 'Reguler & Kargo', 'icon' => 'mdi-flash'],
            'indah' => ['name' => 'Indah Logistik', 'type' => 'Kargo Berat', 'icon' => 'mdi-truck-trailer'],
            'deliveree' => ['name' => 'Deliveree', 'type' => 'Kargo Instan', 'icon' => 'mdi-car-pickup'],
        ];

        return view('admin.logistics.index', compact('settings', 'api_couriers'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token']);

        // Konversi checkbox kurir API menjadi JSON
        $data['api_active_couriers'] = isset($request->couriers) ? json_encode($request->couriers) : json_encode([]);
        unset($data['couriers']); // Hapus array asli agar tidak masuk DB langsung

        // Handle Toggle Switch (Jika off, POST tidak mengirim data, jadi kita set 0 manual)
        $toggles = ['enable_custom_fleet', 'enable_emergency_delivery'];
        foreach ($toggles as $toggle) {
            if (!isset($data[$toggle])) {
                $data[$toggle] = '0';
            }
        }

        // Simpan ke database tb_pengaturan
        foreach ($data as $key => $value) {
            DB::table('tb_pengaturan')->updateOrInsert(
                ['setting_nama' => $key],
                ['setting_nilai' => $value]
            );
        }

        return back()->with('success', 'Regulasi dan Pengaturan Logistik berhasil diperbarui.');
    }
}