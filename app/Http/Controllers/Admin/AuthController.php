<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login rahasia
    public function showLoginForm()
    {
        // Jika sudah login dan dia admin, langsung lempar ke dashboard
        if (Auth::check() && Auth::user()->level === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }

    // Proses autentikasi
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            // Cek apakah yang login BENAR-BENAR admin
            if (Auth::user()->level === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Komandan.');
            } else {
                // Jika user biasa nyasar kesini dan coba login, tendang dia keluar!
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error', 'Akses ditolak. Anda bukan Administrator.');
            }
        }

        // Jika email/password salah
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }
}