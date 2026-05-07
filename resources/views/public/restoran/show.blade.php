@extends('layouts.public')
@section('title', $restoran->nama_usaha)
@section('meta-description', 'Detail restoran ' . $restoran->nama_usaha . ' di ' . $restoran->kecamatan?->nama . ', Bondowoso.')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">Beranda</a>
        <span>/</span>
        <a href="{{ route('restoran.index') }}" class="hover:text-amber-400 transition-colors">Restoran</a>
        <span>/</span>
        <span class="text-white truncate">{{ $restoran->nama_usaha }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left: Main Info --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Photo Gallery --}}
            <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl overflow-hidden">
                <div class="relative h-72 overflow-hidden">
                    <img id="main-photo" src="{{ $restoran->foto_utama_url }}" alt="{{ $restoran->nama_usaha }}"
                         class="w-full h-full object-cover"
                         onerror="this.src='https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&q=80'">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f1117]/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $restoran->is_buka ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ $restoran->is_buka ? '● Buka Sekarang' : '● Tutup' }}
                        </span>
                    </div>
                    @if($restoran->sumber === 'gmaps')
                    <div class="absolute top-4 right-4">
                        <span class="text-xs px-2 py-1 rounded-lg bg-blue-500/80 text-white font-semibold">Google Maps</span>
                    </div>
                    @elseif($restoran->sumber === 'dispar')
                    <div class="absolute top-4 right-4">
                        <span class="text-xs px-2 py-1 rounded-lg bg-emerald-500/80 text-white font-semibold">Dinas Pariwisata</span>
                    </div>
                    @endif
                </div>
                @if($restoran->foto_galeri && count($restoran->foto_galeri))
                <div class="flex gap-2 p-3 overflow-x-auto">
                    <img src="{{ $restoran->foto_utama_url }}" alt="Foto 1"
                         class="h-16 w-24 object-cover rounded-lg cursor-pointer border-2 border-amber-500 flex-shrink-0"
                         onclick="document.getElementById('main-photo').src=this.src">
                    @foreach($restoran->foto_galeri as $foto)
                    <img src="{{ asset('storage/'.$foto) }}" alt="Foto"
                         class="h-16 w-24 object-cover rounded-lg cursor-pointer border-2 border-transparent hover:border-amber-500 flex-shrink-0 transition-all"
                         onclick="document.getElementById('main-photo').src=this.src"
                         onerror="this.style.display='none'">
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Ulasan --}}
            <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-6">
                <h3 class="font-black text-white text-lg mb-4">Ulasan Pengunjung</h3>

                {{-- Sentiment bar --}}
                @if($sentimentSummary['total'] > 0)
                <div class="bg-[#0f1117] rounded-xl p-4 mb-5">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="text-center">
                            <p class="text-3xl font-black text-white">{{ number_format($restoran->avg_rating, 1) }}</p>
                            <div class="flex gap-0.5 mt-1">
                                @for($s=1; $s<=5; $s++)
                                <svg class="w-4 h-4 {{ $s <= $restoran->avg_rating ? 'fill-amber-400' : 'fill-gray-700' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                @endfor
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $sentimentSummary['total'] }} ulasan</p>
                        </div>
                        <div class="flex-1 space-y-1.5">
                            <div class="flex items-center gap-2"><span class="text-xs text-emerald-400 w-14">Positif</span><div class="flex-1 bg-[#2d3548] rounded-full h-2"><div class="bg-emerald-500 h-2 rounded-full" style="width:{{ $sentimentSummary['pct_positif'] }}%"></div></div><span class="text-xs text-gray-400 w-8">{{ $sentimentSummary['pct_positif'] }}%</span></div>
                            <div class="flex items-center gap-2"><span class="text-xs text-gray-400 w-14">Netral</span><div class="flex-1 bg-[#2d3548] rounded-full h-2"><div class="bg-gray-500 h-2 rounded-full" style="width:{{ $sentimentSummary['pct_netral'] }}%"></div></div><span class="text-xs text-gray-400 w-8">{{ $sentimentSummary['pct_netral'] }}%</span></div>
                            <div class="flex items-center gap-2"><span class="text-xs text-red-400 w-14">Negatif</span><div class="flex-1 bg-[#2d3548] rounded-full h-2"><div class="bg-red-500 h-2 rounded-full" style="width:{{ $sentimentSummary['pct_negatif'] }}%"></div></div><span class="text-xs text-gray-400 w-8">{{ $sentimentSummary['pct_negatif'] }}%</span></div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Daftar ulasan --}}
                @forelse($ulasan as $u)
                <div class="border-b border-[#2d3548] last:border-0 py-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#2d3548] flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($u->foto_reviewer)
                            <img src="{{ $u->foto_reviewer }}" alt="" class="w-full h-full object-cover">
                            @else
                            <span class="text-sm font-bold text-gray-400">{{ strtoupper(substr($u->nama_reviewer ?? 'A', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-semibold text-white text-sm">{{ $u->nama_reviewer ?? 'Anonim' }}</span>
                                <span class="text-xs px-1.5 py-0.5 rounded font-medium
                                    {{ $u->platform_sumber === 'gmaps' ? 'bg-blue-500/20 text-blue-400' : ($u->platform_sumber === 'dispar' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400') }}">
                                    {{ $u->platform_badge }}
                                </span>
                                @if($u->analisisSentimen)
                                <span class="text-xs px-1.5 py-0.5 rounded font-medium
                                    {{ $u->analisisSentimen->label_sentimen === 'positif' ? 'bg-emerald-500/10 text-emerald-400' : ($u->analisisSentimen->label_sentimen === 'negatif' ? 'bg-red-500/10 text-red-400' : 'bg-gray-500/10 text-gray-400') }}">
                                    {{ ucfirst($u->analisisSentimen->label_sentimen) }}
                                </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-1 mt-1">
                                @for($s=1; $s<=5; $s++)
                                <svg class="w-3 h-3 {{ $s <= $u->rating ? 'fill-amber-400' : 'fill-gray-700' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                @endfor
                                @if($u->tgl_kunjungan)
                                <span class="text-xs text-gray-600 ml-1">{{ $u->tgl_kunjungan->format('d M Y') }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-300 mt-2 leading-relaxed">{{ $u->teks_ulasan }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm text-center py-8">Belum ada ulasan. Jadilah yang pertama via aplikasi!</p>
                @endforelse

                @if($ulasan->hasPages())
                <div class="mt-4 text-center">
                    {{ $ulasan->links() }}
                </div>
                @endif

                {{-- CTA Tulis Ulasan --}}
                <div class="mt-5 bg-amber-500/10 border border-amber-500/20 rounded-xl p-4 text-center">
                    <p class="text-sm text-amber-300 font-semibold mb-1">Ingin tulis ulasan?</p>
                    <p class="text-xs text-gray-500 mb-3">Download aplikasi BondoWisata untuk menulis ulasan dan mendapatkan rekomendasi personal.</p>
                    <a href="#download-app" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0f1117] bg-amber-500 hover:bg-amber-400 px-4 py-2 rounded-lg transition-all">
                        Download Aplikasi
                    </a>
                </div>
            </div>

            {{-- Related --}}
            @if($related->isNotEmpty())
            <div>
                <h3 class="font-black text-white text-lg mb-4">Restoran Terdekat</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($related as $rel)
                    <a href="{{ route('restoran.show', $rel->slug) }}" class="group bg-[#1a1f2e] border border-[#2d3548] rounded-xl overflow-hidden hover:border-amber-500/30 transition-all">
                        <div class="h-28 overflow-hidden"><img src="{{ $rel->foto_utama_url }}" alt="{{ $rel->nama_usaha }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=400&q=80'"></div>
                        <div class="p-3"><p class="font-semibold text-white text-sm truncate group-hover:text-amber-400 transition-colors">{{ $rel->nama_usaha }}</p><p class="text-xs text-gray-500 mt-0.5">⭐ {{ number_format($rel->avg_rating,1) }}</p></div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Right: Info Card --}}
        <div class="space-y-4">
            {{-- Main Info --}}
            <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
                <h1 class="text-2xl font-black text-white leading-tight mb-2">{{ $restoran->nama_usaha }}</h1>
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex items-center gap-1">
                        @for($s=1;$s<=5;$s++)<svg class="w-4 h-4 {{ $s <= $restoran->avg_rating ? 'fill-amber-400' : 'fill-gray-700' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>@endfor
                    </div>
                    <span class="text-amber-400 font-bold text-sm">{{ number_format($restoran->avg_rating,1) }}</span>
                    <span class="text-gray-500 text-sm">({{ $restoran->total_ulasan }} ulasan)</span>
                </div>

                {{-- SAW Score --}}
                @if($restoran->skor_saw)
                <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-3 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400">Skor Rekomendasi</p>
                            <p class="text-2xl font-black text-amber-400">{{ number_format($restoran->skor_saw, 3) }}</p>
                        </div>
                        <svg class="w-10 h-10 text-amber-400/30" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Berdasarkan rating, sentimen ulasan, dan popularitas</p>
                </div>
                @endif

                <div class="space-y-3 text-sm">
                    @if($restoran->alamat)
                    <div class="flex gap-2.5"><svg class="w-4 h-4 text-gray-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg><span class="text-gray-300">{{ $restoran->alamat }}, {{ $restoran->kecamatan?->nama }}, Bondowoso</span></div>
                    @endif
                    @if($restoran->no_telepon)
                    <div class="flex gap-2.5"><svg class="w-4 h-4 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg><a href="tel:{{ $restoran->no_telepon }}" class="text-blue-400 hover:underline">{{ $restoran->no_telepon }}</a></div>
                    @endif
                    @if($restoran->website)
                    <div class="flex gap-2.5"><svg class="w-4 h-4 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg><a href="{{ $restoran->website }}" target="_blank" class="text-blue-400 hover:underline truncate">{{ $restoran->website }}</a></div>
                    @endif
                    @if($restoran->harga_min)
                    <div class="flex gap-2.5"><svg class="w-4 h-4 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="text-amber-400 font-semibold">{{ $restoran->harga_range_text }}</span></div>
                    @endif
                </div>

                @if($restoran->fasilitas && count($restoran->fasilitas))
                <div class="mt-4 pt-4 border-t border-[#2d3548]">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Fasilitas</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($restoran->fasilitas as $f)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-[#2d3548] text-gray-300">{{ $f }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($restoran->gmaps_url)
                <a href="{{ $restoran->gmaps_url }}" target="_blank"
                   class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 bg-blue-600/20 border border-blue-600/30 hover:bg-blue-600/30 text-blue-400 font-semibold text-sm rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    Buka di Google Maps
                </a>
                @endif
            </div>

            {{-- Map embed --}}
            @if($restoran->latitude && $restoran->longitude)
            <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl overflow-hidden">
                <iframe
                    src="https://www.google.com/maps/embed/v1/place?key={{ config('google.maps_key') }}&q={{ $restoran->latitude }},{{ $restoran->longitude }}&zoom=16"
                    class="w-full h-48" style="border:0" allowfullscreen loading="lazy">
                </iframe>
            </div>
            @endif

            {{-- Jam Buka --}}
            @if($restoran->jam_buka && count($restoran->jam_buka))
            <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
                <h3 class="font-bold text-white text-sm mb-3">Jam Operasional</h3>
                <div class="space-y-2">
                    @foreach($restoran->jam_buka as $j)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 capitalize">{{ $j['hari'] ?? '' }}</span>
                        <span class="{{ isset($j['tutup_total']) && $j['tutup_total'] ? 'text-red-400' : 'text-white' }}">
                            {{ isset($j['tutup_total']) && $j['tutup_total'] ? 'Tutup' : (($j['buka'] ?? '') . ' – ' . ($j['tutup'] ?? '')) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
