@extends('layouts.dashboard')
@section('title', 'Ulasan Saya')
@section('page-title', 'Ulasan Saya')

@section('content')
@if($ulasan->isEmpty())
    <div class="card p-16 text-center">
        <div class="text-6xl mb-4">📝</div>
        <h3 class="text-xl font-bold text-white mb-2">Belum ada ulasan</h3>
        <p class="text-gray-400 mb-6">Jelajahi tempat wisata dan bagikan pengalamanmu!</p>
        <a href="{{ route('explore') }}" class="btn-primary">Jelajahi Sekarang</a>
    </div>
@else
    <div class="space-y-4">
        @foreach($ulasan as $u)
            <div class="card p-5" x-data="{ editing: false }">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('tempat.show', $u->tempat_id) }}" class="font-bold text-white hover:text-amber-400 transition-colors">{{ $u->tempat->nama_usaha }}</a>
                        @if($u->analisisSentimen)
                            @php $label = $u->analisisSentimen->label_sentimen; @endphp
                            <span class="badge {{ $label === 'positif' ? 'badge-green' : ($label === 'negatif' ? 'badge-red' : 'badge-gray') }} capitalize">{{ $label }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button @click="editing = !editing" class="btn-secondary btn-sm">Edit</button>
                        <form action="{{ route('dashboard.ulasan.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-2">
                    <span class="text-amber-400">{{ str_repeat('⭐', (int)$u->rating) }}</span>
                    <span class="text-gray-500 text-xs">{{ $u->created_at->format('d M Y') }}</span>
                    @if($u->tgl_kunjungan)
                        <span class="text-gray-600 text-xs">Kunjungan: {{ $u->tgl_kunjungan->format('d M Y') }}</span>
                    @endif
                </div>

                <p class="text-gray-300 text-sm mt-3 leading-relaxed">{{ $u->teks_ulasan }}</p>

                {{-- Edit Form --}}
                <form x-show="editing" x-transition action="{{ route('dashboard.ulasan.update', $u->id) }}" method="POST" class="mt-4 pt-4 border-t border-dark-700 space-y-3">
                    @csrf @method('PUT')
                    <div class="flex gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="text-xl">⭐</button>
                        @endfor
                        <input type="hidden" name="rating" value="{{ $u->rating }}">
                    </div>
                    <textarea name="teks_ulasan" rows="3" class="form-input resize-none">{{ $u->teks_ulasan }}</textarea>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary btn-sm">Simpan</button>
                        <button type="button" @click="editing = false" class="btn-secondary btn-sm">Batal</button>
                    </div>
                </form>
            </div>
        @endforeach

        <div class="mt-4">{{ $ulasan->links('vendor.pagination.custom') }}</div>
    </div>
@endif
@endsection
