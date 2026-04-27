@extends('layouts.public')
@section('title', $tempat->nama_usaha)
@section('meta-description', Str::limit($tempat->deskripsi, 160))

@section('content')
@php
    $placeholders = [
        'restoran' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=80',
        'hotel'    => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80',
        'ekraf'    => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&q=80',
    ];
    $imgSrc = $tempat->foto_utama
        ? Storage::url($tempat->foto_utama)
        : ($placeholders[$tempat->kategori->jenis] ?? $placeholders['ekraf']);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">Beranda</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('explore') }}" class="hover:text-amber-400 transition-colors">Jelajahi</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-300 truncate max-w-xs">{{ $tempat->nama_usaha }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Hero Image --}}
            <div class="card overflow-hidden">
                <div class="h-72 md:h-96 bg-dark-700">
                    <img src="{{ $imgSrc }}" alt="{{ $tempat->nama_usaha }}"
                         class="w-full h-full object-cover"
                         onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=80'">
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="badge {{ $tempat->kategori->jenis === 'restoran' ? 'badge-amber' : ($tempat->kategori->jenis === 'hotel' ? 'badge-blue' : 'badge-green') }}">
                                    {{ ucfirst($tempat->kategori->jenis) }}
                                </span>
                                <span class="badge {{ $tempat->status === 'aktif' ? 'badge-green' : 'badge-red' }} capitalize">{{ $tempat->status }}</span>
                            </div>
                            <h1 class="text-2xl font-black text-white">{{ $tempat->nama_usaha }}</h1>
                        </div>
                        @auth
                            @php $isFav = auth()->user()->favorit()->where('tempat_id', $tempat->id)->exists(); @endphp
                            <form action="{{ route('dashboard.favorit.toggle', $tempat->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="{{ $isFav ? 'btn-danger' : 'btn-secondary' }} btn flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="{{ $isFav ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    {{ $isFav ? 'Hapus Favorit' : 'Simpan Favorit' }}
                                </button>
                            </form>
                        @endauth
                    </div>

                    <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-400">
                        @if($tempat->alamat)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                {{ $tempat->alamat }}, {{ $tempat->kecamatan->nama }}
                            </div>
                        @endif
                        @if($tempat->no_telepon)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $tempat->no_telepon }}
                            </div>
                        @endif
                        @if($tempat->harga_min)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Rp {{ number_format($tempat->harga_min, 0, ',', '.') }}
                                @if($tempat->harga_max) — Rp {{ number_format($tempat->harga_max, 0, ',', '.') }} @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Rating Summary --}}
            <div class="card p-6">
                <h3 class="font-bold text-white mb-4">Rating & Ulasan</h3>
                <div class="flex items-center gap-8">
                    <div class="text-center">
                        <p class="text-5xl font-black text-amber-400">{{ number_format($rataRating, 1) }}</p>
                        <div class="flex items-center justify-center gap-0.5 mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($rataRating) ? 'text-amber-400 fill-amber-400' : 'text-gray-600' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $totalUlasan }} ulasan</p>
                    </div>
                    <div class="flex-1 space-y-1.5">
                        @foreach($distribusiRating as $bintang => $jumlah)
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-gray-400 w-4 text-right">{{ $bintang }}</span>
                                <svg class="w-3 h-3 text-amber-400 fill-amber-400 flex-shrink-0" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <div class="flex-1 bg-dark-700 rounded-full h-1.5">
                                    <div class="bg-amber-400 h-1.5 rounded-full transition-all" style="width:{{ $totalUlasan > 0 ? ($jumlah / $totalUlasan * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-gray-500 w-5">{{ $jumlah }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            @if($tempat->deskripsi)
                <div class="card p-6">
                    <h3 class="font-bold text-white mb-3">Deskripsi</h3>
                    <p class="text-gray-300 leading-relaxed text-sm">{{ $tempat->deskripsi }}</p>
                </div>
            @endif

            {{-- Write Review Form --}}
            @auth
                <div class="card p-6">
                    <h3 class="font-bold text-white mb-4">Tulis Ulasan</h3>
                    <form action="{{ route('dashboard.ulasan.store') }}" method="POST" class="space-y-4" x-data="{ rating: 0 }">
                        @csrf
                        <input type="hidden" name="tempat_id" value="{{ $tempat->id }}">
                        <input type="hidden" name="rating" x-model="rating">

                        <div>
                            <label class="form-label">Rating *</label>
                            <div class="flex items-center gap-1 mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" @click="rating = {{ $i }}"
                                            class="transition-transform hover:scale-110">
                                        <svg class="w-8 h-8" :class="rating >= {{ $i }} ? 'text-amber-400 fill-amber-400' : 'text-gray-600'" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    </button>
                                @endfor
                                <span class="ml-2 text-sm text-gray-400" x-text="rating > 0 ? rating + ' bintang' : 'Pilih rating'"></span>
                            </div>
                            @error('rating') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">Ulasan *</label>
                            <textarea name="teks_ulasan" rows="3" placeholder="Ceritakan pengalamanmu di sini..." class="form-input resize-none @error('teks_ulasan') border-red-500 @enderror" required minlength="10">{{ old('teks_ulasan') }}</textarea>
                            @error('teks_ulasan') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">Tanggal Kunjungan</label>
                            <input type="date" name="tgl_kunjungan" value="{{ old('tgl_kunjungan') }}" max="{{ date('Y-m-d') }}" class="form-input">
                        </div>

                        <button type="submit" class="btn btn-primary" :disabled="rating === 0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            @else
                <div class="card p-6 text-center">
                    <svg class="w-10 h-10 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <p class="text-gray-400 text-sm mb-3">Login untuk memberikan ulasan</p>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login Sekarang</a>
                </div>
            @endauth

            {{-- Reviews List --}}
            <div class="card p-6">
                <h3 class="font-bold text-white mb-4">Ulasan Terbaru ({{ $totalUlasan }})</h3>
                <div class="space-y-4">
                    @forelse($tempat->ulasan as $u)
                        <div class="border-b border-dark-700 pb-4 last:border-0 last:pb-0">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-400 font-bold text-sm flex-shrink-0">
                                        {{ mb_substr($u->user->nama_lengkap ?? 'A', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white text-sm">{{ $u->user->nama_lengkap ?? 'Anonim' }}</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <div class="flex items-center gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 {{ $i <= $u->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-600' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                                @endfor
                                            </div>
                                            <span class="text-gray-500 text-xs">{{ $u->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($u->analisisSentimen)
                                    @php $label = $u->analisisSentimen->label_sentimen; @endphp
                                    <span class="badge {{ $label === 'positif' ? 'badge-green' : ($label === 'negatif' ? 'badge-red' : 'badge-gray') }} capitalize text-xs flex-shrink-0">{{ $label }}</span>
                                @endif
                            </div>
                            <p class="text-gray-300 text-sm mt-3 leading-relaxed">{{ $u->teks_ulasan }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-10 h-10 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <p class="text-gray-500 text-sm">Belum ada ulasan. Jadilah yang pertama!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            {{-- Map --}}
            @if($tempat->latitude && $tempat->longitude)
                <div class="card overflow-hidden">
                    <div class="h-52 bg-dark-700" id="detailMap"></div>
                    <div class="p-4 text-center">
                        <a href="https://maps.google.com/?q={{ $tempat->latitude }},{{ $tempat->longitude }}" target="_blank"
                           class="btn btn-secondary btn-sm w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Buka di Google Maps
                        </a>
                    </div>
                </div>
            @endif

            {{-- Info Card --}}
            <div class="card p-5">
                <h3 class="font-bold text-white mb-4 text-sm">Informasi Tempat</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-2">
                        <dt class="text-gray-500">Kategori</dt>
                        <dd class="text-gray-300 font-medium capitalize">{{ $tempat->kategori->nama }}</dd>
                    </div>
                    <div class="flex justify-between gap-2">
                        <dt class="text-gray-500">Kecamatan</dt>
                        <dd class="text-gray-300 font-medium">{{ $tempat->kecamatan->nama }}</dd>
                    </div>
                    @if($tempat->kode_dispar)
                        <div class="flex justify-between gap-2">
                            <dt class="text-gray-500">Kode Dispar</dt>
                            <dd class="text-gray-300 font-mono text-xs">{{ $tempat->kode_dispar }}</dd>
                        </div>
                    @endif
                    @if($tempat->tgl_daftar_dispar)
                        <div class="flex justify-between gap-2">
                            <dt class="text-gray-500">Terdaftar</dt>
                            <dd class="text-gray-300">{{ $tempat->tgl_daftar_dispar->format('d M Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Similar Places --}}
            @if($similar->isNotEmpty())
                <div class="card p-5">
                    <h3 class="font-bold text-white mb-4 text-sm">Tempat Serupa</h3>
                    <div class="space-y-3">
                        @foreach($similar as $s)
                            @php
                                $sImg = $s->foto_utama
                                    ? Storage::url($s->foto_utama)
                                    : ($placeholders[$s->kategori->jenis] ?? $placeholders['ekraf']);
                            @endphp
                            <a href="{{ route('tempat.show', $s->id) }}" class="flex items-center gap-3 group">
                                <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-dark-700">
                                    <img src="{{ $sImg }}" alt="{{ $s->nama_usaha }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=100&q=80'">
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-white group-hover:text-amber-400 transition-colors truncate">{{ $s->nama_usaha }}</p>
                                    <p class="text-xs text-gray-500">{{ $s->kecamatan->nama }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@if($tempat->latitude && $tempat->longitude)
@push('scripts')
<script>
function initDetailMap() {
    const map = new google.maps.Map(document.getElementById('detailMap'), {
        center: { lat: {{ $tempat->latitude }}, lng: {{ $tempat->longitude }} },
        zoom: 15,
        styles: [{"featureType":"all","elementType":"geometry","stylers":[{"color":"#1a1f2e"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#0f1117"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#252a3a"}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"color":"#9ca3af"}]}]
    });
    new google.maps.Marker({ position: { lat: {{ $tempat->latitude }}, lng: {{ $tempat->longitude }} }, map, title: "{{ $tempat->nama_usaha }}" });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initDetailMap" async defer></script>
@endpush
@endif
