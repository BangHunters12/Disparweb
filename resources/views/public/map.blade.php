@extends('layouts.public')
@section('title', 'Peta Wisata Bondowoso')
@section('meta-description', 'Peta interaktif destinasi wisata Kabupaten Bondowoso — Restoran, Hotel, dan Ekonomi Kreatif.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                Peta Wisata Bondowoso
            </h1>
            <p class="text-gray-400 text-sm mt-1">{{ count($tempat) }} lokasi wisata ditampilkan</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <span class="badge badge-amber flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span>
                Restoran
            </span>
            <span class="badge badge-blue flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-400 inline-block"></span>
                Hotel
            </span>
            <span class="badge badge-green flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                Ekraf
            </span>
        </div>
    </div>

    <div class="card overflow-hidden" style="height: 72vh;">
        <div id="bondowisata-map" class="w-full h-full"></div>
    </div>

    {{-- Filter bar bawah --}}
    <div class="grid grid-cols-3 gap-3 mt-4" id="filterButtons">
        <button onclick="filterMarkers('semua')" class="btn btn-secondary justify-center text-sm active-filter" data-filter="semua">
            Semua ({{ count($tempat) }})
        </button>
        <button onclick="filterMarkers('restoran')" class="btn btn-secondary justify-center text-sm" data-filter="restoran">
            Restoran ({{ collect($tempat)->where('kategori','restoran')->count() }})
        </button>
        <button onclick="filterMarkers('hotel')" class="btn btn-secondary justify-center text-sm" data-filter="hotel">
            Hotel ({{ collect($tempat)->where('kategori','hotel')->count() }})
        </button>
    </div>
</div>
@endsection

@push('scripts')
{{-- Leaflet.js (open source, tidak perlu API key) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/WLEg=" crossorigin=""></script>

<style>
    #bondowisata-map { z-index: 1; }
    .leaflet-popup-content-wrapper {
        background: #1a1f2e !important;
        border: 1px solid #252a3a !important;
        border-radius: 12px !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5) !important;
        color: #f1f5f9 !important;
    }
    .leaflet-popup-tip { background: #1a1f2e !important; }
    .leaflet-popup-close-button { color: #9ca3af !important; font-size: 18px !important; }
    .leaflet-tile { filter: brightness(0.7) saturate(0.5) hue-rotate(180deg) invert(0.15); }
    .active-filter { background-color: rgba(245,158,11,0.15) !important; color: #fbbf24 !important; border-color: rgba(245,158,11,0.3) !important; }
</style>

<script>
const tempatData = @json($tempat);
const colors = { restoran: '#f59e0b', hotel: '#3b82f6', ekraf: '#10b981' };
let allMarkers = [];

// Init map Leaflet — center di Bondowoso
const map = L.map('bondowisata-map', { zoomControl: true }).setView([-7.9092, 113.8224], 12);

// Tile layer OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 19,
}).addTo(map);

// Custom marker icon
function createIcon(color) {
    return L.divIcon({
        className: '',
        html: `<div style="
            width: 28px; height: 28px;
            background: ${color};
            border: 3px solid white;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            box-shadow: 0 3px 10px rgba(0,0,0,0.4);
        "></div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 28],
        popupAnchor: [0, -30],
    });
}

// Tambah markers
tempatData.forEach(t => {
    if (!t.lat || !t.lng) return;

    const color = colors[t.kategori] || '#f59e0b';
    const marker = L.marker([t.lat, t.lng], { icon: createIcon(color) })
        .addTo(map)
        .bindPopup(`
            <div style="min-width:180px; font-family: system-ui, sans-serif;">
                <p style="font-weight:700; font-size:14px; margin:0 0 4px; color:#f1f5f9;">${t.nama}</p>
                <p style="font-size:11px; color:#9ca3af; margin:0 0 4px;">${t.alamat || ''}</p>
                <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                    <span style="
                        background: ${color}25;
                        color: ${color};
                        padding: 2px 8px;
                        border-radius: 999px;
                        font-size: 11px;
                        font-weight: 600;
                        text-transform: capitalize;
                    ">${t.kategori}</span>
                </div>
                <a href="/tempat/${t.id}" style="
                    display: inline-block;
                    color: #f59e0b;
                    font-size: 12px;
                    font-weight: 600;
                    text-decoration: none;
                ">Lihat Detail &rarr;</a>
            </div>
        `, { maxWidth: 240 });

    marker._kategori = t.kategori;
    allMarkers.push(marker);
});

// Filter function
function filterMarkers(filter) {
    allMarkers.forEach(m => {
        if (filter === 'semua' || m._kategori === filter) {
            m.addTo(map);
        } else {
            m.removeFrom(map);
        }
    });

    // Update tombol aktif
    document.querySelectorAll('[data-filter]').forEach(btn => {
        btn.classList.remove('active-filter');
        if (btn.dataset.filter === filter) btn.classList.add('active-filter');
    });
}
</script>
@endpush
