@extends('layouts.public')
@section('title', 'Jelajahi Wisata')
@section('meta-description', 'Jelajahi semua tempat wisata, restoran, dan hotel di Kabupaten Bondowoso.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ═══════════════════════════════════
         Quick Category Chips (di atas)
    ═══════════════════════════════════ --}}
    <div class="mb-6">
        <div class="flex flex-wrap items-center gap-3">
            {{-- Semua --}}
            <a href="{{ route('explore', array_merge(request()->except('kategori'), ['kategori' => ''])) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all
                      {{ !request('kategori') ? 'bg-amber-500 text-dark-900 shadow-lg shadow-amber-500/20' : 'bg-dark-700 text-gray-300 hover:bg-dark-600 border border-dark-600 hover:border-amber-500/50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                Semua
                <span class="text-xs opacity-75">({{ $tempat->total() }})</span>
            </a>

            @php
                $chipMeta = [
                    'restoran' => [
                        'label' => 'Restoran & Kuliner',
                        'active_class' => 'bg-amber-500/20 text-amber-400 border border-amber-500/50 shadow-lg shadow-amber-500/10',
                        'inactive_class' => 'bg-dark-700 text-gray-300 hover:bg-dark-600 border border-dark-600 hover:border-amber-500/30',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>',
                    ],
                    'hotel' => [
                        'label' => 'Hotel & Penginapan',
                        'active_class' => 'bg-blue-500/20 text-blue-400 border border-blue-500/50 shadow-lg shadow-blue-500/10',
                        'inactive_class' => 'bg-dark-700 text-gray-300 hover:bg-dark-600 border border-dark-600 hover:border-blue-500/30',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                    ],
                    'ekraf' => [
                        'label' => 'Ekonomi Kreatif',
                        'active_class' => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/50 shadow-lg shadow-emerald-500/10',
                        'inactive_class' => 'bg-dark-700 text-gray-300 hover:bg-dark-600 border border-dark-600 hover:border-emerald-500/30',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
                    ],
                ];
            @endphp

            @foreach($kategoriList as $kat)
                @php $chip = $chipMeta[$kat->jenis] ?? null; @endphp
                @if($chip)
                    @php $isActive = request('kategori') === $kat->jenis; @endphp
                    <a href="{{ route('explore', array_merge(request()->except('kategori', 'page'), ['kategori' => $isActive ? '' : $kat->jenis])) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all
                              {{ $isActive ? $chip['active_class'] : $chip['inactive_class'] }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $chip['icon'] !!}
                        </svg>
                        {{ $chip['label'] }}
                        <span class="text-xs opacity-60">({{ $kat->tempat_count }})</span>
                    </a>
                @endif
            @endforeach

            {{-- Sort cepat --}}
            <div class="flex-1 flex justify-end">
                <select onchange="window.location.href=this.value"
                        class="bg-dark-700 border border-dark-600 text-gray-300 text-sm rounded-xl px-3 py-2 focus:border-amber-500 focus:outline-none">
                    @php $baseParams = request()->except('sort_by', 'page'); @endphp
                    <option value="{{ route('explore', array_merge($baseParams, ['sort_by'=>'terbaru'])) }}"
                        {{ request('sort_by','terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="{{ route('explore', array_merge($baseParams, ['sort_by'=>'saw'])) }}"
                        {{ request('sort_by') === 'saw' ? 'selected' : '' }}>⭐ SAW Terbaik</option>
                    <option value="{{ route('explore', array_merge($baseParams, ['sort_by'=>'rating'])) }}"
                        {{ request('sort_by') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    <option value="{{ route('explore', array_merge($baseParams, ['sort_by'=>'harga_asc'])) }}"
                        {{ request('sort_by') === 'harga_asc' ? 'selected' : '' }}>Harga Termurah</option>
                    <option value="{{ route('explore', array_merge($baseParams, ['sort_by'=>'harga_desc'])) }}"
                        {{ request('sort_by') === 'harga_desc' ? 'selected' : '' }}>Harga Termahal</option>
                    <option value="{{ route('explore', array_merge($baseParams, ['sort_by'=>'nama'])) }}"
                        {{ request('sort_by') === 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════
         Layout: Sidebar + Results
    ═══════════════════════════════════ --}}
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Sidebar Filter --}}
        <aside class="lg:w-60 flex-shrink-0">
            <div class="card p-5 sticky top-20">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </h2>
                    @if(request()->hasAny(['search','kategori','kecamatan_id','harga_min','harga_max']))
                        <a href="{{ route('explore', request()->only('sort_by')) }}"
                           class="text-xs text-red-400 hover:text-red-300 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reset
                        </a>
                    @endif
                </div>
                <form method="GET" action="{{ route('explore') }}" class="space-y-4" id="filterForm">
                    @if(request('sort_by'))
                        <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                    @endif

                    {{-- Search --}}
                    <div>
                        <label class="form-label">Cari Tempat</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Nama tempat..." class="form-input pl-9">
                        </div>
                    </div>

                    {{-- Kategori Radio --}}
                    <div>
                        <label class="form-label">Kategori</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2.5 cursor-pointer group py-1">
                                <input type="radio" name="kategori" value=""
                                       {{ !request('kategori') ? 'checked' : '' }}
                                       class="text-amber-500 w-4 h-4 accent-amber-500">
                                <span class="text-sm text-gray-400 group-hover:text-white transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                    Semua Kategori
                                </span>
                            </label>

                            @foreach($kategoriList as $kat)
                                @php $chip = $chipMeta[$kat->jenis] ?? null; @endphp
                                @if($chip)
                                    <label class="flex items-center gap-2.5 cursor-pointer group py-1">
                                        <input type="radio" name="kategori" value="{{ $kat->jenis }}"
                                               {{ request('kategori') === $kat->jenis ? 'checked' : '' }}
                                               class="w-4 h-4 accent-amber-500">
                                        <span class="text-sm text-gray-400 group-hover:text-white transition-colors flex items-center gap-2 flex-1">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $chip['icon'] !!}
                                            </svg>
                                            {{ $chip['label'] }}
                                        </span>
                                        <span class="text-xs text-gray-600 flex-shrink-0">{{ $kat->tempat_count }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Kecamatan --}}
                    <div>
                        <label class="form-label">Kecamatan</label>
                        <select name="kecamatan_id" class="form-input">
                            <option value="">Semua Kecamatan</option>
                            @foreach($kecamatanList as $kec)
                                <option value="{{ $kec->id }}" {{ request('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                    {{ $kec->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Harga --}}
                    <div>
                        <label class="form-label">Rentang Harga (Rp)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="harga_min" value="{{ request('harga_min') }}"
                                   placeholder="Min" class="form-input text-xs">
                            <input type="number" name="harga_max" value="{{ request('harga_max') }}"
                                   placeholder="Max" class="form-input text-xs">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </aside>

        {{-- ═══════════════ Results ═══════════════ --}}
        <div class="flex-1 min-w-0">
            {{-- Result header --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-gray-400 text-sm">
                        Menampilkan <span class="text-white font-semibold">{{ $tempat->total() }}</span> tempat
                        @if(request('search'))
                            untuk "<span class="text-amber-400">{{ request('search') }}</span>"
                        @endif
                        @if(request('kategori'))
                            <span class="ml-1">
                                @php $chipActive = $chipMeta[request('kategori')] ?? null; @endphp
                                kategori <span class="text-amber-400 font-medium">{{ $chipActive['label'] ?? request('kategori') }}</span>
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            @if($tempat->isEmpty())
                <div class="card p-16 text-center">
                    <svg class="w-14 h-14 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-lg font-bold text-white mb-2">Tidak ada hasil</h3>
                    <p class="text-gray-500 mb-4">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    <a href="{{ route('explore') }}" class="btn btn-secondary btn-sm">Reset Filter</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($tempat as $t)
                        @php
                            $placeholders = [
                                'restoran' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&q=80',
                                'hotel'    => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=400&q=80',
                                'ekraf'    => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=80',
                            ];
                            $imgSrc = $t->foto_utama
                                ? Storage::url($t->foto_utama)
                                : ($placeholders[$t->kategori->jenis] ?? $placeholders['ekraf']);
                            $avgRating = $t->ulasan->avg('rating') ?? 0;
                            $jmlUlasan = $t->ulasan->count();

                            $badgeClass = match($t->kategori->jenis) {
                                'restoran' => 'badge-amber',
                                'hotel'    => 'badge-blue',
                                default    => 'badge-green',
                            };
                        @endphp
                        <a href="{{ route('tempat.show', $t->id) }}" class="card-hover group overflow-hidden block">
                            <div class="h-44 bg-dark-700 overflow-hidden relative">
                                <img src="{{ $imgSrc }}" alt="{{ $t->nama_usaha }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     loading="lazy"
                                     onerror="this.src='{{ $placeholders['ekraf'] }}'">
                                {{-- Kategori badge --}}
                                <div class="absolute top-3 right-3">
                                    <span class="badge {{ $badgeClass }}">{{ $chipMeta[$t->kategori->jenis]['label'] ?? ucfirst($t->kategori->jenis) }}</span>
                                </div>
                                @if($t->status === 'tutup')
                                    <div class="absolute inset-0 bg-dark-900/70 flex items-center justify-center">
                                        <span class="badge badge-red text-sm font-bold">Tutup</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-white group-hover:text-amber-400 transition-colors truncate">{{ $t->nama_usaha }}</h3>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
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

                <div class="mt-6">
                    {{ $tempat->withQueryString()->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
