{{-- NAVBAR TOP (DARK MODE) --}}
<header class="sticky top-0 z-40 flex items-center justify-between w-full h-[70px] px-4 md:px-6 bg-slate-900 border-b border-slate-800 shadow-sm">

    {{-- KIRI: Hamburger Menu (Muncul di Mobile/Tablet) --}}
    <div class="flex items-center gap-4">
        {{-- Tombol Toggle Sidebar --}}
        <button id="sidebarToggle" class="p-2 text-slate-400 rounded-xl hover:bg-slate-800 hover:text-white transition-colors lg:hidden focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-slate-900">
            <i class="mdi mdi-menu text-2xl leading-none"></i>
        </button>

        {{-- Opsional: Breadcrumb atau Judul Halaman bisa ditaruh di sini --}}
        <div class="hidden lg:block">
            <span class="text-sm font-bold text-slate-400">Panel Kelola Toko</span>
        </div>
    </div>

    {{-- KANAN: Ikon Aksi & Profil --}}
    <div class="flex items-center gap-2 sm:gap-3">

        {{-- Ikon Notifikasi (Lonceng) --}}
        <button class="relative p-2.5 text-slate-400 rounded-xl hover:bg-slate-800 hover:text-white transition-all group focus:outline-none">
            <i class="mdi mdi-bell-outline text-xl group-hover:scale-110 transition-transform"></i>
            {{-- Badge Titik Merah (Indikator ada notifikasi baru) --}}
            <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border border-slate-900"></span>
        </button>

        {{-- Ikon Pesan (Email) --}}
        <button class="relative p-2.5 text-slate-400 rounded-xl hover:bg-slate-800 hover:text-white transition-all group focus:outline-none">
            <i class="mdi mdi-email-outline text-xl group-hover:scale-110 transition-transform"></i>
        </button>

        {{-- Garis Pemisah --}}
        <div class="w-px h-6 bg-slate-800 mx-1 sm:mx-2"></div>

        {{-- Dropdown Profil --}}
        <div class="relative" id="profileDropdownContainer">
            {{-- Tombol Profil --}}
            <button onclick="toggleProfileMenu()" class="flex items-center gap-3 p-1.5 pr-3 rounded-xl hover:bg-slate-800 border border-transparent hover:border-slate-700 transition-all group focus:outline-none">
                {{-- Avatar --}}
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-black text-sm shadow-md shadow-blue-900/50">
                    {{ strtoupper(substr(Auth::user()->nama ?? 'S', 0, 1)) }}
                </div>

                {{-- Nama & Role (Sembunyi di layar kecil HP) --}}
                <div class="hidden md:flex flex-col text-left">
                    <span class="text-xs font-black text-slate-200 group-hover:text-white leading-tight">
                        {{ Str::limit(Auth::user()->nama ?? 'Seller', 15) }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Seller</span>
                </div>

                {{-- Chevron Animasi --}}
                <i class="mdi mdi-chevron-down text-slate-500 group-hover:text-white transition-transform duration-300" id="profileChevron"></i>
            </button>

            {{-- Isi Menu Dropdown --}}
            <div id="profileMenu" class="absolute right-0 mt-3 w-48 bg-slate-800 border border-slate-700 rounded-2xl shadow-xl shadow-black/50 opacity-0 invisible transform scale-95 transition-all duration-200 origin-top-right">
                <div class="p-2 space-y-1">
                    <a href="{{ route('seller.shop.profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-slate-300 hover:text-white hover:bg-slate-700 rounded-xl transition-colors">
                        <i class="mdi mdi-account-circle-outline text-lg text-slate-400"></i> Profil Toko
                    </a>
                    <a href="{{ route('seller.shop.settings') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-slate-300 hover:text-white hover:bg-slate-700 rounded-xl transition-colors">
                        <i class="mdi mdi-cog-outline text-lg text-slate-400"></i> Pengaturan
                    </a>

                    <div class="h-px bg-slate-700 my-2"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition-colors">
                            <i class="mdi mdi-logout text-lg"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>

{{-- SCRIPT LOGIC DROPDOWN PROFIL --}}
<script>
    function toggleProfileMenu() {
        const menu = document.getElementById('profileMenu');
        const chevron = document.getElementById('profileChevron');

        if (menu.classList.contains('opacity-0')) {
            // Buka Dropdown
            menu.classList.remove('opacity-0', 'invisible', 'scale-95');
            menu.classList.add('opacity-100', 'visible', 'scale-100');
            chevron.classList.add('rotate-180');
        } else {
            // Tutup Dropdown
            menu.classList.add('opacity-0', 'invisible', 'scale-95');
            menu.classList.remove('opacity-100', 'visible', 'scale-100');
            chevron.classList.remove('rotate-180');
        }
    }

    // Klik di luar untuk menutup Dropdown (Fitur wajib UI Modern)
    document.addEventListener('click', function(event) {
        const container = document.getElementById('profileDropdownContainer');
        if (!container.contains(event.target)) {
            const menu = document.getElementById('profileMenu');
            const chevron = document.getElementById('profileChevron');

            menu.classList.add('opacity-0', 'invisible', 'scale-95');
            menu.classList.remove('opacity-100', 'visible', 'scale-100');
            chevron.classList.remove('rotate-180');
        }
    });
</script>
