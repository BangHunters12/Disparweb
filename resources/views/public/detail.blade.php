@extends('layouts.public')
@section('title', $tempat->nama_usaha . ' — BondoWisata')
@section('description', Str::limit($tempat->deskripsi, 160))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">Beranda</a>
        <span>/</span>
        <a href="{{ route('explore') }}" class="hover:text-amber-400 transition-colors">Jelajahi</a>
        <span>/</span>
        <span class="text-gray-300">{{ $tempat->nama_usaha }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Photo --}}
            <div class="card overflow-hidden">
                <div class="h-72 bg-dark-700 flex items-center justify-center overflow-hidden">
                    @if($tempat->foto_utama)
                        <img src="{{ Storage::url($tempat->foto_utama) }}" alt="{{ $tempat->nama_usaha }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-8xl">{{ $tempat->kategori->jenis === 'restoran' ? '🍽️' : ($tempat->kategori->jenis === 'hotel' ? '🏨' : '🎨') }}</div>
                    @endif
                </div>
            </div>

            {{-- Info --}}
            <div class="card p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="badge {{ $tempat->kategori->jenis === 'restoran' ? 'badge-amber' : ($tempat->kategori->jenis === 'hotel' ? 'badge-blue' : 'badge-green') }}">
                                {{ $tempat->kategori->nama }}
                            </span>
                            @if($tempat->sumber_dispar)
                                <span class="badge badge-gray">✓ Dispar Verified</span>
                            @endif
                            <span class="{{ $tempat->status === 'aktif' ? 'badge-green' : 'badge-red' }} badge">
                                {{ ucfirst($tempat->status) }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-black text-white">{{ $tempat->nama_usaha }}</h1>
                        <p class="text-gray-400 mt-1">📍 {{ $tempat->alamat }} — {{ $tempat->kecamatan->nama }}</p>
                    </div>
                    @auth
                        <form action="{{ route('dashboard.favorit.toggle', $tempat->id) }}" method="POST">
                            @csrf
                            @php $isFav = auth()->user()->favorit()->where('tempat_id', $tempat->id)->exists(); @endphp
                            <button type="submit" class="btn {{ $isFav ? 'btn-danger' : 'btn-secondary' }} btn-sm flex-shrink-0">
                                {{ $isFav ? '❤️ Hapus' : '🤍 Simpan' }}
                            </button>
                        </form>
                    @endauth
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-4 py-4 border-y border-dark-700 mb-6">
                    <div class="text-center">
                        <p class="text-2xl font-black {{ $rataRating >= 4 ? 'text-amber-400' : ($rataRating >= 3 ? 'text-yellow-400' : 'text-red-400') }}">
                            {{ $rataRating > 0 ? number_format($rataRating, 1) : '—' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">⭐ Rating</p>
                    </div>
                    <div class="text-center border-x border-dark-700">
                        <p class="text-2xl font-black text-blue-400">{{ $totalUlasan }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Ulasan</p>
                    </div>
                    <div class="text-center">
                        @if($tempat->harga_min)
                            <p class="text-2xl font-black text-emerald-400">{{ number_format($tempat->harga_min / 1000, 0) }}K</p>
                            <p class="text-xs text-gray-500 mt-0.5">Mulai dari</p>
                        @else
                            <p class="text-2xl font-black text-gray-500">—</p>
                            <p class="text-xs text-gray-500 mt-0.5">Harga</p>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                @if($tempat->deskripsi)
                    <p class="text-gray-300 leading-relaxed mb-6">{{ $tempat->deskripsi }}</p>
                @endif

                {{-- Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    @if($tempat->no_telepon)
                        <div class="flex items-center gap-2 text-gray-300">
                            <span class="text-amber-400">📞</span> {{ $tempat->no_telepon }}
                        </div>
                    @endif
                    @if($tempat->harga_min && $tempat->harga_max)
                        <div class="flex items-center gap-2 text-gray-300">
                            <span class="text-emerald-400">💰</span>
                            Rp {{ number_format($tempat->harga_min, 0, ',', '.') }} – Rp {{ number_format($tempat->harga_max, 0, ',', '.') }}
                        </div>
                    @endif
                    @if($tempat->kode_dispar)
                        <div class="flex items-center gap-2 text-gray-300">
                            <span class="text-blue-400">🔖</span> {{ $tempat->kode_dispar }}
                        </div>
                    @endif
                </div>

                {{-- Facilities --}}
                @if($tempat->fasilitas)
                    <div class="mt-5 pt-5 border-t border-dark-700">
                        <p class="text-sm font-semibold text-gray-300 mb-3">Fasilitas:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tempat->fasilitas as $f)
                                <span class="badge badge-gray capitalize">{{ str_replace('_', ' ', $f) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Reviews Section --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">Ulasan ({{ $totalUlasan }})</h2>
                    @auth
                        <button onclick="document.getElementById('form-ulasan').classList.toggle('hidden')" class="btn-primary btn-sm">+ Tulis Ulasan</button>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary btn-sm">Login untuk ulasan</a>
                    @endauth
                </div>

                {{-- Review Form --}}
                @auth
                <form id="form-ulasan" action="{{ route('dashboard.ulasan.store') }}" method="POST" class="hidden mb-6 bg-dark-700 rounded-xl p-5">
                    @csrf
                    <input type="hidden" name="tempat_id" value="{{ $tempat->id }}">
                    <div class="mb-4">
                        <label class="form-label">Rating</label>
                        <div class="flex gap-2" x-data="{ rating: 5 }">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" @click="rating = {{ $i }}" class="text-2xl transition-transform hover:scale-110" :class="rating >= {{ $i }} ? 'grayscale-0' : 'grayscale opacity-40'">⭐</button>
                            @endfor
                            <input type="hidden" name="rating" x-bind:value="rating">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Ulasan</label>
                        <textarea name="teks_ulasan" rows="4" placeholder="Tulis ulasan Anda..." class="form-input resize-none" required minlength="10"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Tanggal Kunjungan</label>
                        <input type="date" name="tgl_kunjungan" class="form-input" max="{{ date('Y-m-d') }}">
                    </div>
                    <button type="submit" class="btn-primary">Kirim Ulasan</button>
                </form>
                @endauth

                {{-- Reviews List --}}
                @forelse($tempat->ulasan as $ulasan)
                    <div class="py-5 border-b border-dark-700 last:border-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-amber-500/20 flex items-center justify-center text-amber-400 font-semibold text-sm flex-shrink-0">
                                    {{ mb_substr($ulasan->user->nama_lengkap, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-white text-sm">{{ $ulasan->user->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-500">{{ $ulasan->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="text-amber-400 text-sm">{{ str_repeat('⭐', (int)$ulasan->rating) }}</span>
                                @if($ulasan->analisisSentimen)
                                    @php
                                        $label = $ulasan->analisisSentimen->label_sentimen;
                                        $badgeClass = $label === 'positif' ? 'badge-green' : ($label === 'negatif' ? 'badge-red' : 'badge-gray');
                                    @endphp
                                    <span class="badge {{ $badgeClass }} capitalize">{{ $label }}</span>
                                @endif
                            </div>
                        </div>
                        @if($ulasan->teks_ulasan)
                            <p class="text-gray-300 text-sm mt-3 leading-relaxed">{{ $ulasan->teks_ulasan }}</p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-500">
                        <p class="text-3xl mb-2">💬</p>
                        <p>Belum ada ulasan. Jadilah yang pertama!</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-5">
            {{-- Map --}}
            @if($tempat->latitude && $tempat->longitude)
                <div class="card overflow-hidden">
                    <div id="map-detail" class="h-56"></div>
                    <div class="p-4 text-sm text-gray-400">
                        <p>{{ $tempat->alamat }}</p>
                        <a href="https://maps.google.com/?q={{ $tempat->latitude }},{{ $tempat->longitude }}" target="_blank"
                           class="text-amber-400 hover:text-amber-300 text-xs mt-1 inline-block">Buka di Google Maps →</a>
                    </div>
                </div>
            @endif

            {{-- Similar --}}
            @if($similar->count())
                <div class="card p-5">
                    <h3 class="font-semibold text-white mb-4">Tempat Serupa</h3>
                    <div class="space-y-3">
                        @foreach($similar as $s)
                            <a href="{{ route('tempat.show', $s->id) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-dark-700 transition-colors group">
                                <div class="w-10 h-10 rounded-lg bg-dark-600 flex items-center justify-center text-xl flex-shrink-0 overflow-hidden">
                                    @if($s->foto_utama)
                                        <img src="{{ Storage::url($s->foto_utama) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ $s->kategori->jenis === 'restoran' ? '🍽️' : ($s->kategori->jenis === 'hotel' ? '🏨' : '🎨') }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-white text-sm group-hover:text-amber-400 transition-colors truncate">{{ $s->nama_usaha }}</p>
                                    <p class="text-xs text-gray-500">{{ $s->kecamatan->nama }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection

@push('scripts')
@if($tempat->latitude && $tempat->longitude)
<script>
function initMap() {
    const pos = { lat: {{ $tempat->latitude }}, lng: {{ $tempat->longitude }} };
    const map = new google.maps.Map(document.getElementById('map-detail'), {
        center: pos, zoom: 15,
        styles: [{ elementType: 'geometry', stylers: [{ color: '#1a1f2e' }] },
                 { elementType: 'labels.text.stroke', stylers: [{ color: '#0f1117' }] },
                 { elementType: 'labels.text.fill', stylers: [{ color: '#9ca3af' }] }],
        mapTypeControl: false, streetViewControl: false,
    });
    new google.maps.Marker({ position: pos, map, title: '{{ addslashes($tempat->nama_usaha) }}' });
}
window.addEventListener('load', () => { if (typeof google !== 'undefined') initMap(); });
</script>
@endif
@endpush
