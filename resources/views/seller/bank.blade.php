@extends('layouts.seller')

@section('title', 'Rekening Bank Toko')

@section('content')

{{-- =========================================================================
     CSS DIMASUKKAN LANGSUNG KE CONTENT AGAR PASTI TER-LOAD OLEH BROWSER
     ========================================================================= --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
<style>
    /* === CSS ISOLATED UNTUK BANK B2B === */
    :root {
        --bnk-dark: #0f172a;
        --bnk-primary: #2563eb;
        --bnk-danger: #ef4444;
        --bnk-border: #e2e8f0;
        --bnk-bg: #f8fafc;
        --text-mut: #64748b;
    }
    .bnk-wrapper { font-family: 'Inter', sans-serif; color: #1e293b; }

    /* HEADER */
    .bnk-header-box { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .bnk-icon { background: var(--bnk-dark); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    
    /* ALERT EDUKASI */
    .security-alert { background: #eff6ff; border: 1px solid #bfdbfe; border-left: 4px solid var(--bnk-primary); padding: 16px 20px; border-radius: 8px; display: flex; gap: 15px; margin-bottom: 24px; }
    .security-alert i { font-size: 1.5rem; color: var(--bnk-primary); }
    .security-alert p { margin: 0; font-size: 13px; color: #1e3a8a; line-height: 1.5; font-weight: 500; }

    /* KARTU REKENING (ATM STYLE) */
    .bank-card { background: white; border: 1px solid var(--bnk-border); border-radius: 16px; padding: 24px; display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: 0.3s; position: relative; overflow: hidden; }
    .bank-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 6px; background: var(--bnk-primary); }
    .bank-card:hover { border-color: #cbd5e1; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }

    .bank-info { display: flex; gap: 20px; align-items: center; }
    .bank-logo-box { width: 70px; height: 70px; background: #f1f5f9; border: 1px solid var(--bnk-border); border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 2rem; color: var(--text-mut); }
    
    .bank-details h5 { font-size: 18px; font-weight: 800; color: var(--bnk-dark); margin: 0 0 4px 0; }
    .bank-details .acc-num { font-family: 'Courier New', Courier, monospace; font-size: 20px; font-weight: 900; color: var(--bnk-primary); letter-spacing: 2px; margin-bottom: 4px; display: block; }
    .bank-details .acc-name { font-size: 14px; font-weight: 600; color: var(--text-mut); text-transform: uppercase; }

    /* KARTU TAMBAH REKENING (EMPTY STATE) */
    .add-bank-card { background: var(--bnk-bg); border: 2px dashed #cbd5e1; border-radius: 16px; padding: 40px 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; color: var(--text-mut); text-decoration: none; }
    .add-bank-card:hover { background: white; border-color: var(--bnk-primary); color: var(--bnk-primary); transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(37,99,235,0.1); }
    .add-bank-card i { font-size: 3rem; margin-bottom: 10px; }
    .add-bank-card span { font-weight: 700; font-size: 15px; }

    /* BUTTONS */
    .btn-group-custom { display: flex; gap: 10px; }
    .btn-act { font-weight: 700; font-size: 13px; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 6px; }
    .btn-edit { background: white; border: 1px solid var(--bnk-dark); color: var(--bnk-dark); }
    .btn-edit:hover { background: var(--bnk-dark); color: white; }
    .btn-del { background: white; border: 1px solid var(--bnk-danger); color: var(--bnk-danger); }
    .btn-del:hover { background: #fef2f2; }

    /* MODAL FORM CSS */
    .modal-content-custom { border-radius: 16px; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .modal-header-custom { background: var(--bnk-bg); border-bottom: 1px solid var(--bnk-border); border-radius: 16px 16px 0 0; padding: 20px 24px; }
    .fm-label { font-weight: 700; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: block; }
    .fm-input { border-radius: 10px; border: 2px solid var(--bnk-border); padding: 12px 16px; font-weight: 600; font-size: 14px; width: 100%; transition: 0.2s; outline: none; }
    .fm-input:focus { border-color: var(--bnk-primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

    /* Override TomSelect agar sempurna di Modal */
    .ts-control { border: 2px solid var(--bnk-border) !important; border-radius: 10px !important; padding: 12px 16px !important; font-size: 14px !important; font-weight: 600 !important; box-shadow: none !important; }
    .ts-control.focus { border-color: var(--bnk-primary) !important; box-shadow: 0 0 0 3px rgba(37,99,235,0.1) !important; }
    .ts-dropdown { border-radius: 10px !important; border: 1px solid var(--bnk-border) !important; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important; font-size: 14px; font-weight: 500; }
    .ts-wrapper.form-select.single.full.has-items { border: none !important; padding: 0 !important; }
</style>


<div class="bnk-wrapper">

    {{-- Notifikasi SweetAlert --}}
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({title: 'Sukses!', text: '{{ session('success') }}', icon: 'success', confirmButtonColor: '#0f172a'}));</script>
    @endif
    @if($errors->any())
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({title: 'Gagal!', text: 'Cek kembali format isian Anda.', icon: 'error', confirmButtonColor: '#ef4444'}));</script>
    @endif

    {{-- HEADER --}}
    <div class="bnk-header-box">
        <div class="bnk-icon"><i class="mdi mdi-bank"></i></div>
        <div>
            <h3 class="m-0 fw-bold fs-4">Rekening Penarikan Dana</h3>
            <p class="m-0 text-muted" style="font-size: 13px;">Kelola rekening bank utama toko untuk menerima pencairan penghasilan Anda.</p>
        </div>
    </div>

    {{-- SECURITY BANNER --}}
    <div class="security-alert">
        <i class="mdi mdi-shield-check"></i>
        <p><strong>Penting:</strong> Pastikan Nama Pemilik Rekening sesuai dengan identitas (KTP) pemilik toko untuk mencegah penolakan transfer oleh sistem perbankan. Pencairan dana memakan waktu maksimal 1x24 Jam kerja.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            
            {{-- LOGIKA TAMPILAN: Jika Punya Rekening vs Belum Punya --}}
            @if($toko->rekening_bank && $toko->nomor_rekening)
                
                {{-- KARTU REKENING AKTIF --}}
                <div class="bank-card">
                    <div class="bank-info">
                        <div class="bank-logo-box"><i class="mdi mdi-bank-transfer"></i></div>
                        <div class="bank-details">
                            <h5>{{ $toko->rekening_bank }}</h5>
                            <span class="acc-num">{{ $toko->nomor_rekening }}</span>
                            <span class="acc-name">A.N. {{ $toko->atas_nama_rekening }}</span>
                            <div class="mt-2">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success fw-bold px-2 py-1" style="font-size: 11px;">
                                    <i class="mdi mdi-check-circle me-1"></i> Rekening Utama Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="btn-group-custom">
                        <button type="button" class="btn-act btn-edit" data-bs-toggle="modal" data-bs-target="#bankModal">
                            <i class="mdi mdi-pencil"></i> Ubah
                        </button>
                        
                        <form action="{{ route('seller.finance.bank.destroy') }}" method="POST" class="m-0 form-delete-bank">
                            @csrf 
                            <button type="button" class="btn-act btn-del btn-delete-confirm">
                                <i class="mdi mdi-trash-can-outline"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>

            @else
                
                {{-- KARTU KOSONG (EMPTY STATE) --}}
                <a href="#" class="add-bank-card" data-bs-toggle="modal" data-bs-target="#bankModal">
                    <i class="mdi mdi-plus-circle-outline"></i>
                    <span>Tambah Rekening Bank Utama</span>
                </a>

            @endif

        </div>
    </div>
</div>

{{-- MODAL TAMBAH / UBAH REKENING --}}
<div class="modal fade" id="bankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <form action="{{ route('seller.finance.bank.update') }}" method="POST">
                @csrf
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title fw-bold" style="color: #0f172a;">
                        <i class="mdi mdi-bank-plus text-primary me-2"></i> Rincian Rekening
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    
                    <div class="mb-4">
                        <label class="fm-label">Pilih Bank <span class="text-danger">*</span></label>
                        {{-- Select box untuk TomSelect --}}
                        <select id="bank-select" name="nama_bank" class="form-select" placeholder="Ketik nama bank..." required>
                            <option value="">Pilih Bank Tujuan...</option>
                            @foreach ($daftar_bank as $bank)
                                <option value="{{ $bank }}" {{ ($toko->rekening_bank == $bank) ? 'selected' : '' }}>
                                    {{ $bank }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="fm-label">Nomor Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="no_rekening" class="fm-input" value="{{ $toko->nomor_rekening }}" placeholder="Contoh: 1234567890" pattern="[0-9]+" title="Hanya boleh berisi angka" required>
                        <small class="text-muted" style="font-size: 11px;">Pastikan nomor rekening tidak mengandung spasi atau tanda baca.</small>
                    </div>
                    
                    <div class="mb-2">
                        <label class="fm-label">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pemilik" class="fm-input" value="{{ $toko->atas_nama_rekening }}" placeholder="Sesuai buku tabungan / KTP" style="text-transform: uppercase;" required>
                    </div>

                </div>
                
                <div class="modal-footer p-4 bg-light border-top rounded-bottom-4 justify-content-end">
                    <button type="button" class="btn btn-act btn-edit me-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-act text-white" style="background: #0f172a;"><i class="mdi mdi-content-save"></i> Simpan Rekening</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

{{-- SCRIPT JUGA KITA JADIKAN SATU DI BAWAH CONTENT --}}
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Inisialisasi TomSelect agar pencarian bank rapi dan cantik
    new TomSelect("#bank-select", {
        create: true, // Izinkan user mengetik bank lokal yang tidak ada di list
        sortField: { field: "text", direction: "asc" },
        dropdownParent: 'body', // Cegah terpotong oleh batas modal
        placeholder: "Cari atau Ketik Nama Bank..."
    });

    // 2. Konfirmasi Hapus Rekening (Mencegah salah klik)
    document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('.form-delete-bank');
            
            Swal.fire({
                title: 'Hapus Rekening?',
                text: "Penarikan dana tidak dapat dilakukan jika Anda menghapus rekening utama ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Rekening',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Loading...';
                    form.submit();
                }
            });
        });
    });

});
</script>