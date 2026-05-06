@extends('layouts.public')
@section('title', 'Beranda')
@section('meta-description', 'Temukan rekomendasi wisata terbaik di Bondowoso — Restoran, Hotel, dan Ekonomi Kreatif pilihan.')

@section('content')
{{-- Hero Section --}}
<section class="relative overflow-hidden min-h-[80vh] flex items-center">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1600&q=80"
             alt="Wisata Bondowoso" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(15,17,23,0.95) 0%,rgba(15,17,23,0.7) 60%,rgba(245,158,11,0.15) 100%)"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 w-full">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 bg-amber-500/10 border border-amber-500/20 rounded-full px-4 py-1.5 text-amber-400 text-sm font-medium mb-6">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                Kabupaten Bondowoso, Jawa Timur
            </div>
            <h1 class="text-5xl lg:text-6xl font-black text-white leading-tight mb-5">
                Jelajahi Wisata<br><span class="text-gradient">Bondowoso</span>
            </h1>
            <p class="text-gray-300 text-lg leading-relaxed mb-8">
                Temukan rekomendasi restoran, hotel, dan ekonomi kreatif terbaik berdasarkan data nyata dan analisis sentimen pengguna.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('explore') }}" class="btn btn-primary btn-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Jelajahi Sekarang
                </a>
                <a href="{{ route('map') }}" class="btn btn-secondary btn-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Lihat Peta
                </a>
            </div>
        </div>
    </div>

    {{-- Stats bar --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 w-full max-w-2xl px-4">
        <div class="bg-dark-800/80 backdrop-blur border border-dark-700 rounded-2xl px-6 py-4">
            <div class="grid grid-cols-3 gap-4 divide-x divide-dark-700">
                <div class="text-center">
                    <p class="text-2xl font-black text-white">{{ number_format($totalTempat) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Tempat Wisata</p>
                </div>
                <div class="text-center pl-4">
                    <p class="text-2xl font-black text-white">{{ number_format($totalUlasan) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Ulasan</p>
                </div>
                <div class="text-center pl-4">
                    <p class="text-2xl font-black text-white">{{ number_format($totalKecamatan) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Kecamatan</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Kategori Cards --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <h2 class="section-title">Kategori Wisata</h2>
        <p class="section-sub">Pilih kategori yang sesuai dengan kebutuhanmu</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @php
            $categoryMeta = [
                'restoran' => [
                    'label' => 'Restoran & Kuliner',
                    'desc'  => 'Nikmati cita rasa khas Bondowoso dari warung lokal hingga restoran modern.',
                    'img'   => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&q=80',
                    'color' => 'amber',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>',
                ],
                'hotel' => [
                    'label' => 'Hotel & Penginapan',
                    'desc'  => 'Temukan akomodasi terbaik dengan fasilitas lengkap sesuai budget Anda.',
                    'img'   => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80',
                    'color' => 'blue',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                ],
                'ekraf' => [
                    'label' => 'Ekonomi Kreatif',
                    'desc'  => 'Dukung UMKM lokal dan temukan produk kerajinan unik khas Bondowoso.',
                    'img'   => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?w=600&q=80',
                    'color' => 'green',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
                ],
            ];
        @endphp

        @foreach($kategoriList as $kat)
            @php $meta = $categoryMeta[$kat->jenis] ?? null; @endphp
            @if($meta)
                @php
                    $colorMap = [
                        'amber' => ['badge' => 'bg-amber-500/20 text-amber-400 border-amber-500/30', 'arrow' => 'text-amber-400', 'icon_bg' => 'bg-amber-500/20', 'icon_c' => 'text-amber-400'],
                        'blue'  => ['badge' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',   'arrow' => 'text-blue-400',  'icon_bg' => 'bg-blue-500/20',  'icon_c' => 'text-blue-400'],
                        'green' => ['badge' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30', 'arrow' => 'text-emerald-400', 'icon_bg' => 'bg-emerald-500/20', 'icon_c' => 'text-emerald-400'],
                    ];
                    $c = $colorMap[$meta['color']];
                @endphp
                <a href="{{ route('explore', ['kategori' => $kat->jenis]) }}"
                   class="card-hover group overflow-hidden relative block">
                    <div class="h-52 overflow-hidden relative">
                        <img src="{{ $meta['img'] }}" alt="{{ $meta['label'] }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-60">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-dark-900/50 to-transparent"></div>
                        {{-- Jumlah tempat badge --}}
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-lg border {{ $c['badge'] }}">
                                {{ $kat->tempat_count }} tempat
                            </span>
                        </div>
                        {{-- Icon --}}
                        <div class="absolute top-3 left-3">
                            <div class="w-9 h-9 rounded-xl {{ $c['icon_bg'] }} flex items-center justify-center">
                                <svg class="w-5 h-5 {{ $c['icon_c'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $meta['icon'] !!}
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-5">
                        <h3 class="font-bold text-white text-lg mb-1">{{ $meta['label'] }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $meta['desc'] }}</p>
                        <span class="inline-flex items-center gap-1 mt-3 {{ $c['arrow'] }} text-sm font-medium">
                            Jelajahi
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</section>

{{-- Top Rekomendasi SAW --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="section-title">Rekomendasi Teratas</h2>
            <p class="section-sub">Dipilih berdasarkan algoritma SAW</p>
        </div>
        <a href="{{ route('explore', ['sort_by' => 'saw']) }}" class="btn btn-secondary btn-sm">
            Lihat Semua
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if($topRekomendasi->isEmpty())
        <div class="card p-16 text-center">
            <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-gray-400 mb-4">Belum ada data rekomendasi.</p>
            <a href="{{ route('explore') }}" class="btn btn-primary">Lihat Semua Tempat</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($topRekomendasi as $i => $r)
                @php
                    $t = $r->tempat;
                    if (!$t) continue;
                    $placeholders = [
                        'restoran' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&q=80',
                        'hotel'    => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=400&q=80',
                        'ekraf'    => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=80',
                    ];
                    $imgSrc = $t->foto_utama
                        ? Storage::url($t->foto_utama)
                        : ($placeholders[$t->kategori->jenis] ?? $placeholders['ekraf']);
                    $avgRating = $t->ulasan()->avg('rating') ?? 0;
                    $jmlUlasan = $t->ulasan()->count();
                @endphp
                <a href="{{ route('tempat.show', $t->id) }}" class="card-hover group overflow-hidden block">
                    <div class="relative h-48 overflow-hidden bg-dark-700">
                        <img src="{{ $imgSrc }}" alt="{{ $t->nama_usaha }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy"
                             onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&q=80'">
                        {{-- Peringkat badge --}}
                        <div class="absolute top-3 left-3">
                            @if($r->peringkat <= 3)
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl font-black text-sm
                                    {{ $r->peringkat === 1 ? 'bg-amber-400 text-dark-900' : ($r->peringkat === 2 ? 'bg-gray-300 text-dark-900' : 'bg-orange-500 text-white') }}">
                                    {{ $r->peringkat }}
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-dark-800/80 text-gray-300 font-bold text-xs">
                                    #{{ $r->peringkat }}
                                </span>
                            @endif
                        </div>
                        {{-- Kategori badge --}}
                        <div class="absolute top-3 right-3">
                            <span class="badge {{ $t->kategori->jenis === 'restoran' ? 'badge-amber' : ($t->kategori->jenis === 'hotel' ? 'badge-blue' : 'badge-green') }}">
                                {{ ucfirst($t->kategori->jenis) }}
                            </span>
                        </div>
                        {{-- Skor SAW --}}
                        <div class="absolute bottom-3 right-3">
                            <span class="inline-flex items-center gap-1 bg-dark-900/80 backdrop-blur text-xs text-amber-400 font-bold px-2 py-1 rounded-lg">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                SAW {{ number_format($r->skor_saw_final, 3) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-white group-hover:text-amber-400 transition-colors truncate">{{ $t->nama_usaha }}</h3>
                        <div class="flex items-center gap-2 mt-1.5">
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <span class="text-xs font-semibold text-amber-400">{{ number_format($avgRating, 1) }}</span>
                            </div>
                            <span class="text-gray-600 text-xs">·</span>
                            <span class="text-gray-500 text-xs">{{ $jmlUlasan }} ulasan</span>
                        </div>
                        <div class="flex items-center gap-1 mt-1.5">
                            <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
                            <span class="text-xs text-gray-500 truncate">{{ $t->kecamatan->nama }}</span>
                        </div>
                        @if($t->harga_min)
                            <p class="text-amber-400 text-xs font-semibold mt-2">Rp {{ number_format($t->harga_min, 0, ',', '.') }}+</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</section>
@endsection
