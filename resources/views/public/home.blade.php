@extends('layouts.public')
@section('title', 'Kuliner Terbaik Bondowoso')
@section('meta-description', 'Temukan kuliner terbaik di Kabupaten Bondowoso — restoran, warung, dan cafe terpilih dari Dinas Pariwisata dan Google Maps.')

@section('content')

{{-- Hero --}}
<section class="relative min-h-[88vh] flex items-center overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1600&q=80"
             alt="Kuliner Bondowoso" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(15,17,23,0.98) 0%,rgba(15,17,23,0.75) 60%,rgba(245,158,11,0.08) 100%)"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 w-full">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-amber-500/10 border border-amber-500/20 rounded-full px-4 py-1.5 text-amber-400 text-sm font-semibold mb-6">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                Kabupaten Bondowoso, Jawa Timur
            </div>
            <h1 class="text-5xl lg:text-7xl font-black text-white leading-[1.05] mb-6">
                Temukan Kuliner<br>
                <span class="bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">Terbaik</span>
                <br>Bondowoso
            </h1>
            <p class="text-gray-300 text-xl leading-relaxed mb-10 max-w-xl">
                {{ number_format($totalRestoran) }} restoran terverifikasi dari Dinas Pariwisata dan Google Maps. Data real, rating asli.
            </p>

            {{-- Search Bar --}}
            <form action="{{ route('restoran.index') }}" method="GET" class="flex gap-2 max-w-lg">
                <div class="flex-1 relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" placeholder="Cari restoran, warung, cafe..."
                           class="w-full pl-12 pr-4 py-4 bg-[#1a1f2e] border border-[#2d3548] text-white placeholder-gray-500 rounded-2xl focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-base">
                </div>
                <button type="submit" class="px-6 py-4 bg-amber-500 hover:bg-amber-400 text-[#0f1117] font-black rounded-2xl transition-all hover:scale-105 whitespace-nowrap">
                    Cari
                </button>
            </form>

            {{-- Quick filters --}}
            <div class="flex flex-wrap gap-2 mt-4">
                <a href="{{ route('restoran.index', ['sort' => 'saw']) }}" class="px-3 py-1.5 rounded-full bg-[#1a1f2e] border border-[#2d3548] text-gray-400 hover:border-amber-500/50 hover:text-amber-400 text-xs font-medium transition-all">⭐ Top SAW</a>
                <a href="{{ route('restoran.index', ['sort' => 'rating']) }}" class="px-3 py-1.5 rounded-full bg-[#1a1f2e] border border-[#2d3548] text-gray-400 hover:border-amber-500/50 hover:text-amber-400 text-xs font-medium transition-all">🔝 Rating Tertinggi</a>
                <a href="{{ route('restoran.index', ['buka_sekarang' => 1]) }}" class="px-3 py-1.5 rounded-full bg-[#1a1f2e] border border-[#2d3548] text-gray-400 hover:border-amber-500/50 hover:text-amber-400 text-xs font-medium transition-all">🟢 Buka Sekarang</a>
                <a href="{{ route('restoran.index', ['harga_max' => 50000]) }}" class="px-3 py-1.5 rounded-full bg-[#1a1f2e] border border-[#2d3548] text-gray-400 hover:border-amber-500/50 hover:text-amber-400 text-xs font-medium transition-all">💰 Di bawah 50K</a>
            </div>
        </div>
    </div>

    {{-- Stats bar --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 w-full max-w-lg px-4">
        <div class="bg-[#1a1f2e]/90 backdrop-blur border border-[#2d3548] rounded-2xl px-6 py-4">
            <div class="grid grid-cols-3 gap-4 divide-x divide-[#2d3548]">
                <div class="text-center">
                    <p class="text-2xl font-black text-white">{{ number_format($totalRestoran) }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Restoran</p>
                </div>
                <div class="text-center pl-4">
                    <p class="text-2xl font-black text-white">{{ number_format($totalUlasan) }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Ulasan</p>
                </div>
                <div class="text-center pl-4">
                    <p class="text-2xl font-black text-amber-400">{{ number_format($avgRatingKota, 1) }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Avg Rating</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Top Rekomendasi SAW --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex items-end justify-between mb-10">
        <div>
            <p class="text-amber-400 text-sm font-semibold mb-2">🏆 Berdasarkan Algoritma SAW</p>
            <h2 class="text-3xl font-black text-white">Restoran Terpilih</h2>
            <p class="text-gray-500 text-sm mt-1">Dipilih berdasarkan rating, sentimen ulasan, dan popularitas</p>
        </div>
        <a href="{{ route('restoran.index', ['sort' => 'saw']) }}" class="hidden sm:flex items-center gap-1 text-sm text-amber-400 hover:text-amber-300 font-semibold transition-colors">
            Lihat Semua
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if($topRestoran->isEmpty())
        <div class="text-center py-20">
            <div class="w-16 h-16 rounded-2xl bg-[#1a1f2e] border border-[#2d3548] flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <p class="text-gray-500 mb-4">Data restoran belum tersedia.</p>
            <a href="{{ route('restoran.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-[#0f1117] font-bold rounded-xl text-sm hover:bg-amber-400 transition-all">Lihat Semua Restoran</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($topRestoran as $i => $r)
            <a href="{{ route('restoran.show', $r->slug) }}" class="group bg-[#1a1f2e] border border-[#2d3548] rounded-2xl overflow-hidden hover:border-amber-500/30 hover:shadow-[0_20px_60px_-15px_rgba(245,158,11,0.15)] transition-all duration-300 hover:-translate-y-1 block">
                <div class="relative h-48 overflow-hidden bg-[#0f1117]">
                    <img src="{{ $r->foto_utama_url }}" alt="{{ $r->nama_usaha }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90"
                         loading="lazy"
                         onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&q=80'">
                    {{-- Rank badge --}}
                    <div class="absolute top-3 left-3">
                        @if($i < 3)
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl font-black text-sm
                            {{ $i === 0 ? 'bg-amber-400 text-[#0f1117]' : ($i === 1 ? 'bg-gray-300 text-[#0f1117]' : 'bg-orange-600 text-white') }}">
                            {{ $i + 1 }}
                        </span>
                        @endif
                    </div>
                    {{-- SAW Badge --}}
                    @if($r->skor_saw)
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center gap-1 bg-[#0f1117]/80 backdrop-blur text-xs text-amber-400 font-bold px-2 py-1 rounded-lg">
                            <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            {{ number_format($r->skor_saw ?? 0, 3) }}
                        </span>
                    </div>
                    @endif
                    {{-- Status --}}
                    <div class="absolute bottom-3 left-3">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $r->is_buka ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $r->is_buka ? 'Buka' : 'Tutup' }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-white group-hover:text-amber-400 transition-colors truncate text-base">{{ $r->nama_usaha }}</h3>
                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span class="text-xs font-bold text-amber-400">{{ number_format($r->avg_rating, 1) }}</span>
                        </div>
                        <span class="text-gray-600 text-xs">·</span>
                        <span class="text-gray-500 text-xs">{{ $r->total_ulasan }} ulasan</span>
                        <span class="text-gray-600 text-xs">·</span>
                        <span class="text-xs px-1.5 py-0.5 rounded bg-[#2d3548] text-gray-400">{{ ucfirst($r->sumber) }}</span>
                    </div>
                    <div class="flex items-center gap-1 mt-2">
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

        <div class="text-center mt-8">
            <a href="{{ route('restoran.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#1a1f2e] border border-[#2d3548] hover:border-amber-500/50 text-white font-semibold rounded-xl text-sm transition-all">
                Lihat Semua Restoran
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    @endif
</section>

{{-- App Download CTA --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="bg-gradient-to-br from-amber-500/10 via-[#1a1f2e] to-orange-500/5 border border-amber-500/20 rounded-3xl p-8 md:p-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-amber-500/20 flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.7 9.05 7.42c1.32.07 2.25.8 3.03.82.97-.19 1.9-.95 3.14-.86 1.97.17 3.34 1.1 4.05 2.66-3.57 2.02-2.7 6.98.78 8.24zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
        </div>
        <h2 class="text-3xl font-black text-white mb-3">Ingin Fitur Lengkap?</h2>
        <p class="text-gray-400 text-base max-w-lg mx-auto mb-8">
            Tulis ulasan, simpan favorit, dapatkan rekomendasi personal berbasis SAW, dan lihat analisis sentimen di aplikasi mobile BondoWisata.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#" class="flex items-center gap-3 px-6 py-3.5 bg-white rounded-2xl hover:bg-gray-100 transition-all group">
                <svg class="w-7 h-7 text-gray-800" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.7 9.05 7.42c1.32.07 2.25.8 3.03.82.97-.19 1.9-.95 3.14-.86 1.97.17 3.34 1.1 4.05 2.66-3.57 2.02-2.7 6.98.78 8.24zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
                <div class="text-left"><p class="text-[10px] text-gray-500 font-medium">Download di</p><p class="text-sm font-black text-gray-900">App Store</p></div>
            </a>
            <a href="#" class="flex items-center gap-3 px-6 py-3.5 bg-[#2d3548] border border-[#3d4558] rounded-2xl hover:bg-[#3d4558] transition-all">
                <svg class="w-7 h-7 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M3 20.5v-17c0-.83.94-1.3 1.6-.8l14 8.5c.6.36.6 1.24 0 1.6l-14 8.5c-.66.5-1.6.03-1.6-.8z"/></svg>
                <div class="text-left"><p class="text-[10px] text-gray-500 font-medium">Tersedia di</p><p class="text-sm font-black text-white">Google Play</p></div>
            </a>
        </div>
    </div>
</section>

@endsection
