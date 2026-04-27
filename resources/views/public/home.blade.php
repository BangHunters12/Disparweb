@extends('layouts.public')
@section('title', 'BondoWisata — Wisata Terbaik Bondowoso')
@section('description', 'Temukan rekomendasi restoran, hotel, dan produk ekonomi kreatif terbaik di Bondowoso berdasarkan data resmi Dinas Pariwisata.')

@section('content')
{{-- HERO --}}
<section class="relative overflow-hidden bg-dark-900">
    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 via-transparent to-blue-500/5 pointer-events-none"></div>
    <div class="absolute top-20 right-10 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-amber-500/10 border border-amber-500/20 rounded-full px-4 py-1.5 text-amber-400 text-sm font-medium mb-6">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                Resmi dari Dinas Pariwisata Bondowoso
            </div>
            <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-6">
                Jelajahi <span class="text-gradient">Wisata</span><br>Bondowoso
            </h1>
            <p class="text-gray-400 text-lg md:text-xl leading-relaxed mb-10 max-w-2xl">
                Temukan restoran, hotel, dan produk ekonomi kreatif terbaik di Bondowoso. Rekomendasi cerdas berbasis AI untuk pengalaman wisata yang tak terlupakan.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('explore') }}" class="btn-primary btn-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Mulai Jelajahi
                </a>
                <a href="{{ route('map') }}" class="btn-secondary btn-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Lihat Peta
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-6 mt-16 max-w-lg">
            <div class="text-center">
                <p class="text-3xl font-black text-gradient">{{ number_format($totalTempat) }}+</p>
                <p class="text-gray-400 text-sm mt-1">Tempat Wisata</p>
            </div>
            <div class="text-center border-x border-dark-700">
                <p class="text-3xl font-black text-gradient">{{ number_format($totalUlasan) }}+</p>
                <p class="text-gray-400 text-sm mt-1">Ulasan</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-black text-gradient">{{ $totalKecamatan }}</p>
                <p class="text-gray-400 text-sm mt-1">Kecamatan</p>
            </div>
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
<section class="py-16 border-t border-dark-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="section-title text-center">Kategori Wisata</h2>
        <p class="section-sub text-center">Pilih kategori sesuai kebutuhanmu</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            @php
                $jenisGroups = $kategoriList->groupBy('jenis');
                $jenisMeta = [
                    'restoran' => ['label' => 'Restoran & Kuliner', 'icon' => '🍽️', 'color' => 'amber', 'desc' => 'Rumah makan, kafe, bakeri khas Bondowoso'],
                    'hotel' => ['label' => 'Hotel & Penginapan', 'icon' => '🏨', 'color' => 'blue', 'desc' => 'Hotel, homestay, villa, dan cottages'],
                    'ekraf' => ['label' => 'Ekonomi Kreatif', 'icon' => '🎨', 'color' => 'green', 'desc' => 'Batik, kerajinan, kopi, seni budaya'],
                ];
            @endphp

            @foreach(['restoran', 'hotel', 'ekraf'] as $jenis)
                @php
                    $meta = $jenisMeta[$jenis];
                    $count = $kategoriList->where('jenis', $jenis)->sum('tempat_count');
                    $colorMap = ['amber' => 'bg-amber-500/10 border-amber-500/20 text-amber-400', 'blue' => 'bg-blue-500/10 border-blue-500/20 text-blue-400', 'green' => 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400'];
                    $color = $colorMap[$meta['color']];
                @endphp
                <a href="{{ route('explore', ['kategori' => $jenis]) }}"
                   class="card-hover p-8 group cursor-pointer block">
                    <div class="text-4xl mb-4">{{ $meta['icon'] }}</div>
                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-amber-400 transition-colors">{{ $meta['label'] }}</h3>
                    <p class="text-gray-400 text-sm mb-4">{{ $meta['desc'] }}</p>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $color }}">
                        {{ $count }} tempat
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- TOP RECOMMENDATIONS --}}
@if($topRekomendasi->count())
<section class="py-16 border-t border-dark-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="section-title mb-0">🏆 Top Rekomendasi SAW</h2>
                <p class="text-gray-400 text-sm mt-1">Dihitung menggunakan metode Simple Additive Weighting</p>
            </div>
            <a href="{{ route('explore', ['sort_by' => 'saw']) }}" class="btn-secondary btn-sm">Lihat Semua</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($topRekomendasi as $rekomendasi)
                @php $t = $rekomendasi->tempat; @endphp
                <a href="{{ route('tempat.show', $t->id) }}" class="card-hover group overflow-hidden block">
                    <div class="relative h-48 bg-dark-700 overflow-hidden">
                        @if($t->foto_utama)
                            <img src="{{ Storage::url($t->foto_utama) }}" alt="{{ $t->nama_usaha }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-5xl">
                                {{ $t->kategori->jenis === 'restoran' ? '🍽️' : ($t->kategori->jenis === 'hotel' ? '🏨' : '🎨') }}
                            </div>
                        @endif
                        <div class="absolute top-3 left-3">
                            <span class="badge-amber">#{{ $rekomendasi->peringkat }}</span>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="badge {{ $t->kategori->jenis === 'restoran' ? 'badge-amber' : ($t->kategori->jenis === 'hotel' ? 'badge-blue' : 'badge-green') }}">
                                {{ ucfirst($t->kategori->jenis) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-white group-hover:text-amber-400 transition-colors mb-1 truncate">{{ $t->nama_usaha }}</h3>
                        <p class="text-gray-400 text-sm mb-3 truncate">📍 {{ $t->kecamatan->nama }}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1">
                                <span class="text-amber-400">⭐</span>
                                <span class="text-sm font-semibold text-white">{{ number_format($rekomendasi->skor_saw_final, 3) }}</span>
                                <span class="text-xs text-gray-500">SAW</span>
                            </div>
                            @if($t->harga_min)
                                <span class="text-xs text-gray-400">Rp {{ number_format($t->harga_min, 0, ',', '.') }}+</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- TERBARU --}}
@if($tempatTerbaru->count())
<section class="py-16 border-t border-dark-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="section-title mb-0">✨ Terbaru Ditambahkan</h2>
            <a href="{{ route('explore') }}" class="btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($tempatTerbaru as $t)
                <a href="{{ route('tempat.show', $t->id) }}" class="card-hover group overflow-hidden block">
                    <div class="h-36 bg-dark-700 flex items-center justify-center text-4xl overflow-hidden">
                        @if($t->foto_utama)
                            <img src="{{ Storage::url($t->foto_utama) }}" alt="{{ $t->nama_usaha }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            {{ $t->kategori->jenis === 'restoran' ? '🍽️' : ($t->kategori->jenis === 'hotel' ? '🏨' : '🎨') }}
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-white text-sm group-hover:text-amber-400 transition-colors truncate">{{ $t->nama_usaha }}</h3>
                        <p class="text-gray-500 text-xs mt-1">{{ $t->kecamatan->nama }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
