<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil & Alamat - Pondasikita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar_style.css') }}">
    
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #334155; }
        .edit-container { max-width: 800px; margin: 40px auto; padding: 0 15px; }
        .edit-card { background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
        
        .edit-header { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px dashed #e2e8f0; }
        .edit-header h2 { margin: 0; font-size: 1.5rem; color: #0f172a; }
        .btn-back { color: #64748b; font-size: 1.2rem; text-decoration: none; transition: 0.2s; }
        .btn-back:hover { color: #2563eb; transform: translateX(-5px); }

        .section-title { font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 30px 0 15px 0; display: flex; align-items: center; gap: 8px; }
        .section-title::before { content: ''; width: 4px; height: 18px; background: #2563eb; border-radius: 4px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 8px; color: #475569; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; outline: none; transition: 0.2s; box-sizing: border-box; font-family: 'Inter', sans-serif;}
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        
        .photo-upload-area { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px dashed #cbd5e1; }
        .photo-preview { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .photo-input-group { flex: 1; }
        .photo-input-group p { margin: 0 0 10px 0; font-size: 0.85rem; color: #64748b; }
        .file-input { font-size: 0.9rem; }

        .btn-submit { background: #2563eb; color: white; border: none; padding: 14px 25px; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: 0.2s; width: 100%; margin-top: 20px;}
        .btn-submit:hover { background: #1d4ed8; }

        @media (max-width: 768px) {
            .form-row { grid-template-columns: 1fr; gap: 0; }
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="edit-container">
        <div class="edit-card">
            
            <div class="edit-header">
                <a href="{{ route('profil.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i></a>
                <h2>Edit Profil & Alamat</h2>
            </div>

            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- BAGIAN 1: PROFIL DIRI --}}
                <div class="section-title">Data Diri</div>

                <div class="photo-upload-area">
                    <img src="{{ asset('assets/uploads/avatars/' . ($user->profile_picture_url ?? 'person.png')) }}" 
                         id="preview-img" class="photo-preview"
                         onerror="this.src='{{ asset('assets/uploads/avatars/person.png') }}'">
                    
                    <div class="photo-input-group">
                        <label style="font-weight: 700; color: #0f172a; display: block; margin-bottom: 5px;">Ganti Foto Profil</label>
                        <p>Format JPG, JPEG, PNG. Maksimal 2MB.</p>
                        <input type="file" name="foto" id="foto-input" class="file-input" accept="image/jpeg, image/png, image/jpg">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="number" name="no_telepon" class="form-control" value="{{ old('no_telepon', $user->no_telepon) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">Pilih...</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', empty($user->tanggal_lahir) ? '' : \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d')) }}">
                    </div>
                </div>

                {{-- BAGIAN 2: ALAMAT PENGIRIMAN UTAMA --}}
                <div class="section-title" style="margin-top: 20px;">Alamat Utama Pengiriman</div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Penerima</label>
                        <input type="text" name="nama_penerima" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('nama_penerima', $alamatUtama->nama_penerima ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>No. HP Penerima</label>
                        <input type="number" name="telepon_penerima" class="form-control" placeholder="081234567xxx" value="{{ old('telepon_penerima', $alamatUtama->telepon_penerima ?? '') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Label Alamat</label>
                        <input type="text" name="label_alamat" class="form-control" placeholder="Contoh: Rumah, Kantor, Proyek" value="{{ old('label_alamat', $alamatUtama->label_alamat ?? 'Rumah') }}">
                    </div>
                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input type="number" name="kode_pos" class="form-control" placeholder="Masukan Kode Pos" value="{{ old('kode_pos', $alamatUtama->kode_pos ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" class="form-control" rows="2" placeholder="Nama Jalan, Gedung, No. Rumah, RT/RW, Patokan">{{ old('alamat_lengkap', $alamatUtama->alamat_lengkap ?? '') }}</textarea>
                </div>

                <div class="form-row">
                    {{-- Provinsi --}}
                    <div class="form-group">
                        <label>Provinsi</label>
                        <select name="province_id" id="province_id" class="form-control">
                            <option value="">Pilih Provinsi...</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}" {{ old('province_id', $alamatUtama->province_id ?? '') == $prov->id ? 'selected' : '' }}>
                                    {{ $prov->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kota --}}
                    <div class="form-group">
                        <label>Kota / Kabupaten</label>
                        <select name="city_id" id="city_id" class="form-control">
                            <option value="">Pilih Kota...</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id', $alamatUtama->city_id ?? '') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kecamatan --}}
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <select name="district_id" id="district_id" class="form-control">
                            <option value="">Pilih Kecamatan...</option>
                            @foreach($districts as $dist)
                                <option value="{{ $dist->id }}" {{ old('district_id', $alamatUtama->district_id ?? '') == $dist->id ? 'selected' : '' }}>
                                    {{ $dist->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Simpan Perubahan Profil & Alamat</button>
            </form>
        </div>
    </div>

    @include('partials.footer')

    {{-- SCRIPT --}}
    <script>
        // 1. Preview Gambar Saat Upload
        document.getElementById('foto-input').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById('preview-img').src = URL.createObjectURL(file);
            }
        });

        // 2. Dynamic Dropdown Wilayah (Menggunakan API yang sudah ada di routes/web.php)
        const provSelect = document.getElementById('province_id');
        const citySelect = document.getElementById('city_id');
        const distSelect = document.getElementById('district_id');

        provSelect.addEventListener('change', function() {
            let provId = this.value;
            citySelect.innerHTML = '<option value="">Memuat...</option>';
            distSelect.innerHTML = '<option value="">Pilih Kecamatan...</option>';

            if (provId) {
                fetch(`/api/cities/${provId}`)
                .then(res => res.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">Pilih Kota...</option>';
                    data.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                    });
                });
            } else {
                citySelect.innerHTML = '<option value="">Pilih Kota...</option>';
            }
        });

        citySelect.addEventListener('change', function() {
            let cityId = this.value;
            distSelect.innerHTML = '<option value="">Memuat...</option>';

            if (cityId) {
                fetch(`/api/districts/${cityId}`)
                .then(res => res.json())
                .then(data => {
                    distSelect.innerHTML = '<option value="">Pilih Kecamatan...</option>';
                    data.forEach(dist => {
                        distSelect.innerHTML += `<option value="${dist.id}">${dist.name}</option>`;
                    });
                });
            } else {
                distSelect.innerHTML = '<option value="">Pilih Kecamatan...</option>';
            }
        });
    </script>
</body>
</html>