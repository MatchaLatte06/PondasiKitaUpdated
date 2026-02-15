<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buka Toko - Pondasikita</title>
    
    {{-- CSS --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/auth_style_customer.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        /* Styling Select2 agar seragam dengan input form */
        .select2-container .select2-selection--single {
            height: 45px !important;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            right: 10px;
        }
        .form-wrapper { max-width: 700px; margin: 0 auto; }
        .section-title {
            color: #ff6f00; 
            border-bottom: 2px solid #ff6f00; 
            padding-bottom: 5px; 
            margin-bottom: 15px; 
            margin-top: 25px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="auth-container seller-theme">
        <div class="auth-sidebar">
            <div>
                {{-- Pastikan path logo benar --}}
                <img src="{{ asset('assets/image/Pondasikita.com.png') }}" alt="Logo Pondasikita" class="logo" style="border-radius: 10px;">
                <h1>Jadilah Partner Kami.</h1>
                <p>Jangkau lebih banyak pelanggan dan kelola bisnis Anda dengan mudah.</p>
            </div>
            <span>© Pondasikita {{ date('Y') }}</span>
        </div>
        
        <div class="auth-main">
            <div class="form-wrapper">
                <h2 class="text-center">Formulir Pendaftaran Toko</h2>
                <p class="text-center">Sudah punya akun? <a href="{{ route('seller.login') }}">Masuk di sini</a></p>
                
                {{-- Alert Validasi Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger" style="background:#ffebee; color:#c62828; padding:10px; border-radius:5px; margin-bottom:15px;">
                        <ul style="margin:0; padding-left:20px;">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('seller.register.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- Input Hidden Level Seller --}}
                    <input type="hidden" name="level" value="seller"> 

                    <h4 class="section-title">1. Informasi Pemilik</h4>
                    
                    <div class="form-group">
                        <label>Nama Lengkap Pemilik (Sesuai KTP)</label>
                        <input type="text" name="nama_pemilik" required value="{{ old('nama_pemilik') }}" placeholder="Contoh: Budi Santoso">
                    </div>
                    
                    <div class="row" style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Username</label>
                            <input type="text" name="username" required value="{{ old('username') }}" placeholder="username_anda">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Email</label>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="email@contoh.com">
                        </div>
                    </div>

                    <div class="row" style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Kata Sandi</label>
                            <input type="password" name="password" required placeholder="Minimal 6 karakter">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>No. HP (Pribadi)</label>
                            <input type="number" name="no_telepon" required value="{{ old('no_telepon') }}" placeholder="0812...">
                        </div>
                    </div>

                    <h4 class="section-title">2. Informasi Toko</h4>
                    <div class="form-group">
                        <label>Nama Toko</label>
                        <input type="text" name="nama_toko" required value="{{ old('nama_toko') }}" placeholder="Contoh: TB. Maju Jaya">
                    </div>
                    <div class="form-group">
                        <label>No. Telepon Toko / WhatsApp Bisnis</label>
                        <input type="number" name="telepon_toko" required value="{{ old('telepon_toko') }}" placeholder="0812...">
                    </div>
                    
                    {{-- Dropdown Wilayah --}}
                    <div class="form-group">
                        <label>Provinsi</label>
                        <select id="provinsi" name="province_id" class="select2" required style="width: 100%;">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach(DB::table('provinces')->orderBy('name')->get() as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row" style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Kota / Kabupaten</label>
                            <select id="kota" name="city_id" class="select2" required disabled style="width: 100%;">
                                <option value="">-- Pilih Provinsi Dulu --</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Kecamatan</label>
                            <select id="kecamatan" name="district_id" class="select2" required disabled style="width: 100%;">
                                <option value="">-- Pilih Kota Dulu --</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat Lengkap Toko</label>
                        <textarea name="alamat_toko" rows="3" placeholder="Nama Jalan, RT/RW, Patokan..." required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">{{ old('alamat_toko') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Logo Toko (Opsional)</label>
                        <input type="file" name="logo_toko" accept="image/*" style="border: 1px solid #ccc; padding: 5px; width: 100%; border-radius: 5px;">
                    </div>

                    <button type="submit" class="btn-submit" style="margin-top:20px;">DAFTAR SEKARANG</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Script --}}
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // 1. Inisialisasi Select2
    $('.select2').select2();

    // 2. Logic Provinsi -> Kota
    $('#provinsi').on('change', function() {
        let provinceId = $(this).val();
        
        // Kosongkan dropdown di bawahnya
        $('#kota').empty().append('<option value="">Memuat...</option>').prop('disabled', true);
        $('#kecamatan').empty().append('<option value="">-- Pilih Kota Dulu --</option>').prop('disabled', true);
        
        // Refresh tampilan Select2
        $('#kota').trigger('change');
        $('#kecamatan').trigger('change');

        if(provinceId) {
            $.ajax({
                url: '/api/cities/' + provinceId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log("Data Kota:", data); // Cek Console browser Anda
                    
                    $('#kota').empty().append('<option value="">-- Pilih Kota/Kabupaten --</option>');
                    
                    $.each(data, function(key, value) {
                        $('#kota').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                    
                    $('#kota').prop('disabled', false);
                    $('#kota').trigger('change'); // WAJIB ADA
                },
                error: function(xhr) {
                    console.error("Error Kota:", xhr);
                    $('#kota').empty().append('<option value="">Gagal Memuat</option>');
                }
            });
        }
    });

    // 3. Logic Kota -> Kecamatan
    $('#kota').on('change', function() {
        let cityId = $(this).val();
        
        if(cityId) {
            $('#kecamatan').empty().append('<option value="">Memuat...</option>').prop('disabled', true);
            $('#kecamatan').trigger('change');

            $.ajax({
                url: '/api/districts/' + cityId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log("Data Kecamatan:", data); // Cek Console browser jika masih error
                    
                    $('#kecamatan').empty().append('<option value="">-- Pilih Kecamatan --</option>');
                    
                    // Loop data
                    $.each(data, function(key, value) {
                        $('#kecamatan').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                    
                    // INI YANG SEBELUMNYA KURANG:
                    $('#kecamatan').prop('disabled', false);
                    $('#kecamatan').trigger('change'); // Beritahu Select2 ada data baru
                },
                error: function(xhr) {
                    console.error("Error Kecamatan:", xhr);
                    $('#kecamatan').empty().append('<option value="">Gagal Memuat</option>');
                }
            });
        } else {
            $('#kecamatan').empty().append('<option value="">-- Pilih Kota Dulu --</option>').prop('disabled', true);
            $('#kecamatan').trigger('change');
        }
    });
});
</script>
</body>
</html>