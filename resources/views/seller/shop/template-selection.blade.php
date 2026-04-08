@extends('layouts.seller')

@section('title', 'Pilih Template Dekorasi')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* MODAL PREVIEW HP ANTI-GEPENG */
    .phone-container {
        width: 330px; height: 680px; flex-shrink: 0;
        background: #0f172a; border-radius: 45px; padding: 12px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        position: relative; margin: 0 auto;
        border: 2px solid #334155;
    }
    .phone-screen {
        width: 100%; height: 100%; background: #f8fafc;
        border-radius: 32px; overflow: hidden; position: relative;
        display: flex; flex-direction: column;
    }
    .phone-notch {
        position: absolute; top: 0; left: 50%; transform: translateX(-50%);
        width: 110px; height: 24px; background: #0f172a;
        border-bottom-left-radius: 14px; border-bottom-right-radius: 14px; z-index: 100;
    }
</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

{{-- FULL SCREEN OVERLAY --}}
<div class="fixed inset-0 z-[9999] bg-[#f8fafc] overflow-y-auto font-sans text-slate-800" x-data="templateManager()" x-init="initPage()" x-cloak>

    {{-- HEADER PONDASIKITA --}}
    <div class="bg-[#1e293b] border-b border-slate-700 h-16 px-4 md:px-8 flex justify-between items-center sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 bg-blue-600 text-white rounded flex items-center justify-center font-black text-xl shadow-lg shadow-blue-500/30">
                <i class="mdi mdi-store"></i>
            </div>
            <div class="flex items-center text-sm md:text-base font-bold text-slate-400 gap-2">
                <a href="{{ route('seller.dashboard') }}" class="hover:text-blue-400 transition-colors">Beranda</a>
                <i class="mdi mdi-chevron-right text-slate-600"></i>
                <a href="{{ route('seller.shop.decoration') }}" class="hover:text-blue-400 transition-colors">Dekorasi Toko</a>
                <i class="mdi mdi-chevron-right text-slate-600"></i>
                <span class="text-white">Dekorasi Instan</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 cursor-pointer hover:bg-slate-800 p-1.5 rounded-lg transition-colors text-white">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-black border border-blue-500">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <span class="text-sm font-bold hidden md:block">{{ Auth::user()->name ?? 'Seller' }}</span>
            </div>
        </div>
    </div>

    {{-- KONTEN HALAMAN --}}
    <div class="px-6 lg:px-10 py-8 max-w-[1400px] mx-auto">

        {{-- OPSI PEMBUATAN (HALAMAN KOSONG SAJA) --}}
        <div class="mb-10">
            <div @click="blankCanvas()" class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-5 shadow-sm cursor-pointer hover:shadow-md hover:border-blue-400 transition-all group max-w-md">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors rounded-full flex items-center justify-center">
                    <i class="mdi mdi-plus text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">Buat dari Halaman Kosong</h3>
                    <p class="text-xs text-slate-500 font-medium">Rancang kanvas bebas sesuai kreativitas.</p>
                </div>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
            <h2 class="text-xl font-black text-slate-900 mb-2">Pilih Template</h2>
            <p class="text-sm font-medium text-slate-500 mb-6">Gunakan template sebagai layout dasar untuk membantumu mendesain dekorasi toko.</p>

            <div class="flex flex-wrap items-center gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div class="flex items-center gap-2 flex-1 min-w-[150px]">
                    <span class="text-xs font-bold text-slate-500 uppercase">Kategori</span>
                    <select class="w-full bg-white border border-slate-300 text-sm font-bold rounded px-3 py-2 outline-none"><option>Semua</option></select>
                </div>
                <div class="flex items-center gap-2 flex-1 min-w-[150px]">
                    <span class="text-xs font-bold text-slate-500 uppercase">Tujuan</span>
                    <select class="w-full bg-white border border-slate-300 text-sm font-bold rounded px-3 py-2 outline-none"><option>Semua</option></select>
                </div>
                <div class="flex items-center gap-2 flex-1 min-w-[150px]">
                    <span class="text-xs font-bold text-slate-500 uppercase">Tema</span>
                    <select class="w-full bg-white border border-slate-300 text-sm font-bold rounded px-3 py-2 outline-none"><option>Semua</option></select>
                </div>
                <div class="flex items-center gap-2 flex-1 min-w-[200px]">
                    <span class="text-xs font-bold text-slate-500 uppercase">Urutkan</span>
                    <select class="w-full bg-white border border-slate-300 text-sm font-bold rounded px-3 py-2 outline-none"><option>Direkomendasikan</option></select>
                </div>
            </div>
        </div>

        {{-- GRID 6 TEMPLATES --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-20">

            {{-- TPL 1: OCEANIC PREMIUM --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 p-2.5 flex flex-col relative overflow-hidden group hover:shadow-2xl hover:border-blue-500 transition-all duration-300">
                <div class="h-[420px] w-full bg-slate-100 relative rounded-3xl overflow-hidden border border-slate-200 flex flex-col">
                    <div class="h-32 w-full bg-gradient-to-r from-blue-600 to-indigo-700 p-4 flex items-end"><div class="flex gap-2 items-center"><div class="w-10 h-10 rounded-full bg-white/20"></div><div class="w-20 h-2.5 bg-white/40 rounded-full"></div></div></div>
                    <div class="flex-1 p-2.5 bg-slate-50 space-y-2">
                        <div class="w-full h-24 rounded-xl bg-gradient-to-br from-blue-400 to-cyan-400 flex items-center justify-center text-white/80 text-xs font-black shadow-sm">BANNER UTAMA</div>
                        <div class="grid grid-cols-4 gap-1.5"><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div></div>
                        <div class="grid grid-cols-2 gap-2"><div class="h-28 bg-white rounded-xl border border-slate-200 p-2 shadow-sm"><div class="h-16 bg-slate-100 rounded-lg mb-2"></div><div class="w-full h-2 bg-slate-200 rounded"></div></div><div class="h-28 bg-white rounded-xl border border-slate-200 p-2 shadow-sm"><div class="h-16 bg-slate-100 rounded-lg mb-2"></div><div class="w-full h-2 bg-slate-200 rounded"></div></div></div>
                    </div>

                    {{-- OVERLAY MURNI TAILWIND --}}
                    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-[2px] flex flex-col items-center justify-center gap-3 px-6 z-20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                        <button @click.prevent="applyTemplate(1, 'Oceanic Premium')" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-75 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg">Tampilkan</button>
                        <button @click.prevent="openPreview(1)" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-100 w-full py-2.5 bg-white hover:bg-slate-100 text-slate-800 text-sm font-bold rounded-xl shadow-lg">Preview</button>
                    </div>
                </div>
                <div class="mt-3 mb-2 px-2 text-center"><h5 class="font-black text-slate-800 text-sm">Oceanic Premium</h5></div>
            </div>

            {{-- TPL 2: ECO HARVEST --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 p-2.5 flex flex-col relative overflow-hidden group hover:shadow-2xl hover:border-emerald-500 transition-all duration-300">
                <div class="h-[420px] w-full bg-slate-100 relative rounded-3xl overflow-hidden border border-slate-200 flex flex-col">
                    <div class="h-32 w-full bg-gradient-to-r from-emerald-500 to-green-600 p-4 flex items-end"><div class="flex gap-2 items-center"><div class="w-10 h-10 rounded-full bg-white/20"></div><div class="w-20 h-2.5 bg-white/40 rounded-full"></div></div></div>
                    <div class="flex-1 p-2.5 bg-slate-50 space-y-2">
                        <div class="grid grid-cols-4 gap-1.5"><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div></div>
                        <div class="w-full h-24 rounded-xl bg-gradient-to-br from-emerald-300 to-teal-400 flex items-center justify-center text-white/80 text-xs font-black shadow-sm">PROMO SPESIAL</div>
                        <div class="w-full h-28 bg-white rounded-xl border border-slate-200 p-2 shadow-sm flex gap-2"><div class="w-20 h-full bg-slate-100 rounded-lg"></div><div class="flex-1"><div class="w-full h-2 bg-slate-200 rounded mb-2"></div><div class="w-1/2 h-2 bg-slate-200 rounded"></div></div></div>
                    </div>

                    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-[2px] flex flex-col items-center justify-center gap-3 px-6 z-20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                        <button @click.prevent="applyTemplate(2, 'Eco Harvest')" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-75 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg">Tampilkan</button>
                        <button @click.prevent="openPreview(2)" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-100 w-full py-2.5 bg-white hover:bg-slate-100 text-slate-800 text-sm font-bold rounded-xl shadow-lg">Preview</button>
                    </div>
                </div>
                <div class="mt-3 mb-2 px-2 text-center"><h5 class="font-black text-slate-800 text-sm">Eco Harvest</h5></div>
            </div>

            {{-- TPL 3: SUNSET BOLD --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 p-2.5 flex flex-col relative overflow-hidden group hover:shadow-2xl hover:border-orange-500 transition-all duration-300">
                <div class="h-[420px] w-full bg-slate-100 relative rounded-3xl overflow-hidden border border-slate-200 flex flex-col">
                    <div class="h-32 w-full bg-gradient-to-r from-orange-500 to-red-500 p-4 flex items-end"><div class="flex gap-2 items-center"><div class="w-10 h-10 rounded-full bg-white/20"></div><div class="w-20 h-2.5 bg-white/40 rounded-full"></div></div></div>
                    <div class="flex-1 p-2.5 bg-slate-50 space-y-2">
                        <div class="w-full h-32 bg-slate-800 rounded-xl shadow-sm flex items-center justify-center border-2 border-slate-300"><i class="mdi mdi-play-circle text-4xl text-red-500"></i></div>
                        <div class="grid grid-cols-2 gap-2"><div class="h-28 bg-white rounded-xl border border-slate-200 p-2 shadow-sm"><div class="h-16 bg-slate-100 rounded-lg mb-2"></div><div class="w-full h-2 bg-slate-200 rounded"></div></div><div class="h-28 bg-white rounded-xl border border-slate-200 p-2 shadow-sm"><div class="h-16 bg-slate-100 rounded-lg mb-2"></div><div class="w-full h-2 bg-slate-200 rounded"></div></div></div>
                    </div>

                    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-[2px] flex flex-col items-center justify-center gap-3 px-6 z-20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                        <button @click.prevent="applyTemplate(3, 'Sunset Bold')" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-75 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg">Tampilkan</button>
                        <button @click.prevent="openPreview(3)" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-100 w-full py-2.5 bg-white hover:bg-slate-100 text-slate-800 text-sm font-bold rounded-xl shadow-lg">Preview</button>
                    </div>
                </div>
                <div class="mt-3 mb-2 px-2 text-center"><h5 class="font-black text-slate-800 text-sm">Sunset Bold (Video)</h5></div>
            </div>

            {{-- TPL 4: MIDNIGHT LUXURY --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 p-2.5 flex flex-col relative overflow-hidden group hover:shadow-2xl hover:border-slate-800 transition-all duration-300">
                <div class="h-[420px] w-full bg-slate-100 relative rounded-3xl overflow-hidden border border-slate-200 flex flex-col">
                    <div class="h-32 w-full bg-gradient-to-b from-slate-800 to-black p-4 flex items-end"><div class="flex gap-2 items-center"><div class="w-10 h-10 rounded-full bg-white/20"></div><div class="w-20 h-2.5 bg-white/40 rounded-full"></div></div></div>
                    <div class="flex-1 p-2.5 bg-slate-50 space-y-2">
                        <div class="w-full h-24 rounded-xl bg-gradient-to-br from-slate-700 to-slate-900 flex flex-col items-center justify-center text-white/80 text-xs font-black shadow-sm"><i class="mdi mdi-view-carousel text-2xl mb-1"></i> CAROUSEL</div>
                        <div class="w-full h-28 bg-white rounded-xl border border-slate-200 p-2 shadow-sm flex gap-2"><div class="flex-1"><div class="w-full h-2 bg-slate-200 rounded mb-2"></div><div class="w-1/2 h-2 bg-slate-200 rounded"></div></div><div class="w-20 h-full bg-slate-100 rounded-lg"></div></div>
                    </div>

                    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-[2px] flex flex-col items-center justify-center gap-3 px-6 z-20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                        <button @click.prevent="applyTemplate(4, 'Midnight Luxury')" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-75 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg">Tampilkan</button>
                        <button @click.prevent="openPreview(4)" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-100 w-full py-2.5 bg-white hover:bg-slate-100 text-slate-800 text-sm font-bold rounded-xl shadow-lg">Preview</button>
                    </div>
                </div>
                <div class="mt-3 mb-2 px-2 text-center"><h5 class="font-black text-slate-800 text-sm">Midnight Luxury</h5></div>
            </div>

            {{-- TPL 5: PINK BLOSSOM --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 p-2.5 flex flex-col relative overflow-hidden group hover:shadow-2xl hover:border-pink-500 transition-all duration-300">
                <div class="h-[420px] w-full bg-slate-100 relative rounded-3xl overflow-hidden border border-slate-200 flex flex-col">
                    <div class="h-32 w-full bg-gradient-to-r from-pink-400 to-rose-500 p-4 flex items-end"><div class="flex gap-2 items-center"><div class="w-10 h-10 rounded-full bg-white/20"></div><div class="w-20 h-2.5 bg-white/40 rounded-full"></div></div></div>
                    <div class="flex-1 p-2.5 bg-slate-50 space-y-2">
                        <div class="grid grid-cols-4 gap-1.5"><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div><div class="h-10 bg-white rounded-lg border border-slate-200 shadow-sm"></div></div>
                        <div class="w-full h-24 rounded-xl bg-gradient-to-br from-pink-300 to-rose-400 flex items-center justify-center text-white/80 text-xs font-black shadow-sm">HOT DEALS</div>
                        <div class="grid grid-cols-2 gap-2"><div class="h-20 bg-white rounded-xl border border-slate-200 p-1 shadow-sm"><div class="h-12 bg-slate-100 rounded-lg mb-1"></div><div class="w-full h-2 bg-slate-200 rounded"></div></div><div class="h-20 bg-white rounded-xl border border-slate-200 p-1 shadow-sm"><div class="h-12 bg-slate-100 rounded-lg mb-1"></div><div class="w-full h-2 bg-slate-200 rounded"></div></div></div>
                    </div>

                    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-[2px] flex flex-col items-center justify-center gap-3 px-6 z-20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                        <button @click.prevent="applyTemplate(5, 'Pink Blossom')" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-75 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg">Tampilkan</button>
                        <button @click.prevent="openPreview(5)" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-100 w-full py-2.5 bg-white hover:bg-slate-100 text-slate-800 text-sm font-bold rounded-xl shadow-lg">Preview</button>
                    </div>
                </div>
                <div class="mt-3 mb-2 px-2 text-center"><h5 class="font-black text-slate-800 text-sm">Pink Blossom</h5></div>
            </div>

            {{-- TPL 6: NEON CYBER --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 p-2.5 flex flex-col relative overflow-hidden group hover:shadow-2xl hover:border-cyan-500 transition-all duration-300">
                <div class="h-[420px] w-full bg-slate-100 relative rounded-3xl overflow-hidden border border-slate-200 flex flex-col">
                    <div class="h-32 w-full bg-gradient-to-r from-purple-600 to-cyan-500 p-4 flex items-end"><div class="flex gap-2 items-center"><div class="w-10 h-10 rounded-full bg-white/20"></div><div class="w-20 h-2.5 bg-white/40 rounded-full"></div></div></div>
                    <div class="flex-1 p-2.5 bg-slate-50 flex flex-col gap-2">
                        <div class="w-full flex-1 bg-slate-800 rounded-xl shadow-sm flex flex-col items-center justify-center border-2 border-slate-300 relative"><i class="mdi mdi-play-circle text-4xl text-cyan-400 absolute"></i><div class="absolute bottom-2 left-2 text-[8px] text-white">NEW ARRIVAL</div></div>
                        <div class="h-20 bg-white rounded-xl border border-slate-200 p-2 shadow-sm flex items-center justify-between"><div class="w-12 h-12 bg-slate-100 rounded-full"></div><div class="w-12 h-12 bg-slate-100 rounded-full"></div><div class="w-12 h-12 bg-slate-100 rounded-full"></div></div>
                    </div>

                    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-[2px] flex flex-col items-center justify-center gap-3 px-6 z-20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                        <button @click.prevent="applyTemplate(6, 'Neon Cyber')" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-75 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg">Tampilkan</button>
                        <button @click.prevent="openPreview(6)" class="transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-100 w-full py-2.5 bg-white hover:bg-slate-100 text-slate-800 text-sm font-bold rounded-xl shadow-lg">Preview</button>
                    </div>
                </div>
                <div class="mt-3 mb-2 px-2 text-center"><h5 class="font-black text-slate-800 text-sm">Neon Cyber</h5></div>
            </div>

        </div>
    </div>

    {{-- =================================================================
        MODAL LIVE PREVIEW (HP MOCKUP - ANTI GEPENG)
        ================================================================= --}}
    <div x-show="isPreviewOpen" class="fixed inset-0 z-[100000] overflow-y-auto" style="display: none;" x-cloak>
        <div x-show="isPreviewOpen" x-transition.opacity.duration.300ms class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm" @click="closePreview()"></div>

        <div class="min-h-screen flex items-center justify-center p-4">
            <div x-show="isPreviewOpen"
                 x-transition:enter="transition ease-out duration-400 transform"
                 x-transition:enter-start="opacity-0 translate-y-12 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 class="relative flex-shrink-0 w-[360px] h-[720px] bg-white rounded-[2.5rem] shadow-2xl flex flex-col z-10 overflow-hidden my-8">

                {{-- Header Modal --}}
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white z-20">
                    <h2 class="text-base font-black text-slate-800" x-text="'Preview: ' + previewData.name"></h2>
                    <button @click="closePreview()" class="text-slate-400 hover:text-red-500 outline-none"><i class="mdi mdi-close text-2xl"></i></button>
                </div>

                {{-- Phone Wrapper --}}
                <div class="flex-1 bg-slate-100 p-6 flex justify-center overflow-y-auto hide-scrollbar">
                    <div class="phone-container">
                        <div class="phone-notch"></div>
                        <div class="phone-screen">

                            {{-- Status Bar --}}
                            <div class="h-7 w-full bg-black/20 flex justify-between items-center px-6 pt-1.5 text-[10px] font-black z-50 text-white absolute top-0">
                                <span x-text="currentTime"></span>
                                <div class="flex gap-1.5"><i class="mdi mdi-signal"></i><i class="mdi mdi-wifi"></i><i class="mdi mdi-battery"></i></div>
                            </div>

                            <div class="flex-1 overflow-y-auto hide-scrollbar bg-slate-50 flex flex-col pb-10">

                                {{-- HEADER TOKO DINAMIS --}}
                                <div class="h-44 p-4 text-white flex flex-col justify-end relative shadow-md pt-10" :class="previewData.headerClass">
                                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                                    <div class="absolute top-8 left-4 right-4 flex justify-between items-center z-10">
                                        <i class="mdi mdi-arrow-left text-lg"></i>
                                        <div class="bg-black/20 rounded px-3 py-1.5 flex items-center gap-1 text-[10px] w-3/5 backdrop-blur-sm border border-white/20"><i class="mdi mdi-magnify"></i> Cari di toko</div>
                                        <i class="mdi mdi-dots-vertical text-lg"></i>
                                    </div>
                                    <div class="relative z-10 flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full border-2 border-white/50 bg-white/20 backdrop-blur-sm flex items-center justify-center text-xl font-black shadow-lg">T</div>
                                        <div class="flex-1">
                                            <div class="text-sm font-black truncate">{{ $toko->nama_toko ?? 'NAMA TOKO' }}</div>
                                            <div class="text-[9px] font-bold text-white/90 flex items-center gap-1 mt-0.5"><i class="mdi mdi-star text-amber-400"></i> 5.0 | 1.9K Pengikut</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- TABS --}}
                                <div class="flex bg-white shadow-sm border-b border-slate-100 z-20 sticky top-0">
                                    <div class="flex-1 py-3 text-center text-[11px] font-black border-b-2 border-blue-600 text-blue-600">Beranda</div>
                                    <div class="flex-1 py-3 text-center text-[11px] font-bold text-slate-500">Produk</div>
                                </div>

                                {{-- KONTEN PREVIEW MURNI (ANTI VUE CONFLICT) --}}
                                <div class="p-3 space-y-3">

                                    {{-- LAYOUT 1: OCEANIC --}}
                                    <div x-show="currentPreviewId === 1" class="space-y-3">
                                        <div class="w-full h-36 rounded-xl shadow-md flex items-center justify-center text-white text-xl font-black bg-gradient-to-br from-blue-400 to-cyan-400">PROMO SPESIAL</div>
                                        <div class="grid grid-cols-4 gap-2">
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-slate-100"><i class="mdi mdi-shoe-sneaker text-slate-400"></i></div>
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-slate-100"><i class="mdi mdi-tshirt-crew text-slate-400"></i></div>
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-slate-100"><i class="mdi mdi-watch text-slate-400"></i></div>
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-slate-100"><i class="mdi mdi-dots-horizontal text-slate-400"></i></div>
                                        </div>
                                        <div class="bg-white p-3 rounded-xl shadow-sm">
                                            <h4 class="text-[11px] font-black text-slate-800 mb-2">Produk Baru</h4>
                                            <div class="grid grid-cols-2 gap-2"><div class="h-32 bg-slate-50 rounded-lg border border-slate-100"></div><div class="h-32 bg-slate-50 rounded-lg border border-slate-100"></div></div>
                                        </div>
                                    </div>

                                    {{-- LAYOUT 2: ECO HARVEST --}}
                                    <div x-show="currentPreviewId === 2" class="space-y-3">
                                        <div class="grid grid-cols-4 gap-2">
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-emerald-100 text-emerald-500"><i class="mdi mdi-leaf"></i></div>
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-emerald-100 text-emerald-500"><i class="mdi mdi-food-apple"></i></div>
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-emerald-100 text-emerald-500"><i class="mdi mdi-bottle-tonic"></i></div>
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-emerald-100 text-emerald-500"><i class="mdi mdi-dots-horizontal"></i></div>
                                        </div>
                                        <div class="w-full h-32 rounded-xl shadow-md flex items-center justify-center text-white text-xl font-black bg-gradient-to-br from-emerald-400 to-lime-400">ORGANIC SALE</div>
                                        <div class="bg-white p-2 rounded-xl shadow-sm border border-slate-100 flex gap-2"><div class="w-24 h-24 bg-slate-100 rounded-lg"></div><div class="flex-1 py-2"><div class="w-full h-2 bg-slate-200 mb-2"></div><div class="w-1/2 h-2 bg-slate-200"></div></div></div>
                                    </div>

                                    {{-- LAYOUT 3: SUNSET BOLD --}}
                                    <div x-show="currentPreviewId === 3" class="space-y-3">
                                        <div class="w-full h-48 bg-slate-900 rounded-xl shadow-md flex items-center justify-center relative overflow-hidden border-2 border-orange-500">
                                            <i class="mdi mdi-play-circle text-orange-500 text-5xl"></i>
                                            <div class="absolute bottom-2 left-3 text-white text-[10px] font-bold">Watch Video</div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2"><div class="h-32 bg-white rounded-xl shadow-sm border border-slate-100"></div><div class="h-32 bg-white rounded-xl shadow-sm border border-slate-100"></div></div>
                                    </div>

                                    {{-- LAYOUT 4: MIDNIGHT LUXURY --}}
                                    <div x-show="currentPreviewId === 4" class="space-y-3">
                                        <div class="w-full h-32 rounded-xl shadow-md flex flex-col items-center justify-center text-white bg-gradient-to-br from-slate-700 to-slate-900">
                                            <i class="mdi mdi-view-carousel text-3xl"></i><span class="text-[10px] mt-1 font-bold">LUXURY CAROUSEL</span>
                                        </div>
                                        <div class="bg-white p-2 rounded-xl shadow-sm border border-slate-100 flex gap-2"><div class="flex-1 py-2"><div class="w-full h-2 bg-slate-200 mb-2"></div><div class="w-1/2 h-2 bg-slate-200"></div></div><div class="w-24 h-24 bg-slate-100 rounded-lg"></div></div>
                                    </div>

                                    {{-- LAYOUT 5: PINK BLOSSOM --}}
                                    <div x-show="currentPreviewId === 5" class="space-y-3">
                                        <div class="grid grid-cols-4 gap-2">
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-pink-100 text-pink-500"><i class="mdi mdi-lipstick"></i></div>
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-pink-100 text-pink-500"><i class="mdi mdi-ring"></i></div>
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-pink-100 text-pink-500"><i class="mdi mdi-hanger"></i></div>
                                            <div class="h-14 bg-white rounded-xl shadow-sm flex items-center justify-center border border-pink-100 text-pink-500"><i class="mdi mdi-dots-horizontal"></i></div>
                                        </div>
                                        <div class="w-full h-32 rounded-xl shadow-md flex items-center justify-center text-white text-xl font-black bg-gradient-to-br from-pink-400 to-rose-400">HOT DEALS</div>
                                        <div class="grid grid-cols-2 gap-2"><div class="h-28 bg-white rounded-xl shadow-sm border border-slate-100"></div><div class="h-28 bg-white rounded-xl shadow-sm border border-slate-100"></div></div>
                                    </div>

                                    {{-- LAYOUT 6: NEON CYBER --}}
                                    <div x-show="currentPreviewId === 6" class="space-y-3">
                                        <div class="w-full h-40 bg-slate-900 rounded-xl shadow-md flex items-center justify-center border-2 border-cyan-400"><i class="mdi mdi-play text-cyan-400 text-5xl"></i></div>
                                        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex justify-between items-center"><div class="w-12 h-12 bg-slate-100 rounded-full"></div><div class="w-12 h-12 bg-slate-100 rounded-full"></div><div class="w-12 h-12 bg-slate-100 rounded-full"></div></div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function templateManager() {
        return {
            isPreviewOpen: false,
            currentPreviewId: 1,
            previewData: { name: '', headerClass: '' },
            currentTime: '12:30',

            initPage() {
                setInterval(() => {
                    const now = new Date();
                    this.currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                }, 1000);
            },

            blankCanvas() {
                Swal.fire({
                    title: 'Membuat Kanvas Kosong',
                    text: 'Menyiapkan ruang kerja...',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-2xl' }
                }).then(() => {
                    window.location.href = "{{ route('seller.shop.decoration') }}?tpl=blank";
                });
            },

            openPreview(id) {
                this.currentPreviewId = id;
                if(id === 1) this.previewData = { name: 'Oceanic Premium', headerClass: 'bg-gradient-to-r from-blue-600 to-indigo-700' };
                if(id === 2) this.previewData = { name: 'Eco Harvest', headerClass: 'bg-gradient-to-r from-emerald-500 to-green-600' };
                if(id === 3) this.previewData = { name: 'Sunset Bold', headerClass: 'bg-gradient-to-r from-orange-500 to-red-500' };
                if(id === 4) this.previewData = { name: 'Midnight Luxury', headerClass: 'bg-gradient-to-b from-slate-800 to-black' };
                if(id === 5) this.previewData = { name: 'Pink Blossom', headerClass: 'bg-gradient-to-r from-pink-400 to-rose-500' };
                if(id === 6) this.previewData = { name: 'Neon Cyber', headerClass: 'bg-gradient-to-r from-purple-600 to-cyan-500' };

                this.isPreviewOpen = true;
                document.body.style.overflow = 'hidden';
            },

            closePreview() {
                this.isPreviewOpen = false;
                document.body.style.overflow = 'auto';
            },

            applyTemplate(id, name) {
                Swal.fire({
                    title: 'Terapkan Template?',
                    text: `Tema "${name}" akan diterapkan.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Terapkan',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-2xl' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Menerapkan...', html: 'Mohon tunggu', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                        setTimeout(() => { window.location.href = "{{ route('seller.shop.decoration') }}?tpl=" + id; }, 1500);
                    }
                });
            }
        }
    }
</script>
@endsection
