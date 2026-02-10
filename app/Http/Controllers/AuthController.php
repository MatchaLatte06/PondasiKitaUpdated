<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ==========================================================
    // 1. TAMPILKAN HALAMAN LOGIN (GET)
    // ==========================================================
    public function showLogin()
    {
        // Cek apakah user sedang kena limit (blokir sementara)
        $throttleKey = request()->ip();
        $sisaDetik = 0;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $sisaDetik = RateLimiter::availableIn($throttleKey);
        }

        // Ini yang dicari oleh Route::get('/login')
        return view('auth.login_customer', compact('sisaDetik'));
    }

    // ==========================================================
    // 2. PROSES LOGIN (POST)
    // ==========================================================
    public function login(Request $request)
    {
        // 1. Cek Rate Limiter
        $throttleKey = $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak percobaan. Coba lagi dalam $seconds detik.");
        }

        // 2. Validasi Input
        $request->validate([
            'username' => 'required', 
            'password' => 'required',
        ]);

        // 3. Deteksi login pakai Email atau Username
        $inputType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 4. Siapkan Kredensial
        $credentials = [
            $inputType => $request->username,
            'password' => $request->password,
        ];

        // 5. Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey); 

            // Redirect sesuai Level
            $role = Auth::user()->level;
            if ($role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($role === 'seller') {
                return redirect()->intended('/seller/dashboard');
            } else {
                return redirect()->intended('/'); 
            }
        }

        // 6. Jika Gagal
        RateLimiter::hit($throttleKey); 
        return back()->with('error', 'Username atau Password salah.');
    }

    // ==========================================================
    // 3. PROSES LOGOUT
    // ==========================================================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // ==========================================================
    // 4. REGISTER (Website Version)
    // ==========================================================
    public function register(Request $request)
    {
        // Validasi
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tb_user',
            'username' => 'required|unique:tb_user',
            'password' => 'required|min:6',
            'no_telepon' => 'required',
        ]);

        // Simpan User
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'no_telepon' => $request->no_telepon,
            'password' => Hash::make($request->password),
            'level' => 'customer',
            'status' => 'offline',
            'is_verified' => 1
        ]);

        // Redirect ke login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}