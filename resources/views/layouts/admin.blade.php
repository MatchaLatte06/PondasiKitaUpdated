<!DOCTYPE html>
<html lang="id"
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
      x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Pondasikita Admin</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">

    {{-- Bootstrap & Tailwind CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] } } },
            corePlugins: { preflight: false }
        }
    </script>

    <style>
        /* Base Styling - Ultra Smooth & True Black OLED */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            display: flex;
            min-height: 100vh;
            @apply bg-slate-50 text-slate-800 transition-colors duration-500 ease-in-out;
        }

        .dark body {
            background: radial-gradient(circle at top center, #111827 0%, #000000 40%);
            background-attachment: fixed;
            @apply text-slate-300;
        }

        [x-cloak] { display: none !important; }

        /* Layout Structure & Glassmorphism Dewa */
        .admin-sidebar {
            width: 260px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 1040;
            @apply bg-white/80 dark:bg-[#0a0a0a]/80 backdrop-blur-2xl border-r border-slate-200/50 dark:border-white/[0.03] shadow-[4px_0_24px_rgba(0,0,0,0.02)] dark:shadow-[4px_0_24px_rgba(0,0,0,0.8)] transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-main-wrapper {
            flex-grow: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
            @apply transition-all duration-500 cubic-bezier(0.4, 0, 0.2, 1) bg-slate-50 dark:bg-transparent;
        }

        .admin-navbar {
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1030;
            @apply bg-white/70 dark:bg-[#000000]/50 backdrop-blur-xl border-b border-slate-200/50 dark:border-white/[0.03] shadow-sm dark:shadow-none transition-colors duration-500;
        }

        .admin-content {
            flex-grow: 1;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        /* Glassmorphism Alerts Premium - Deep Neon Aesthetic */
        .alert {
            @apply border border-white/20 dark:border-white/[0.05] rounded-2xl font-bold text-sm shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.5)] backdrop-blur-xl transition-all;
        }
        .alert-success { @apply bg-emerald-50/80 text-emerald-700 dark:bg-[#022c22]/50 dark:text-emerald-400 dark:shadow-[0_0_20px_rgba(16,185,129,0.1)]; }
        .alert-danger { @apply bg-red-50/80 text-red-700 dark:bg-[#4c0519]/50 dark:text-rose-400 dark:shadow-[0_0_20px_rgba(244,63,94,0.1)]; }

        /* Responsive Mobile */
        @media (max-width: 991px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.sidebar-open { transform: translateX(0); }
            .admin-main-wrapper { margin-left: 0; }
            .admin-content { padding: 1.5rem 1rem; }
        }

        /* Scrollbar ala macOS - Darkened */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { border: 2px solid rgba(0,0,0,0); background-clip: padding-box; @apply bg-slate-300 dark:bg-[#1f2937] rounded-full transition-colors; }
        ::-webkit-scrollbar-thumb:hover { @apply bg-slate-400 dark:bg-[#374151]; }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>

<body x-data="{ sidebarOpen: false }">

    {{-- Overlay untuk Mobile (Animasi Halus & Gelap) --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 bg-slate-900/60 dark:bg-[#000000]/80 backdrop-blur-sm lg:hidden"
         @click="sidebarOpen = false" x-cloak></div>

    {{-- 1. INCLUDE SIDEBAR ADMIN --}}
    <aside class="admin-sidebar" id="adminSidebar" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        @include('admin.partials.sidebar')
    </aside>

    <div class="admin-main-wrapper">

        {{-- 2. INCLUDE NAVBAR ADMIN --}}
        <header class="admin-navbar">
            <button class="btn btn-link text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-white p-0 d-lg-none me-3 transition-transform hover:scale-110 active:scale-95 outline-none" @click="sidebarOpen = true">
                <i class="mdi mdi-menu text-3xl leading-none"></i>
            </button>
            @include('admin.partials.navbar')
        </header>

        <main class="admin-content">

            {{-- Flash Message Success --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
                     class="alert alert-success flex items-center justify-between mb-6 px-5 py-4" role="alert">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-500/10 border dark:border-emerald-500/20 flex items-center justify-center shadow-inner">
                            <i class="mdi mdi-check-circle text-xl text-emerald-600 dark:text-emerald-400"></i>
                        </div>
                        <span class="tracking-wide">{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="show = false" class="text-emerald-600 dark:text-emerald-400 opacity-60 hover:opacity-100 transition-opacity outline-none p-1">
                        <i class="mdi mdi-close text-lg"></i>
                    </button>
                </div>
            @endif

            {{-- Flash Message Error --}}
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
                     class="alert alert-danger flex items-center justify-between mb-6 px-5 py-4" role="alert">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 dark:bg-rose-500/10 border dark:border-rose-500/20 flex items-center justify-center shadow-inner">
                            <i class="mdi mdi-alert-circle text-xl text-red-600 dark:text-rose-400"></i>
                        </div>
                        <span class="tracking-wide">{{ session('error') }}</span>
                    </div>
                    <button type="button" @click="show = false" class="text-red-600 dark:text-rose-400 opacity-60 hover:opacity-100 transition-opacity outline-none p-1">
                        <i class="mdi mdi-close text-lg"></i>
                    </button>
                </div>
            @endif

            {{-- 3. KONTEN UTAMA --}}
            <div class="animate-fade-in-up">
                @yield('content')
            </div>

            {{-- Footer --}}
            <footer class="mt-12 py-6 border-t border-slate-200/50 dark:border-white/[0.03] text-center text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest bg-white/30 dark:bg-[#050505]/50 backdrop-blur-md rounded-2xl mb-2 transition-colors duration-500 shadow-sm dark:shadow-[0_0_15px_rgba(0,0,0,0.5)]">
                &copy; {{ date('Y') }} Pondasikita Admin. Crafted with Excellence.
            </footer>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Custom Script Tailwind Animation --}}
    <script>
        tailwind.config.theme.extend.animation = {
            'fade-in-up': 'fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards'
        };
        tailwind.config.theme.extend.keyframes = {
            fadeInUp: {
                '0%': { opacity: '0', transform: 'translateY(20px)' },
                '100%': { opacity: '1', transform: 'translateY(0)' }
            }
        };
    </script>

    @stack('scripts')
</body>
</html>
