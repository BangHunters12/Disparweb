@extends('layouts.public')
@section('title', 'Jelajahi Wisata Bondowoso')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-black text-white mb-2">Jelajahi Wisata</h1>
    <p class="text-gray-400 mb-8">{{ $tempat->total() }} tempat ditemukan di Bondowoso</p>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- FILTER SIDEBAR --}}
        <aside class="lg:col-span-1">
            <form method="GET" action="{{ route('explore') }}" class="card p-5 space-y-5 sticky top-24">
                <h3 class="font-semibold text-white">Filter & Cari</h3>

                <div>
                    <label class="form-label">Cari Tempat</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama tempat..." class="form-input">
                </div>

                <div>
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-input">
                        <option value="">Semua</option>
                        <option value="restoran" {{ request('kategori') === 'restoran' ? 'selected' : '' }}>🍽️ Restoran</option>
                        <option value="hotel" {{ request('kategori') === 'hotel' ? 'selected' : '' }}>🏨 Hotel</option>
                        <option value="ekraf" {{ request('kategori') === 'ekraf' ? 'selected' : '' }}>🎨 Ekraf</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Kecamatan</label>
                    <select name="kecamatan_id" class="form-input">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatanList as $kec)
                            <option value="{{ $kec->id }}" {{ request('kecamatan_id') === $kec->id ? 'selected' : '' }}>{{ $kec->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Harga Min (Rp)</label>
                    <input type="number" name="harga_min" value="{{ request('harga_min') }}" placeholder="0" class="form-input">
                </div>

                <div>
                    <label class="form-label">Harga Max (Rp)</label>
                    <input type="number" name="harga_max" value="{{ request('harga_max') }}" placeholder="1.000.000" class="form-input">
                </div>

                <div>
                    <label class="form-label">Urutkan</label>
                    <select name="sort_by" class="form-input">
                        <option value="terbaru" {{ request('sort_by') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="rating" {{ request('sort_by') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="saw" {{ request('sort_by') === 'saw' ? 'selected' : '' }}>Rekomendasi SAW</option>
                        <option value="harga_asc" {{ request('sort_by') === 'harga_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="harga_desc" {{ request('sort_by') === 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="nama" {{ request('sort_by') === 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary w-full justify-center">Terapkan Filter</button>
                @if(request()->hasAny(['search','kategori','kecamatan_id','harga_min','harga_max','sort_by']))
                    <a href="{{ route('explore') }}" class="btn-secondary w-full justify-center">Reset</a>
                @endif
            </form>
        </aside>

        {{-- RESULTS --}}
        <div class="lg:col-span-3">
            @if($tempat->isEmpty())
                <div class="card p-16 text-center">
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="text-xl font-bold text-white mb-2">Tidak ada hasil</h3>
                    <p class="text-gray-400">Coba ubah filter pencarian Anda</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($tempat as $t)
                        @php
                            $avgRating = $t->ulasan->avg('rating') ?? 0;
                            $totalUlasan = $t->ulasan->count();
                        @endphp
                        <a href="{{ route('tempat.show', $t->id) }}" class="card-hover group overflow-hidden block">
                            <div class="relative h-44 bg-dark-700 overflow-hidden">
                                @if($t->foto_utama)
                                    <img src="{{ Storage::url($t->foto_utama) }}" alt="{{ $t->nama_usaha }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-5xl">
                                        {{ $t->kategori->jenis === 'restoran' ? '🍽️' : ($t->kategori->jenis === 'hotel' ? '🏨' : '🎨') }}
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2">
                                    <span class="badge {{ $t->kategori->jenis === 'restoran' ? 'badge-amber' : ($t->kategori->jenis === 'hotel' ? 'badge-blue' : 'badge-green') }}">
                                        {{ ucfirst($t->kategori->jenis) }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-white group-hover:text-amber-400 transition-colors truncate">{{ $t->nama_usaha }}</h3>
                                <p class="text-gray-400 text-xs mt-1 mb-3 truncate">📍 {{ $t->kecamatan->nama }}</p>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-1">
                                        @if($avgRating > 0)
                                            <span class="text-amber-400">⭐</span>
                                            <span class="font-semibold text-white">{{ number_format($avgRating, 1) }}</span>
                                            <span class="text-gray-500">({{ $totalUlasan }})</span>
                                        @else
                                            <span class="text-gray-500 text-xs">Belum ada ulasan</span>
                                        @endif
                                    </div>
                                    @if($t->harga_min)
                                        <span class="text-gray-400 text-xs">Rp {{ number_format($t->harga_min, 0, ',', '.') }}+</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $tempat->withQueryString()->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
