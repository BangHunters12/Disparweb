@extends('layouts.public')
@section('title', 'Jelajahi Wisata')
@section('meta-description', 'Jelajahi semua tempat wisata, restoran, dan hotel di Kabupaten Bondowoso.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Sidebar Filter --}}
        <aside class="lg:w-64 flex-shrink-0">
            <div class="card p-5 sticky top-20">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-white">Filter</h2>
                    @if(request()->hasAny(['search','kategori','kecamatan_id','harga_min','harga_max','sort_by']))
                        <a href="{{ route('explore') }}" class="text-xs text-red-400 hover:text-red-300">Reset</a>
                    @endif
                </div>
                <form method="GET" action="{{ route('explore') }}" class="space-y-4" id="filterForm">
                    <div>
                        <label class="form-label">Cari</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama tempat..." class="form-input pl-9">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Kategori</label>
                        <div class="space-y-2">
                            @foreach($kategoriList as $k)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="kategori" value="{{ $k->jenis }}" {{ request('kategori') === $k->jenis ? 'checked' : '' }} class="text-amber-500">
                                    <span class="text-sm text-gray-400 group-hover:text-white transition-colors capitalize">{{ $k->jenis }}</span>
                                </label>
                            @endforeach
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="kategori" value="" {{ !request('kategori') ? 'checked' : '' }} class="text-amber-500">
                                <span class="text-sm text-gray-400 group-hover:text-white transition-colors">Semua</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Kecamatan</label>
                        <select name="kecamatan_id" class="form-input">
                            <option value="">Semua Kecamatan</option>
                            @foreach($kecamatanList as $kec)
                                <option value="{{ $kec->id }}" {{ request('kecamatan_id') == $kec->id ? 'selected' : '' }}>{{ $kec->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Harga Min (Rp)</label>
                        <input type="number" name="harga_min" value="{{ request('harga_min') }}" placeholder="0" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Harga Max (Rp)</label>
                        <input type="number" name="harga_max" value="{{ request('harga_max') }}" placeholder="500000" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Urutkan</label>
                        <select name="sort_by" class="form-input">
                            <option value="terbaru" {{ request('sort_by','terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="saw" {{ request('sort_by') === 'saw' ? 'selected' : '' }}>Rekomendasi SAW</option>
                            <option value="rating" {{ request('sort_by') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                            <option value="harga_asc" {{ request('sort_by') === 'harga_asc' ? 'selected' : '' }}>Harga Termurah</option>
                            <option value="harga_desc" {{ request('sort_by') === 'harga_desc' ? 'selected' : '' }}>Harga Termahal</option>
                            <option value="nama" {{ request('sort_by') === 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-full justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </aside>

        {{-- Results --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-5">
                <p class="text-gray-400 text-sm">
                    Menampilkan <span class="text-white font-semibold">{{ $tempat->total() }}</span> tempat
                    @if(request('search')) untuk "<span class="text-amber-400">{{ request('search') }}</span>" @endif
                </p>
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
                        @endphp
                        <a href="{{ route('tempat.show', $t->id) }}" class="card-hover group overflow-hidden block">
                            <div class="h-44 bg-dark-700 overflow-hidden relative">
                                <img src="{{ $imgSrc }}" alt="{{ $t->nama_usaha }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     loading="lazy"
                                     onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&q=80'">
                                <div class="absolute top-3 right-3">
                                    <span class="badge {{ $t->kategori->jenis === 'restoran' ? 'badge-amber' : ($t->kategori->jenis === 'hotel' ? 'badge-blue' : 'badge-green') }}">
                                        {{ ucfirst($t->kategori->jenis) }}
                                    </span>
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
                                        <svg class="w-3.5 h-3.5 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        <span class="text-xs font-semibold text-amber-400">{{ number_format($avgRating, 1) }}</span>
                                    </div>
                                    <span class="text-gray-600 text-xs">·</span>
                                    <span class="text-gray-500 text-xs">{{ $t->ulasan->count() }} ulasan</span>
                                </div>
                                <div class="flex items-center gap-1 mt-1.5">
                                    <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
                                    <span class="text-xs text-gray-500">{{ $t->kecamatan->nama }}</span>
                                </div>
                                @if($t->harga_min)
                                    <p class="text-amber-400 text-xs font-semibold mt-2">Rp {{ number_format($t->harga_min, 0, ',', '.') }}+</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6">{{ $tempat->withQueryString()->links('vendor.pagination.custom') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
