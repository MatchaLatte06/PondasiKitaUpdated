<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Pondasikita</title>
    
    {{-- Tailwind CSS CDN + Config Dewa --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8' },
                        surface: '#fcfcfd',
                    },
                    boxShadow: {
                        'soft': '0 4px 40px -4px rgba(0,0,0,0.03)',
                        'float': '0 10px 30px -5px rgba(0,0,0,0.08)',
                        'glow': '0 0 20px rgba(37,99,235,0.2)',
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f4f5; }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.5); }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f4f4f5; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="text-zinc-800 antialiased pt-[80px]">

    {{-- Include Navbar --}}
    @include('partials.navbar')

    <main class="max-w-[1100px] mx-auto px-4 sm:px-6 py-10 lg:py-16">

        {{-- HEADER & STATS SUMMARY --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <h1 class="text-3xl lg:text-4xl font-black text-black tracking-tight">Pesanan Saya</h1>
                <p class="text-sm font-medium text-zinc-500 mt-2">Pantau status pengadaan material dan riwayat transaksi proyek Anda.</p>
            </div>
            
            {{-- Mini Stats (Membuat user merasa progresif) --}}
            <div class="flex items-center gap-3">
                <div class="bg-white border border-zinc-200 px-5 py-3 rounded-2xl shadow-soft">
                    <span class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest">Total Pesanan</span>
                    <span class="text-xl font-black text-black leading-none">{{ $orders->count() }}</span>
                </div>
                <div class="bg-blue-600 px-5 py-3 rounded-2xl shadow-glow">
                    <span class="block text-[10px] font-black text-blue-200 uppercase tracking-widest">Aktif</span>
                    <span class="text-xl font-black text-white leading-none">
                        {{ $orders->where('status_pesanan_global', '!=', 'selesai')->where('status_pesanan_global', '!=', 'dibatalkan')->count() }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ======================================================= --}}
        {{-- EMPTY STATE --}}
        {{-- ======================================================= --}}
        @if ($orders->isEmpty())
            <div class="bg-white rounded-[3rem] shadow-soft border border-zinc-200 p-16 text-center animate-fade-in flex flex-col items-center justify-center">
                <div class="relative mb-8">
                    <div class="w-24 h-24 bg-zinc-50 rounded-full flex items-center justify-center shadow-inner">
                        <i class="fas fa-box-open text-4xl text-zinc-300"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center animate-bounce">
                        <i class="fas fa-search text-blue-500 text-xs"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-black text-black mb-3">Belum Ada Material Dipesan</h2>
                <p class="text-zinc-500 font-medium max-w-sm mb-10 leading-relaxed">Mulai bangun proyek impianmu sekarang. Telusuri katalog material terlengkap kami.</p>
                <a href="{{ route('produk.index') }}" class="bg-black hover:bg-blue-600 text-white font-bold py-4 px-10 rounded-2xl transition-all shadow-xl hover:-translate-y-1">
                    <i class="fas fa-shopping-cart mr-2"></i> Belanja Material
                </a>
            </div>

        {{-- ======================================================= --}}
        {{-- ORDER LIST --}}
        {{-- ======================================================= --}}
        @else
            <div class="space-y-6">
                @foreach($orders as $row)
                    @php
                        // Logika Warna & Ikon Status
                        $statusCfg = [
                            'menunggu_pembayaran' => ['color' => 'bg-amber-50 text-amber-600 border-amber-100', 'icon' => 'fa-clock'],
                            'diproses' => ['color' => 'bg-blue-50 text-blue-600 border-blue-100', 'icon' => 'fa-cog fa-spin'],
                            'dikirim' => ['color' => 'bg-indigo-50 text-indigo-600 border-indigo-100', 'icon' => 'fa-truck-fast'],
                            'selesai' => ['color' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'icon' => 'fa-check-double'],
                            'dibatalkan' => ['color' => 'bg-red-50 text-red-600 border-red-100', 'icon' => 'fa-circle-xmark'],
                            'default' => ['color' => 'bg-zinc-50 text-zinc-600 border-zinc-100', 'icon' => 'fa-circle-info'],
                        ];
                        $cfg = $statusCfg[$row->status_pesanan_global] ?? $statusCfg['default'];
                    @endphp

                    <div class="bg-white rounded-[2rem] shadow-soft border border-zinc-200 overflow-hidden transition-all duration-300 hover:shadow-float group">
                        {{-- Card Header --}}
                        <div class="bg-zinc-50/50 border-b border-zinc-100 px-6 sm:px-10 py-5 flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white border border-zinc-200 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-file-invoice text-zinc-400 group-hover:text-blue-600 transition-colors"></i>
                                </div>
                                <div>
                                    <span class="block text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-0.5">Invoice ID</span>
                                    <h6 class="font-black text-black tracking-tight leading-none uppercase">{{ $row->kode_invoice }}</h6>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-bold text-zinc-400 hidden sm:block">STATUS AKHIR:</span>
                                <div class="px-4 py-1.5 rounded-full border {{ $cfg['color'] }} text-[10px] font-black tracking-widest uppercase flex items-center gap-2 shadow-sm">
                                    <i class="fas {{ $cfg['icon'] }}"></i>
                                    {{ str_replace('_', ' ', $row->status_pesanan_global) }}
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="px-6 sm:px-10 py-8 flex flex-col md:flex-row md:items-center justify-between gap-8">
                            
                            {{-- Info Utama --}}
                            <div class="flex items-center gap-8">
                                {{-- Date --}}
                                <div class="hidden sm:block border-r border-zinc-100 pr-8">
                                    <span class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest mb-1.5">Waktu Transaksi</span>
                                    <div class="flex items-center gap-3">
                                        <div class="text-center">
                                            <span class="block text-xl font-black text-black leading-none">{{ \Carbon\Carbon::parse($row->tanggal_transaksi)->format('d') }}</span>
                                            <span class="text-[10px] font-bold text-zinc-500 uppercase">{{ \Carbon\Carbon::parse($row->tanggal_transaksi)->format('M') }}</span>
                                        </div>
                                        <div class="h-8 w-px bg-zinc-200"></div>
                                        <span class="text-xs font-bold text-zinc-500">{{ \Carbon\Carbon::parse($row->tanggal_transaksi)->format('H:i') }} WIB</span>
                                    </div>
                                </div>

                                {{-- Total --}}
                                <div>
                                    <span class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest mb-1">Total Nilai Kontrak</span>
                                    <div class="text-2xl font-black text-black tracking-tight flex items-start gap-1">
                                        <span class="text-sm font-bold mt-1 text-blue-600">Rp</span>
                                        {{ number_format($row->total_final, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Rata Kanan: Action Buttons --}}
                            <div class="flex items-center gap-3 md:self-end lg:self-center">
                                <a href="{{ route('pesanan.lacak', $row->kode_invoice) }}" class="flex-1 md:flex-none bg-zinc-900 hover:bg-blue-600 text-white font-black py-3.5 px-8 rounded-2xl transition-all duration-300 shadow-lg hover:shadow-blue-500/40 text-xs flex items-center justify-center gap-3 group/btn">
                                    Track & Manage <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </a>
                                
                                {{-- Opsional: Tombol Hubungi CS (Hanya muncul jika butuh bantuan) --}}
                                <button onclick="alert('Menghubungkan ke POTA Support...')" class="w-12 h-12 rounded-2xl bg-zinc-100 text-zinc-400 hover:bg-zinc-200 hover:text-black transition-all flex items-center justify-center border border-zinc-200">
                                    <i class="fas fa-headset text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Footer Decorative Element --}}
                        <div class="h-1 w-full bg-gradient-to-r from-transparent via-zinc-100 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Bantuan Section (Absolute Cinema Finishing) --}}
        <div class="mt-20 bg-zinc-950 rounded-[3rem] p-10 lg:p-16 relative overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/20 rounded-full blur-[80px]"></div>
            <div class="relative z-10 grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <h3 class="text-2xl font-black text-white mb-4">Butuh Bantuan Pengadaan?</h3>
                    <p class="text-zinc-400 text-sm font-medium leading-relaxed">Hubungi asisten virtual kami (POTA) atau Customer Service resmi Pondasikita jika Anda menemui kendala dalam pesanan atau ingin melakukan komplain material.</p>
                </div>
                <div class="flex flex-wrap gap-4 md:justify-end">
                    <a href="mailto:support@pondasikita.com" class="bg-white/5 border border-white/10 text-white font-bold py-3 px-6 rounded-xl hover:bg-white/10 transition-colors text-sm">Email Support</a>
                    <a href="https://wa.me/yournumber" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all text-sm">WhatsApp Hotline</a>
                </div>
            </div>
        </div>

    </main>

    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
</body>
</html>