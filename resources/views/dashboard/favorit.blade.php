@extends('layouts.dashboard')
@section('title', 'Favorit Saya')
@section('page-title', 'Favorit Saya')

@section('content')
@if($favorit->isEmpty())
    <div class="card p-16 text-center">
        <div class="text-6xl mb-4">❤️</div>
        <h3 class="text-xl font-bold text-white mb-2">Belum ada favorit</h3>
        <p class="text-gray-400 mb-6">Simpan tempat wisata yang kamu suka!</p>
        <a href="{{ route('explore') }}" class="btn-primary">Jelajahi Sekarang</a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($favorit as $fav)
            @php $t = $fav->tempat; @endphp
            <div class="card-hover overflow-hidden group">
                <a href="{{ route('tempat.show', $t->id) }}" class="block">
                    <div class="h-40 bg-dark-700 flex items-center justify-center text-4xl overflow-hidden">
                        @if($t->foto_utama)
                            <img src="{{ Storage::url($t->foto_utama) }}" alt="{{ $t->nama_usaha }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            {{ $t->kategori->jenis === 'restoran' ? '🍽️' : ($t->kategori->jenis === 'hotel' ? '🏨' : '🎨') }}
                        @endif
                    </div>
                    <div class="p-4">
                        <span class="badge {{ $t->kategori->jenis === 'restoran' ? 'badge-amber' : ($t->kategori->jenis === 'hotel' ? 'badge-blue' : 'badge-green') }} mb-2">{{ ucfirst($t->kategori->jenis) }}</span>
                        <h3 class="font-bold text-white group-hover:text-amber-400 transition-colors truncate">{{ $t->nama_usaha }}</h3>
                        <p class="text-gray-400 text-xs mt-1">📍 {{ $t->kecamatan->nama }}</p>
                    </div>
                </a>
                <div class="px-4 pb-4">
                    <form action="{{ route('dashboard.favorit.toggle', $t->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-danger btn-sm w-full justify-center">❤️ Hapus Favorit</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $favorit->links('vendor.pagination.custom') }}</div>
@endif
@endsection
