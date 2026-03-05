<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Pembeli - Pondasikita</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/auth_style_customer.css') }}" />
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .auth-container {
            display: flex;
            width: 100%;
            max-width: 900px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 20px;
        }

        /* --- BAGIAN KIRI: FORMULIR --- */
        .auth-form-section {
            flex: 1;
            padding: 50px;
            background: #ffffff;
        }

        .auth-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #111827;
            margin: 0 0 5px 0;
        }

        .auth-subtitle {
            font-size: 0.95rem;
            color: #6b7280;
            margin-bottom: 30px;
        }

        .auth-subtitle a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-subtitle a:hover {
            text-decoration: underline;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #111827;
            box-sizing: border-box;
            transition: border-color 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: #111827;
        }

        .btn-submit {
            width: 100%;
            background-color: #18181b; /* Warna tombol hitam */
            color: #ffffff;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #27272a;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.8rem;
            margin-top: 5px;
            display: block;
            font-weight: 500;
        }

        /* --- BAGIAN KANAN: BANNER HITAM --- */
        .auth-banner-section {
            flex: 1;
            background-color: #27272a; /* Warna gelap sesuai gambar */
            color: #ffffff;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .banner-title {
            font-size: 2.2rem;
            font-weight: 800;
            margin: 0 0 15px 0;
            line-height: 1.3;
        }

        .banner-text {
            font-size: 1rem;
            color: #d4d4d8;
            line-height: 1.6;
            max-width: 85%;
        }

        .banner-footer {
            position: absolute;
            bottom: 30px;
            font-size: 0.8rem;
            color: #a1a1aa;
        }

        /* Responsif untuk HP */
        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
            }
            .auth-form-section {
                padding: 30px 20px;
            }
            .auth-banner-section {
                padding: 40px 20px;
                min-height: 250px;
            }
        }
    </style>
</head>
<body>

    <div class="auth-container">
        {{-- KIRI: FORMULIR --}}
        <div class="auth-form-section">
            <h2 class="auth-title">Buat Akun Pembeli</h2>
            <p class="auth-subtitle">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>

            <form action="{{ route('register.process') }}" method="POST" id="registerForm">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Pengguna</label>
                    <input type="text" name="username" class="form-input" placeholder="test" value="{{ old('username') }}" required>
                    @error('username') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-input" placeholder="test reg" value="{{ old('nama') }}" required>
                    @error('nama') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="test@gmail.com" value="{{ old('email') }}" required>
                    @error('email') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" name="password" class="form-input" placeholder="Minimal 8 karakter" required>
                    @error('password') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi kata sandi" required>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">Daftar Sekarang</button>
            </form>
        </div>

        {{-- KANAN: BANNER --}}
        <div class="auth-banner-section">
            <h2 class="banner-title">Selamat Datang di<br>Pondasikita.</h2>
            <p class="banner-text">Platform terpercaya untuk semua kebutuhan bahan bangunan Anda.</p>
            <div class="banner-footer">
                © Pondasikita 2025
            </div>
        </div>
    </div>

    {{-- SWEETALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');
            
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            });

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#18181b',
                    confirmButtonText: 'Lanjut Login',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: `
                        <div style="text-align: left; font-size: 0.9rem; color: #dc2626;">
                            <ul style="padding-left: 20px; margin: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    `,
                    confirmButtonColor: '#dc2626'
                });
            @endif
        });
    </script>
</body>
</html>