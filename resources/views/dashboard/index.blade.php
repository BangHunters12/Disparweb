@extends('layouts.dashboard')
@section('title', 'Dashboard Saya')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <div class="stat-icon bg-amber-500/20 text-amber-400 text-2xl">📝</div>
        <div>
            <p class="text-2xl font-black text-white">{{ $totalUlasan }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Ulasan Saya</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-red-500/20 text-red-400 text-2xl">❤️</div>
        <div>
            <p class="text-2xl font-black text-white">{{ $totalFavorit }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Favorit</p>
        </div>
    </div>
    <div class="stat-card col-span-2">
        <div class="stat-icon bg-blue-500/20 text-blue-400 text-2xl">👋</div>
        <div>
            <p class="text-lg font-bold text-white">Halo, {{ explode(' ', $user->nama_lengkap)[0] }}!</p>
            <p class="text-xs text-gray-400 mt-0.5">Selamat datang di BondoWisata</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Reviews --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-white">Ulasan Terbaru</h3>
            <a href="{{ route('dashboard.ulasan') }}" class="text-amber-400 text-sm hover:text-amber-300">Lihat Semua →</a>
        </div>
        @forelse($recentUlasan as $u)
            <div class="py-3 border-b border-dark-700 last:border-0 flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-dark-700 flex items-center justify-center text-lg flex-shrink-0 overflow-hidden">
                    @if($u->tempat->foto_utama)
                        <img src="{{ Storage::url($u->tempat->foto_utama) }}" class="w-full h-full object-cover">
                    @else
                        {{ $u->tempat->kategori->jenis === 'restoran' ? '🍽️' : ($u->tempat->kategori->jenis === 'hotel' ? '🏨' : '🎨') }}
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <a href="{{ route('tempat.show', $u->tempat_id) }}" class="font-medium text-white text-sm hover:text-amber-400 transition-colors truncate block">{{ $u->tempat->nama_usaha }}</a>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-amber-400 text-xs">{{ str_repeat('⭐', (int)$u->rating) }}</span>
                        <span class="text-gray-500 text-xs">{{ $u->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <p class="text-3xl mb-2">📝</p>
                <p class="text-sm">Belum ada ulasan.<br><a href="{{ route('explore') }}" class="text-amber-400">Jelajahi & beri ulasan →</a></p>
            </div>
        @endforelse
    </div>

    {{-- Recent Favorites --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-white">Favorit Terbaru</h3>
            <a href="{{ route('dashboard.favorit') }}" class="text-amber-400 text-sm hover:text-amber-300">Lihat Semua →</a>
        </div>
        @forelse($recentFavorit as $fav)
            <div class="py-3 border-b border-dark-700 last:border-0 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-dark-700 flex items-center justify-center text-lg flex-shrink-0 overflow-hidden">
                    @if($fav->tempat->foto_utama)
                        <img src="{{ Storage::url($fav->tempat->foto_utama) }}" class="w-full h-full object-cover">
                    @else
                        {{ $fav->tempat->kategori->jenis === 'restoran' ? '🍽️' : ($fav->tempat->kategori->jenis === 'hotel' ? '🏨' : '🎨') }}
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <a href="{{ route('tempat.show', $fav->tempat_id) }}" class="font-medium text-white text-sm hover:text-amber-400 transition-colors truncate block">{{ $fav->tempat->nama_usaha }}</a>
                    <p class="text-gray-500 text-xs mt-0.5">{{ $fav->tempat->kecamatan->nama }}</p>
                </div>
                <form action="{{ route('dashboard.favorit.toggle', $fav->tempat_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm" title="Hapus Favorit">✕</button>
                </form>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <p class="text-3xl mb-2">❤️</p>
                <p class="text-sm">Belum ada favorit.<br><a href="{{ route('explore') }}" class="text-amber-400">Temukan tempat bagus →</a></p>
            </div>
        @endforelse
    </div>
</div>
@endsection
