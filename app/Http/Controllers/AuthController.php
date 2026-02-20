<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Toko; // Pastikan Model Toko sudah ada (sesuai langkah sebelumnya)

class AuthController extends Controller
{
    // ==========================================================
    // 1. HALAMAN LOGIN (CUSTOMER)
    // ==========================================================
    public function showLogin()
    {
        $throttleKey = request()->ip();
        $sisaDetik = 0;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $sisaDetik = RateLimiter::availableIn($throttleKey);
        }

        return view('auth.login_customer', compact('sisaDetik'));
    }

    // ==========================================================
    // 2. HALAMAN LOGIN (SELLER)
    // ==========================================================
    public function showLoginSeller()
    {
        $throttleKey = request()->ip();
        $sisaDetik = 0;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $sisaDetik = RateLimiter::availableIn($throttleKey);
        }

        return view('auth.login_seller', compact('sisaDetik'));
    }

// ==========================================================
    // LOGIN ADMIN
    // ==========================================================

    // 1. Menampilkan Halaman Login Admin
    public function showLoginAdmin()
    {
        // Jika sudah login dan levelnya admin, langsung ke dashboard
        if (Auth::check() && Auth::user()->level === 'admin') {
            return redirect('/admin/dashboard');
        }

        return view('auth.login_admin');
    }

    // 2. Memproses Data Login Admin
    public function loginAdmin(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek apakah input berupa email atau username
        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Syarat login: Kredensial benar DAN levelnya harus admin
        $credentials = [
            $loginType => $request->username,
            'password' => $request->password,
            'level'    => 'admin' 
        ];

        // Ambil nilai checkbox 'Ingat Saya'
        $remember = $request->has('remember');

        // Eksekusi Login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        // Jika Gagal (Cek apakah dia user biasa yang salah kamar)
        $userExists = User::where($loginType, $request->username)->first();
        if ($userExists && $userExists->level !== 'admin') {
            return back()->with('error', 'Akses ditolak! Akun ini bukan akun Admin.')->withInput();
        }

        // Jika username/password memang salah
        return back()->with('error', 'Username atau Password salah.')->withInput();
    }

    // ==========================================================
    // 3. PROSES LOGIN (UMUM: CUSTOMER & SELLER)
    // ==========================================================
    public function login(Request $request)
    {
        // 1. Cek Rate Limiter (Anti Brute Force)
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

        // 3. Deteksi Input (Email atau Username)
        $inputType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $inputType => $request->username,
            'password' => $request->password,
        ];

        // 4. Proses Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey); 

            $user = Auth::user();
            
            // Redirect Sesuai Level
            if ($user->level === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->level === 'seller') {
                return redirect()->intended('/seller/dashboard');
            } else {
                return redirect()->intended('/'); 
            }
        }

        // 5. Jika Gagal
        RateLimiter::hit($throttleKey); 
        return back()->with('error', 'Username atau Password salah.');
    }

    // ==========================================================
    // 4. PROSES LOGOUT
    // ==========================================================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
    public function logoutSeller(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/seller/login');
    }

    // ==========================================================
    // 5. REGISTER CUSTOMER (VIEW & PROCESS)
    // ==========================================================
    public function showRegister()
    {
        return view('auth.register_customer');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tb_user', // Sesuai nama tabel tb_user
            'username' => 'required|unique:tb_user',
            'password' => 'required|min:6',
            'no_telepon' => 'required|numeric',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'no_telepon' => $request->no_telepon,
            'password' => Hash::make($request->password),
            'level' => 'customer',
            'status' => 'offline',
            'is_verified' => 1,
            'is_banned' => 0
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ==========================================================
    // 6. REGISTER SELLER (VIEW & PROCESS)
    // ==========================================================
    public function showRegisterSeller()
    {
        // Ambil Data Provinsi untuk Dropdown
        $provinces = DB::table('provinces')->orderBy('name', 'ASC')->get();
        return view('auth.register_seller', compact('provinces'));
    }

    public function registerSeller(Request $request)
    {
        // 1. Validasi Input Lengkap
        $request->validate([
            // Data Pemilik (User)
            'nama_pemilik' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:tb_user,username',
            'email' => 'required|email|unique:tb_user,email',
            'password' => 'required|min:6',
            'telepon_toko' => 'required|numeric', // Digunakan juga sebagai no_telepon user
            
            // Data Toko
            'nama_toko' => 'required|string|max:100|unique:tb_toko,nama_toko',
            'alamat_toko' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'logo_toko' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Maks 2MB
        ]);

        // 2. Database Transaction (Agar Data User & Toko Masuk Bersamaan)
        DB::beginTransaction();

        try {
            // A. Upload Logo Toko (Jika Ada)
            $logoPath = null;
            if ($request->hasFile('logo_toko')) {
                $file = $request->file('logo_toko');
                $filename = time() . '_' . Str::slug($request->nama_toko) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/toko'), $filename); // Simpan di public/uploads/toko
                $logoPath = $filename;
            }

            // B. Buat User Baru (Level Seller)
            $user = User::create([
                'nama' => $request->nama_pemilik,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_telepon' => $request->telepon_toko, 
                'level' => 'seller',
                'status' => 'offline',
                'is_verified' => 1, 
                'is_banned' => 0
            ]);

            // C. Buat Slug Toko Unik
            $slug = Str::slug($request->nama_toko);
            if (Toko::where('slug', $slug)->exists()) {
                $slug .= '-' . time();
            }

            // D. Simpan Data Toko
            Toko::create([
                'user_id' => $user->id,
                'nama_toko' => $request->nama_toko,
                'slug' => $slug,
                'telepon_toko' => $request->telepon_toko,
                'alamat_toko' => $request->alamat_toko,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'district_id' => $request->district_id,
                'logo_toko' => $logoPath,
                'status' => 'active', 
                'status_operasional' => 'Buka'
            ]);

            DB::commit();

            // PERBAIKAN: Redirect ke seller.login, bukan route('login')
            return redirect()->route('seller.login')->with('success', 'Pendaftaran Toko Berhasil! Akun Seller Anda sudah aktif, silakan masuk ke Seller Centre.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mendaftar: ' . $e->getMessage())->withInput();
        }
    }

    // ==========================================================
    // 7. API WILAYAH (AJAX)
    // ==========================================================
    public function getCities($provinceId)
    {
        $cities = DB::table('cities')
            ->where('province_id', $provinceId)
            ->orderBy('name', 'ASC')
            ->get(); 
        return response()->json($cities);
    }

    public function getDistricts($cityId)
    {
        $districts = DB::table('districts')
            ->where('city_id', $cityId)
            ->orderBy('name', 'ASC')
            ->get();
        return response()->json($districts);
    }
}