<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna dengan filter dan statistik.
     */
    public function index(Request $request)
    {
        $limit = 10;
        $level_filter = $request->get('level', 'semua');
        $search = $request->get('search');

        // 1. Ambil Statistik untuk Quick Cards di View
        // Menghitung berdasarkan kolom 'level' di tb_user
        $stats = [
            'total'    => User::where('level', '!=', 'bot')->count(),
            'admin'    => User::where('level', 'admin')->count(),
            'seller'   => User::where('level', 'seller')->count(),
            'customer' => User::where('level', 'customer')->count(),
            'banned'   => User::where('is_banned', true)->count(),
        ];

        // 2. Query Utama menggunakan Eloquent
        $query = User::query()->where('level', '!=', 'bot');

        // Filter Berdasarkan Level (admin, seller, customer)
        if ($level_filter !== 'semua') {
            $query->where('level', $level_filter);
        }

        // Filter Pencarian (Nama, Username, Email)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%$search%")
                  ->orWhere('username', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            });
        }

        // 3. Eksekusi Query dengan Pagination dan tetap membawa query string (level & search)
        $users = $query->latest()->paginate($limit)->withQueryString();

        // Mengirimkan data ke view admin/users/index.blade.php
        return view('admin.users.index', compact('users', 'level_filter', 'search', 'stats'));
    }

    /**
     * Fitur Blokir / Aktifkan Kembali Pengguna (Toggle Ban).
     */
    public function toggleBan($id)
    {
        // Mencari user berdasarkan ID primer di tb_user
        $user = User::findOrFail($id);
        
        // Keamanan: Proteksi agar admin tidak mem-ban dirinya sendiri yang sedang login
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Keamanan Sistem: Anda tidak diizinkan memblokir akun Anda sendiri!');
        }

        // Toggle status is_banned (true jadi false, false jadi true)
        $user->is_banned = !$user->is_banned;
        $user->save();

        // Menyiapkan pesan feedback berdasarkan status terbaru
        $statusText = $user->is_banned ? 'telah diblokir dari sistem' : 'telah diaktifkan kembali';
        $messageType = $user->is_banned ? 'warning' : 'success';

        return back()->with($messageType, "Pengguna {$user->nama} (ID: #{$user->id}) {$statusText}.");
    }

    /**
     * Menampilkan detail profil pengguna (Opsional untuk pengembangan selanjutnya)
     */
    public function show($id)
    {
        $user = User::with('toko')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
}