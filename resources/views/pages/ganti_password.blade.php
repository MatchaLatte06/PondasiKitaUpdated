<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - Pondasikita</title>
    
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar_style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #334155; }
        .password-container { max-width: 500px; margin: 60px auto; padding: 0 15px; }
        
        .card-auth { background: white; border-radius: 16px; padding: 40px 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; }
        .card-auth h2 { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 10px; text-align: center; }
        .card-auth p { text-align: center; color: #64748b; font-size: 0.95rem; margin-bottom: 30px; line-height: 1.5; }

        .form-group { margin-bottom: 20px; position: relative; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 8px; color: #475569; }
        
        .input-group { position: relative; display: flex; align-items: center; }
        .input-group i.icon-left { position: absolute; left: 15px; color: #94a3b8; }
        .form-control { width: 100%; padding: 12px 40px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.95rem; transition: 0.3s; outline: none; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        
        /* Tombol Mata (Toggle Password) */
        .toggle-password { position: absolute; right: 15px; cursor: pointer; color: #94a3b8; font-size: 1rem; transition: 0.2s; }
        .toggle-password:hover { color: #3b82f6; }

        .text-danger-msg { color: #ef4444; font-size: 0.8rem; margin-top: 5px; display: block; font-weight: 500; }

        .btn-submit { width: 100%; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 10px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: 0.2s; margin-top: 10px; }
        .btn-submit:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(37,99,235,0.2); }
        .btn-back { display: block; text-align: center; margin-top: 20px; color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: 0.2s; }
        .btn-back:hover { color: #2563eb; }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="password-container">
        <div class="card-auth">
            <div class="text-center mb-3">
                <div style="background: #eff6ff; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                    <i class="fas fa-shield-alt text-blue-600" style="font-size: 24px;"></i>
                </div>
            </div>
            <h2>Ganti Password</h2>
            <p>Pastikan password baru Anda kuat dan tidak mudah ditebak oleh orang lain.</p>

            <form action="{{ route('profil.password.update') }}" method="POST">
                @csrf

                {{-- Password Lama --}}
                <div class="form-group">
                    <label class="form-label">Password Saat Ini</label>
                    <div class="input-group">
                        <i class="fas fa-lock icon-left"></i>
                        <input type="password" name="password_lama" id="pwd_lama" class="form-control" placeholder="Masukkan password lama" required>
                        <i class="fas fa-eye toggle-password" onclick="toggleVisibility('pwd_lama', this)"></i>
                    </div>
                    @error('password_lama') <span class="text-danger-msg">{{ $message }}</span> @enderror
                </div>

                {{-- Password Baru --}}
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <div class="input-group">
                        <i class="fas fa-key icon-left"></i>
                        <input type="password" name="password_baru" id="pwd_baru" class="form-control" placeholder="Minimal 8 karakter" required>
                        <i class="fas fa-eye toggle-password" onclick="toggleVisibility('pwd_baru', this)"></i>
                    </div>
                    @error('password_baru') <span class="text-danger-msg">{{ $message }}</span> @enderror
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div class="form-group">
                    <label class="form-label">Ulangi Password Baru</label>
                    <div class="input-group">
                        <i class="fas fa-check-circle icon-left"></i>
                        {{-- Namanya wajib 'password_baru_confirmation' agar dibaca oleh fungsi validate Laravel --}}
                        <input type="password" name="password_baru_confirmation" id="pwd_confirm" class="form-control" placeholder="Ketik ulang password baru" required>
                        <i class="fas fa-eye toggle-password" onclick="toggleVisibility('pwd_confirm', this)"></i>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Simpan Password Baru</button>
                
                <a href="{{ route('profil.index') }}" class="btn-back"><i class="fas fa-arrow-left me-1"></i> Kembali ke Profil</a>
            </form>
        </div>
    </div>

    @include('partials.footer')

    {{-- Script untuk Mata Pengintip (Show/Hide Password) & SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleVisibility(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', confirmButtonColor: '#2563eb' });
            @endif

            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', confirmButtonColor: '#ef4444' });
            @endif
        });
    </script>
</body>
</html>