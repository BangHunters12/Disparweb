@extends('layouts.public')
@section('title', 'Semua Restoran Bondowoso')
@section('meta-description', 'Daftar lengkap restoran, warung makan, dan cafe di Kabupaten Bondowoso. Cari, filter, dan temukan kuliner terbaik.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Sidebar Filter --}}
        <aside class="lg:w-72 flex-shrink-0">
            <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-6 sticky top-24">
                <h2 class="font-black text-white text-base mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </h2>

                <form id="filter-form" action="{{ route('restoran.index') }}" method="GET">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    {{-- Sort --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Urutkan</label>
                        <div class="space-y-1.5">
                            @foreach(['saw' => '⭐ Skor SAW', 'rating' => '🔝 Rating Tertinggi', 'terbaru' => '🆕 Terbaru', 'nama' => '🔤 Nama A-Z', 'harga_asc' => '💰 Harga Terendah', 'harga_desc' => '💎 Harga Tertinggi'] as $val => $label)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="radio" name="sort" value="{{ $val }}" {{ request('sort', 'saw') === $val ? 'checked' : '' }}
                                       class="w-4 h-4 accent-amber-500" onchange="this.form.submit()">
                                <span class="text-sm {{ request('sort', 'saw') === $val ? 'text-amber-400 font-semibold' : 'text-gray-400 group-hover:text-white' }} transition-colors">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kecamatan --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Kecamatan</label>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 scrollbar-thin">
                            @foreach($kecamatanList as $kec)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" name="kecamatan[]" value="{{ $kec->id }}"
                                       {{ in_array($kec->id, (array) request('kecamatan', [])) ? 'checked' : '' }}
                                       class="w-4 h-4 accent-amber-500 rounded" onchange="this.form.submit()">
                                <span class="text-sm text-gray-400 group-hover:text-white transition-colors truncate">{{ $kec->nama }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Rating minimum --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Rating Minimum</label>
                        <div class="flex gap-1.5">
                            @foreach([1,2,3,4,5] as $star)
                            <a href="{{ route('restoran.index', array_merge(request()->query(), ['rating_min' => $star])) }}"
                               class="flex-1 py-1.5 text-center text-xs font-bold rounded-lg border transition-all
                                      {{ request('rating_min') == $star ? 'bg-amber-500 border-amber-500 text-[#0f1117]' : 'border-[#2d3548] text-gray-400 hover:border-amber-500/50' }}">
                                {{ $star }}★
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Buka Sekarang --}}
                    <div class="mb-6">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm font-semibold text-gray-300">Buka Sekarang</span>
                            <div class="relative">
                                <input type="checkbox" name="buka_sekarang" value="1" class="sr-only peer"
                                       {{ request('buka_sekarang') ? 'checked' : '' }} onchange="this.form.submit()">
                                <div class="w-10 h-5 bg-[#2d3548] rounded-full peer peer-checked:bg-amber-500 transition-colors"></div>
                                <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                    </div>

                    {{-- Reset --}}
                    @if(request()->hasAny(['sort', 'kecamatan', 'rating_min', 'buka_sekarang', 'harga_min', 'harga_max']))
                    <a href="{{ route('restoran.index', request('search') ? ['search' => request('search')] : []) }}"
                       class="block w-full text-center py-2 text-xs text-gray-500 hover:text-red-400 transition-colors border border-[#2d3548] rounded-xl">
                        Reset Filter
                    </a>
                    @endif
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 min-w-0">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-black text-white">
                        @if(request('search'))
                            Hasil: "{{ request('search') }}"
                        @else
                            Semua Restoran
                        @endif
                    </h1>
                    <p class="text-gray-500 text-sm mt-0.5">{{ $restoran->total() }} restoran ditemukan</p>
                </div>
                {{-- Search bar --}}
                <form action="{{ route('restoran.index') }}" method="GET" class="flex gap-2">
                    @foreach(request()->except(['search', 'page']) as $key => $val)
                        @if(is_array($val))
                            @foreach($val as $item)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endif
                    @endforeach
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari restoran..."
                               class="pl-9 pr-4 py-2.5 bg-[#1a1f2e] border border-[#2d3548] text-white placeholder-gray-500 rounded-xl text-sm focus:outline-none focus:border-amber-500 w-56">
                    </div>
                    <button type="submit" class="px-4 py-2.5 bg-amber-500 text-[#0f1117] font-bold rounded-xl text-sm hover:bg-amber-400 transition-all">Cari</button>
                </form>
            </div>

            {{-- Grid --}}
            @if($restoran->isEmpty())
                <div class="text-center py-24">
                    <div class="w-16 h-16 rounded-2xl bg-[#1a1f2e] border border-[#2d3548] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-gray-400 font-semibold mb-1">Tidak ada restoran ditemukan</p>
                    <p class="text-gray-600 text-sm">Coba ubah filter atau kata kunci pencarian</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($restoran as $r)
                    <a href="{{ route('restoran.show', $r->slug) }}" class="group bg-[#1a1f2e] border border-[#2d3548] rounded-2xl overflow-hidden hover:border-amber-500/30 hover:shadow-[0_20px_60px_-15px_rgba(245,158,11,0.15)] transition-all duration-300 hover:-translate-y-1 block">
                        <div class="relative h-44 overflow-hidden bg-[#0f1117]">
                            <img src="{{ $r->foto_utama_url }}" alt="{{ $r->nama_usaha }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90"
                                 loading="lazy"
                                 onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&q=80'">
                            {{-- SAW --}}
                            @if($r->skor_saw)
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center gap-1 bg-[#0f1117]/80 backdrop-blur text-xs text-amber-400 font-bold px-2 py-0.5 rounded-lg">
                                    <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    {{ number_format($r->skor_saw, 3) }}
                                </span>
                            </div>
                            @endif
                            {{-- Status --}}
                            <div class="absolute bottom-2 left-2">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $r->is_buka ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                                    {{ $r->is_buka ? '● Buka' : '● Tutup' }}
                                </span>
                            </div>
                            {{-- Sumber --}}
                            <div class="absolute top-2 left-2">
                                <span class="text-xs px-1.5 py-0.5 rounded bg-[#0f1117]/70 text-gray-300 font-medium">{{ ucfirst($r->sumber) }}</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-white group-hover:text-amber-400 transition-colors truncate">{{ $r->nama_usaha }}</h3>
                            <div class="flex items-center gap-2 mt-1.5">
                                <div class="flex items-center gap-0.5">
                                    <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <span class="text-xs font-bold text-amber-400">{{ number_format($r->avg_rating, 1) }}</span>
                                </div>
                                <span class="text-gray-600 text-xs">·</span>
                                <span class="text-gray-500 text-xs">{{ $r->total_ulasan }} ulasan</span>
                            </div>
                            <div class="flex items-center gap-1 mt-1.5">
                                <svg class="w-3 h-3 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
                                <span class="text-xs text-gray-500 truncate">{{ $r->kecamatan?->nama }}</span>
                            </div>
                            @if($r->harga_min)
                            <p class="text-amber-400 text-xs font-bold mt-2">{{ $r->harga_range_text }}</p>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($restoran->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $restoran->links('vendor.pagination.custom') }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

{{-- Mobile sticky CTA --}}
<div class="fixed bottom-16 left-4 right-4 sm:hidden z-30">
    <a href="#download-app" class="flex items-center gap-3 bg-amber-500 text-[#0f1117] px-4 py-3 rounded-2xl shadow-2xl font-bold text-sm">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.7 9.05 7.42c1.32.07 2.25.8 3.03.82.97-.19 1.9-.95 3.14-.86 1.97.17 3.34 1.1 4.05 2.66-3.57 2.02-2.7 6.98.78 8.24z"/></svg>
        Download App untuk Fitur Lengkap
    </a>
</div>
@endsection
