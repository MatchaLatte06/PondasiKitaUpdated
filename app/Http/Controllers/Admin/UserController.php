<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. TAMPILKAN HALAMAN UTAMA & STATISTIK
    public function index(Request $request)
    {
        $limit = 10;
        $level_filter = $request->get('level', 'semua');
        $search = $request->get('search');

        $stats = [
            'total'    => User::where('level', '!=', 'bot')->count(),
            'admin'    => User::where('level', 'admin')->count(),
            'seller'   => User::where('level', 'seller')->count(),
            'customer' => User::where('level', 'customer')->count(),
            'banned'   => User::where('is_banned', true)->count(),
        ];

        $query = User::query()->where('level', '!=', 'bot');

        if ($level_filter !== 'semua') {
            $query->where('level', $level_filter);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%$search%")
                  ->orWhere('username', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            });
        }

        $users = $query->latest()->paginate($limit)->withQueryString();

        return view('admin.users.index', compact('users', 'level_filter', 'search', 'stats'));
    }

    // 2. TAMBAH ADMIN BARU (KHUSUS SUPER ADMIN)
    public function store(Request $request)
    {
        // Proteksi Lapis 2: Tolak jika yang memaksa bukan Super Admin
        if (Auth::user()->admin_role !== 'super') {
            return back()->with('error', 'Akses Ditolak! Hanya Super Admin yang diizinkan menambah sistem administrator.');
        }

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:tb_user,username',
            'email' => 'required|email|unique:tb_user,email',
            'password' => 'required|min:6',
            'admin_role' => 'required|in:super,finance,cs'
        ], [
            'username.unique' => 'Username ini sudah dipakai, silakan cari yang lain.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.'
        ]);

        // Simpan Admin Baru ke Database
        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password di enkripsi
            'level' => 'admin',
            'admin_role' => $request->admin_role,
            'is_verified' => 1, // Langsung verified karena dibuat oleh pusat
            'status' => 'offline',
            'status_online' => 'offline'
        ]);

        return back()->with('success', 'Berhasil! Akses Administrator Baru (' . strtoupper($request->admin_role) . ') telah ditambahkan ke sistem.');
    }

    // 3. EDIT PENGGUNA TERINTEGRASI
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Aturan Validasi Dasar
        $rules = [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tb_user,email,' . $id,
            'no_telepon' => 'nullable|string|max:20',
        ];

        // Otoritas Ganda: Jika yang diedit adalah admin, dan yang mengedit adalah Super Admin
        if ($user->level === 'admin' && Auth::user()->admin_role === 'super') {
            $rules['admin_role'] = 'required|in:super,finance,cs';
        }

        // Cek jika kolom password diisi (artinya mau ganti password)
        if ($request->filled('password')) {
            $rules['password'] = 'min:6';
        }

        $request->validate($rules);

        // Eksekusi Pembaruan Data
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->no_telepon = $request->no_telepon;

        // Update role admin (Hanya berlaku jika syarat otoritas terpenuhi)
        if ($user->level === 'admin' && Auth::user()->admin_role === 'super' && $request->has('admin_role')) {
            $user->admin_role = $request->admin_role;
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', "Sempurna! Data profil pengguna {$user->nama} berhasil diperbarui.");
    }

    // 4. BLOKIR ATAU AKTIFKAN AKUN PENGGUNA
    public function toggleBan($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Keamanan Sistem: Anda tidak diizinkan memblokir akun Anda sendiri!');
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        $statusText = $user->is_banned ? 'telah diblokir dari sistem' : 'telah diaktifkan kembali';
        return back()->with('success', "Status Diperbarui: Pengguna {$user->nama} {$statusText}.");
    }

    // 5. EXPORT DATA KE CSV
    public function exportCsv(Request $request)
    {
        $level_filter = $request->get('level', 'semua');
        $query = User::query()->where('level', '!=', 'bot');

        if ($level_filter !== 'semua') {
            $query->where('level', $level_filter);
        }

        $users = $query->latest()->get();

        $filename = "Data_Pengguna_Pondasikita_" . date('Ymd_His') . ".csv";
        $handle = fopen('php://memory', 'w');
        
        fputcsv($handle, ['ID', 'Username', 'Nama', 'Email', 'No Telepon', 'Level', 'Role Admin', 'Status Banned', 'Tanggal Daftar']);

        foreach ($users as $user) {
            fputcsv($handle, [
                $user->id,
                $user->username,
                $user->nama,
                $user->email,
                $user->no_telepon ?? '-',
                strtoupper($user->level),
                strtoupper($user->admin_role ?? '-'),
                $user->is_banned ? 'BANNED' : 'AKTIF',
                $user->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fseek($handle, 0);
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function() use ($handle) {
            fpassthru($handle);
            fclose($handle);
        }, 200, $headers);
    }
}