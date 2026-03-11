<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan Saya - Pondasikita</title>
    
    {{-- Memanggil icon FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Memanggil CSS Navbar & Footer Bawaan Anda (Opsional jika ada) --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/navbar_style.css') }}"> 

    <style>
        /* ========================================================
           INTERNAL CSS MURNI - DESAIN ENTERPRISE E-COMMERCE
           ======================================================== */
        body {
            background-color: #f8fafc;
            font-family: 'Inter', 'Segoe UI', Tahoma, sans-serif;
            color: #334155;
            margin: 0;
            padding: 0;
        }

        .order-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 0 20px;
            min-height: 60vh;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .page-header i {
            color: #2563eb;
            font-size: 1.8rem;
        }

        .page-header h3 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        /* --- EMPTY STATE (Jika tidak ada pesanan) --- */
        .empty-state {
            background-color: white;
            border-radius: 20px;
            padding: 60px 20px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px dashed #cbd5e1;
        }
        .empty-state i {
            font-size: 5rem;
            color: #e2e8f0;
            margin-bottom: 20px;
        }
        .empty-state p {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 25px;
        }
        .btn-start-shopping {
            background-color: #2563eb;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .btn-start-shopping:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        /* --- ORDER CARD --- */
        .order-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }
        .order-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 10px 20px rgba(0,0,0,0.06);
            transform: translateY(-2px);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .invoice-label {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 4px;
            display: block;
        }

        .invoice-number {
            font-size: 1.15rem;
            font-weight: 800;
            color: #2563eb;
            margin: 0;
            letter-spacing: 0.5px;
        }

        /* --- BADGE STATUS --- */
        .status-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .bg-warning { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        .bg-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .bg-info { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .bg-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        /* --- ORDER BODY --- */
        .order-body {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 15px;
        }

        .order-date {
            font-size: 0.85rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .order-total {
            font-size: 1.4rem;
            font-weight: 900;
            color: #0f172a;
            margin: 0;
        }

        .btn-track {
            background: transparent;
            border: 2px solid #2563eb;
            color: #2563eb;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .btn-track:hover {
            background: #2563eb;
            color: white;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        /* Responsif untuk layar HP */
        @media (max-width: 600px) {
            .order-body { flex-direction: column; align-items: flex-start; }
            .btn-track { width: 100%; justify-content: center; margin-top: 10px; }
        }
    </style>
</head>
<body>

    {{-- Memasukkan Navbar Secara Manual --}}
    @include('partials.navbar')

    <main class="order-container">
        
        <div class="page-header">
            <i class="fas fa-clipboard-list"></i>
            <h3>Status Pesanan Saya</h3>
        </div>

        @if ($orders->isEmpty())
            
            {{-- TAMPILAN JIKA KOSONG --}}
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>Kamu belum memiliki riwayat pesanan material saat ini.</p>
                <a href="{{ route('produk.index') }}" class="btn-start-shopping">
                    <i class="fas fa-shopping-cart me-2"></i> Mulai Belanja
                </a>
            </div>

        @else
            
            {{-- TAMPILAN JIKA ADA PESANAN --}}
            @foreach($orders as $row)
                
                @php
                    // Logika pewarnaan Badge Status sesuai isi database ENUM Anda
                    $badgeClass = 'bg-info';
                    $statusText = str_replace('_', ' ', $row->status_pesanan_global);
                    
                    if($row->status_pesanan_global == 'menunggu_pembayaran') {
                        $badgeClass = 'bg-warning';
                    } elseif($row->status_pesanan_global == 'selesai') {
                        $badgeClass = 'bg-success';
                    } elseif($row->status_pesanan_global == 'dibatalkan' || $row->status_pesanan_global == 'komplain') {
                        $badgeClass = 'bg-danger';
                    }
                @endphp

                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <span class="invoice-label">Kode Pesanan (Invoice):</span>
                            <h6 class="invoice-number">{{ $row->kode_invoice }}</h6>
                        </div>
                        <div>
                            <span class="status-badge {{ $badgeClass }}">
                                {{ strtoupper($statusText) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-body">
                        <div>
                            <div class="order-date">
                                <i class="far fa-calendar-alt"></i> 
                                {{ \Carbon\Carbon::parse($row->tanggal_transaksi)->format('d M Y, H:i') }} WIB
                            </div>
                            <h5 class="order-total">Rp {{ number_format($row->total_final, 0, ',', '.') }}</h5>
                        </div>
                        
                        <div>
                            {{-- Saya matikan href-nya sementara dengan '#' agar tidak error jika rute lacak belum dibuat --}}
                            <a href="{{ route('pesanan.lacak', $row->kode_invoice) }}" class="btn-track">
                                <i class="fas fa-truck-fast"></i> Rincian & Lacak
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

        @endif

    </main>

    {{-- Memasukkan Footer Secara Manual --}}
    @include('partials.footer')

    {{-- Script Bawaan --}}
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
</body>
</html>