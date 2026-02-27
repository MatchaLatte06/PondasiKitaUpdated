@extends('layouts.seller')

@section('title', 'Rekening Bank')

@push('styles')
    {{-- CSS TomSelect untuk Dropdown yang Bisa Di-search --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    
    <style>
        /* ========================================= */
        /* ==   STYLE REKENING BANK (MONOCHROME)  == */
        /* ========================================= */
        .bank-account-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            background-color: #ffffff;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .bank-account-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border-color: #111827;
        }
        .bank-account-card .bank-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 8px;
            padding: 5px;
            border: 1px solid #f3f4f6;
            background-color: #ffffff;
        }
        .bank-account-card .account-details {
            flex-grow: 1;
        }
        .bank-account-card .account-details h5 {
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
            font-size: 1.1rem;
        }
        .bank-account-card .account-details p {
            color: #6b7280;
            margin-bottom: 0;
            font-size: 0.95rem;
            line-height: 1.4;
        }
        
        .add-bank-card {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            min-height: 110px;
            text-decoration: none;
            color: #6b7280;
            transition: all 0.2s ease;
            background-color: transparent;
        }
        .add-bank-card:hover {
            border-color: #111827;
            color: #111827;
            background-color: #f9fafb;
        }
        .add-bank-card .add-content {
            text-align: center;
        }
        .add-bank-card .mdi {
            font-size: 2.5rem;
            line-height: 1;
        }
        .add-bank-card p {
            font-weight: 600;
            margin: 0.5rem 0 0;
            font-size: 0.95rem;
        }

        /* Tombol Outline Monochrome */
        .btn-mono-outline {
            background-color: transparent;
            border: 1px solid #d1d5db;
            color: #374151;
            border-radius: 8px;
            padding: 0.4rem 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            transition: 0.2s;
        }
        .btn-mono-outline:hover {
            background-color: #fef2f2; /* Merah sangat pudar untuk tombol hapus */
            color: #dc2626;
            border-color: #fca5a5;
        }
        
        /* Tombol Primary Monochrome */
        .btn-mono {
            background-color: #111827;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-mono:hover { background-color: #374151; color: white; }

        /* TomSelect Override Monochrome */
        .ts-control {
            border: 1px solid #e5e7eb !important;
            border-radius: 8px !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.95rem !important;
            background-color: #ffffff !important;
            box-shadow: none !important;
        }
        .ts-control.focus {
            border-color: #111827 !important;
            box-shadow: 0 0 0 2px rgba(17, 24, 39, 0.1) !important;
        }
        .ts-dropdown {
            border: 1px solid #e5e7eb !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1) !important;
            z-index: 1060 !important; /* Di atas Bootstrap Modal */
        }
        .ts-dropdown .option:hover, .ts-dropdown .active {
            background-color: #f3f4f6 !important;
            color: #111827 !important;
        }
    </style>
@endpush

@section('content')
<div class="page-header mb-4">
    <h3 class="page-title d-flex align-items-center m-0">
        <div class="page-title-icon-mono me-3">
            <i class="mdi mdi-bank"></i>
        </div> 
        <div class="d-flex align-items-center" style="font-size: 1.6rem;">
            <a href="{{ route('seller.dashboard') }}" class="header-path-link">Dashboard</a>
            <i class="mdi mdi-chevron-right header-path-separator"></i>
            <span class="header-path-current">Rekening Bank</span>
        </div>
    </h3>
</div>

<div class="card shadow-sm border-0" style="border-radius: 16px; background: transparent;">
    <div class="card-body p-0">
        <div class="row g-4">
            
            {{-- Looping Rekening yang Tersimpan --}}
            @foreach ($rekening_tersimpan as $rek)
            <div class="col-md-6 col-xl-4">
                <div class="bank-account-card">
                    <img src="{{ $rek->logo }}" alt="{{ $rek->nama_bank }}" class="bank-logo">
                    <div class="account-details">
                        <h5>{{ $rek->nama_bank }}</h5>
                        <p>
                            <span style="letter-spacing: 1px; color: #111827;">{{ $rek->no_rekening }}</span><br>
                            <small class="fw-bold">{{ strtoupper($rek->nama_pemilik) }}</small>
                        </p>
                    </div>
                    <div class="actions">
                        <button class="btn-mono-outline"><i class="mdi mdi-trash-can-outline me-1"></i>Hapus</button>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Kartu Tambah Rekening --}}
            <div class="col-md-6 col-xl-4">
                <a href="#" class="add-bank-card" data-bs-toggle="modal" data-bs-target="#addBankModal">
                    <div class="add-content">
                        <i class="mdi mdi-plus-circle-outline"></i>
                        <p>Tambah Rekening Bank</p>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- MODAL TAMBAH REKENING (BOOTSTRAP 5) --}}
<div class="modal fade" id="addBankModal" tabindex="-1" aria-labelledby="addBankModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title fw-bold" id="addBankModalLabel" style="color: #111827;">Tambah Rekening Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="#" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="bank-select" class="form-label fw-bold text-secondary" style="font-size: 0.9rem;">Pilih Bank</label>
                        <select id="bank-select" name="nama_bank" placeholder="Ketik nama bank...">
                            <option value=""></option>
                            @foreach ($daftar_bank as $bank)
                                <option value="{{ $bank }}">{{ $bank }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="no_rekening" class="form-label fw-bold text-secondary" style="font-size: 0.9rem;">Nomor Rekening</label>
                        <input type="text" id="no_rekening" name="no_rekening" class="form-control" placeholder="Contoh: 1234567890" style="border-radius: 8px; padding: 0.75rem 1rem;" required>
                    </div>
                    
                    <div class="mb-2">
                        <label for="nama_pemilik" class="form-label fw-bold text-secondary" style="font-size: 0.9rem;">Nama Pemilik Rekening</label>
                        <input type="text" id="nama_pemilik" name="nama_pemilik" class="form-control" placeholder="Sesuai buku tabungan" style="border-radius: 8px; padding: 0.75rem 1rem;" required>
                    </div>
                </div>
                
                <div class="modal-footer" style="border-top: 1px solid #e5e7eb;">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn-mono">Simpan Rekening</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Inisialisasi TomSelect untuk Dropdown Bank
    new TomSelect("#bank-select", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        // Memaksa dropdown dirender di body agar tidak terpotong oleh overflow modal
        dropdownParent: 'body' 
    });

});
</script>
@endpush