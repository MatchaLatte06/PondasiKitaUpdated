<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya - {{ $user->nama }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar_style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profil_style.css') }}"> 

    {{-- CSS Fallback agar profil tetap terlihat rapi meskipun profil_style.css tidak ada --}}
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #334155; }
        .profile-container { max-width: 800px; margin: 40px auto; padding: 0 15px; }
        .profile-header { text-align: center; margin-bottom: 30px; }
        .profile-header h2 { font-size: 1.8rem; color: #0f172a; margin: 0 0 10px 0; display: flex; align-items: center; justify-content: center; gap: 10px;}
        .profile-header p { color: #64748b; margin: 0; font-size: 0.95rem; }
        
        .profile-card { background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; gap: 40px; border: 1px solid #f1f5f9; }
        
        .profile-picture-section { display: flex; flex-direction: column; align-items: center; gap: 15px; width: 200px; border-right: 1px dashed #e2e8f0; padding-right: 40px; }
        .profile-picture { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #eff6ff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        
        .profile-details-section { flex: 1; }
        .profile-details-list { display: grid; grid-template-columns: 150px 1fr; gap: 15px; margin: 0 0 30px 0; }
        .profile-details-list dt { font-weight: 600; color: #64748b; font-size: 0.95rem; }
        .profile-details-list dd { margin: 0; color: #0f172a; font-weight: 500; font-size: 0.95rem; }
        
        .profile-actions { display: flex; gap: 15px; border-top: 1px dashed #e2e8f0; padding-top: 20px; }
        .btn-primary, .btn-secondary { padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; cursor: pointer; border: none; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { background: #f1f5f9; color: #475569; }
        .btn-secondary:hover { background: #e2e8f0; }

        @media (max-width: 768px) {
            .profile-card { flex-direction: column; padding: 25px; }
            .profile-picture-section { width: 100%; border-right: none; border-bottom: 1px dashed #e2e8f0; padding-right: 0; padding-bottom: 25px; }
            .profile-details-list { grid-template-columns: 1fr; gap: 5px; }
            .profile-details-list dt { margin-top: 10px; }
        }
    </style>
</head>
<body>
    
    @include('partials.navbar')

    <div class="profile-container">
        <div class="profile-header">
            <h2><i class="fa-solid fa-user-circle text-blue-500"></i> Profil Saya</h2>
            <p>Kelola informasi profil Anda untuk mengontrol, melindungi, dan mengamankan akun.</p>
        </div>

        <div class="profile-card">
          {{-- Foto Profil --}}
            <div class="profile-picture-section">
                {{-- Menggunakan person.png sebagai default --}}
                <img src="{{ asset('assets/uploads/avatars/' . ($user->profile_picture_url ?? 'person.png')) }}" 
                     alt="Foto Profil" 
                     class="profile-picture"
                     onerror="this.onerror=null;this.src='{{ asset('assets/uploads/avatars/person-icon-1680.png') }}';">
                
                {{-- Tombol kecil untuk ubah foto (opsional) --}}
                <button class="btn-secondary" style="font-size: 0.8rem; padding: 6px 12px;" onclick="alert('Fitur upload foto segera hadir!')">
                    Pilih Gambar
                </button>
            </div>
            
            {{-- Detail Profil --}}
            <div class="profile-details-section">
                <dl class="profile-details-list">
                    <dt>Username</dt>
                    <dd>{{ $user->username }}</dd>

                    <dt>Nama Lengkap</dt>
                    <dd>{{ $user->nama ?? '-' }}</dd>

                    <dt>Email</dt>
                    <dd>{{ $user->email ?? '-' }}</dd>

                    <dt>Nomor Telepon</dt>
                    <dd>{{ $user->no_telepon ?? '-' }}</dd>

                    <dt>Jenis Kelamin</dt>
                    <dd>{{ ucfirst($user->jenis_kelamin ?? '-') }}</dd>

                    <dt>Tanggal Lahir</dt>
                    <dd>{{ !empty($user->tanggal_lahir) ? \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</dd>

                    <dt>Alamat Utama</dt>
                    {{-- Gunakan {!! !!} agar tag <br> terbaca sebagai baris baru, bukan sebagai teks --}}
                    <dd>{!! $alamatLengkapFormatted !!}</dd>
                    
                    <dt>Tanggal Bergabung</dt>
                    <dd>{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') }}</dd>
                </dl>
                
                {{-- Tombol Aksi --}}
                <div class="profile-actions">
                    {{-- Note: Rute untuk edit profil disiapkan menggunakan url() sementara --}}
                    {{-- Tombol Edit mengarah ke rute baru --}}
                    <a href="{{ route('profil.edit') }}" class="btn-primary"><i class="fa-solid fa-pen-to-square"></i> Edit Profil</a>
                    <a href="#" class="btn-secondary" onclick="alert('Fitur Ganti Password sedang dibangun.')"><i class="fa-solid fa-key"></i> Ganti Password</a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    </script>
</body>
</html>