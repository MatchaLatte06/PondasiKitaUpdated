<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * REGISTER (Daftar Akun Baru untuk Customer)
     */
    public function register(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tb_user', // Cek unik di tabel tb_user
            'username' => 'required|string|max:50|unique:tb_user',
            'password' => 'required|string|min:6',
            'no_telepon' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // 2. Buat User Baru
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'no_telepon' => $request->no_telepon,
            'password' => Hash::make($request->password), // Enkripsi password
            'level' => 'customer', // Default user baru adalah customer
            'status' => 'active',
            'is_verified' => 1 // Opsional: Langsung verifikasi atau butuh email dulu
        ]);

        // 3. Buat Token agar langsung login
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    /**
     * LOGIN (Masuk Aplikasi)
     */
    public function login(Request $request)
    {
        // 1. Validasi Input tidak boleh kosong
        if (!$request->email || !$request->password) {
            return response()->json([
                'message' => 'Email dan Password wajib diisi'
            ], 400);
        }

        // 2. Cari User berdasarkan Email
        $user = User::where('email', $request->email)->first();

        // 3. Cek apakah User ada DAN Password cocok (Hash::check membaca bcrypt)
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau Password salah'
            ], 401);
        }

        // 4. (Opsional) Cek apakah user dibanned
        if ($user->is_banned) {
            return response()->json(['message' => 'Akun Anda telah dibekukan.'], 403);
        }

        // 5. Hapus token lama (biar fresh) & Buat Token Baru
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        // 6. Kirim respon JSON ke React Native
        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
                'level' => $user->level, // Penting: admin/seller/customer
                'profile_picture' => $user->profile_picture_url
            ]
        ], 200);
    }

    /**
     * LOGOUT (Keluar Aplikasi)
     */
    public function logout(Request $request)
    {
        // Hapus token yang sedang dipakai
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    /**
     * CEK PROFILE (Ambil data user yang sedang login)
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}