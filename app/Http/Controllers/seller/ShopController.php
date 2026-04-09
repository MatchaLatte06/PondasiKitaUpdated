<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * ==========================================
     * 1. MANAJEMEN PROFIL TOKO
     * ==========================================
     */
    public function profile()
    {
        $toko = Auth::user()->toko;
        return view('seller.shop.profile', compact('toko'));
    }

    public function updateProfile(Request $request)
    {
        $toko = Auth::user()->toko;

        // Validasi Super Ketat
        $request->validate([
            'nama_toko'      => 'required|string|max:50',
            'slogan'         => 'nullable|string|max:100',
            'deskripsi'      => 'nullable|string|max:1000',
            'no_telepon'     => 'required|string|max:20',
            'alamat_lengkap' => 'required|string|max:255',
            'kota'           => 'required|string|max:100',
            'kode_pos'       => 'required|numeric|digits_between:5,6',
            'logo_toko'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_toko'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->except(['logo_toko', 'banner_toko']);

        // Handle Logo Baru + Hapus yang Lama
        if ($request->hasFile('logo_toko')) {
            $logo = $request->file('logo_toko');
            $logoName = 'logo_' . Str::random(10) . '.' . $logo->getClientOriginalExtension();

            if ($toko->logo_toko) {
                $oldPath = public_path('assets/uploads/logos/' . $toko->logo_toko);
                if (File::exists($oldPath)) File::delete($oldPath);
            }

            $logo->move(public_path('assets/uploads/logos'), $logoName);
            $data['logo_toko'] = $logoName;
        }

        // Handle Banner Baru + Hapus yang Lama
        if ($request->hasFile('banner_toko')) {
            $banner = $request->file('banner_toko');
            $bannerName = 'banner_' . Str::random(10) . '.' . $banner->getClientOriginalExtension();

            if ($toko->banner_toko) {
                $oldBannerPath = public_path('assets/uploads/banners/' . $toko->banner_toko);
                if (File::exists($oldBannerPath)) File::delete($oldBannerPath);
            }

            $banner->move(public_path('assets/uploads/banners'), $bannerName);
            $data['banner_toko'] = $bannerName;
        }

        $toko->update($data);
        return redirect()->back()->with('success', 'Profil Toko berhasil diperbarui!');
    }

    /**
     * ==========================================
     * 2. PENGATURAN TOKO & KEAMANAN
     * ==========================================
     */
    public function settings()
    {
        $user = Auth::user();
        $toko = $user->toko;

        $notif = json_decode($toko->notifikasi_settings ?? '{}', true) ?: [
            'email_pesanan' => true,
            'email_promo'   => false,
            'push_chat'     => true,
        ];

        return view('seller.shop.settings', compact('user', 'toko', 'notif'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $toko = $user->toko;

        // Tab Keamanan (Ganti Password)
        if ($request->has('form_type') && $request->form_type == 'security') {
            $request->validate([
                'current_password' => 'required',
                'new_password'     => 'required|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with('error', 'Password saat ini salah!');
            }

            $user->update(['password' => Hash::make($request->new_password)]);
            return redirect()->back()->with('success', 'Password berhasil diperbarui!');
        }

        // Tab Pengaturan Umum & Notifikasi
        if ($request->has('form_type') && $request->form_type == 'general') {
            $isVacation = $request->has('status_libur') ? 1 : 0;

            $notifSettings = json_encode([
                'email_pesanan' => $request->has('notif_email_pesanan'),
                'email_promo'   => $request->has('notif_email_promo'),
                'push_chat'     => $request->has('notif_push_chat'),
            ]);

            $toko->update([
                'status_libur'        => $isVacation,
                'pesan_otomatis'      => $request->pesan_otomatis,
                'notifikasi_settings' => $notifSettings,
            ]);

            return redirect()->back()->with('success', 'Pengaturan toko berhasil disimpan!');
        }

        return redirect()->back()->with('error', 'Permintaan tidak valid.');
    }

    /**
     * ==========================================
     * 3. DEKORASI TOKO (DRAG & DROP LOGIC)
     * ==========================================
     */

    // Halaman Landing Dekorasi (Pilih Mobile/Desktop)
    public function decoration()
    {
        $toko = Auth::user()->toko;

        $defaultLayout = [
            ['id' => 'banner_promo', 'type' => 'banner', 'title' => 'Banner Promo Utama', 'image' => null],
            ['id' => 'kategori_pilihan', 'type' => 'kategori', 'title' => 'Kategori Pilihan', 'items' => []],
            ['id' => 'produk_terlaris', 'type' => 'produk', 'title' => 'Produk Terlaris', 'items' => []]
        ];

        $layoutData = empty($toko->layout_data) ? $defaultLayout : $toko->layout_data;

        return view('seller.shop.decoration', compact('toko', 'layoutData'));
    }

    // Halaman Pemilihan Template (Mencegah Error 500 Call to undefined method)
    public function templateSelection()
    {
        $toko = Auth::user()->toko;
        return view('seller.shop.template-selection', compact('toko'));
    }

    // Halaman Editor Drag & Drop (Perbaikan Internal Server Error)
    public function editor()
    {
        $toko = Auth::user()->toko;
        return view('seller.shop.editor', compact('toko'));
    }

    public function editorDesktop()
    {
        $toko = auth()->user()->toko; // Mengambil data toko user yang login

        // Pastikan nama view ini sesuai dengan lokasi file blade desktop Anda
        return view('seller.shop.editor-desktop', compact('toko'));
    }
    /**
     * Update susunan dekorasi via AJAX (Fetch API)
     */
    public function updateDecoration(Request $request)
    {
        $request->validate([
            'layout_data' => 'required|array'
        ]);

        $toko = Auth::user()->toko;

        // Simpan array JSON langsung ke database
        $toko->update([
            'layout_data' => $request->layout_data
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Dekorasi toko berhasil disimpan!'
        ]);
    }
}
