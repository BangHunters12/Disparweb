@extends('layouts.public')
@section('title', 'Peta Wisata Bondowoso')
@section('meta-description', 'Peta interaktif destinasi wisata Kabupaten Bondowoso.')

@push('head-scripts')
{{-- Leaflet CSS wajib di <head> agar peta tampil benar --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    html, body { height: 100%; }
    #bondowisata-map {
        width: 100%;
        height: 100%;
        min-height: 500px;
        z-index: 1;
    }
    .leaflet-popup-content-wrapper {
        background: #1a1f2e !important;
        border: 1px solid #374151 !important;
        border-radius: 12px !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5) !important;
        color: #f1f5f9 !important;
        padding: 0 !important;
    }
    .leaflet-popup-content { margin: 0 !important; }
    .leaflet-popup-tip { background: #1a1f2e !important; }
    .leaflet-popup-close-button {
        color: #9ca3af !important;
        font-size: 20px !important;
        top: 8px !important;
        right: 10px !important;
    }
    .leaflet-control-zoom a {
        background: #1a1f2e !important;
        color: #f1f5f9 !important;
        border-color: #374151 !important;
    }
    .leaflet-control-zoom a:hover { background: #252a3a !important; }
    .leaflet-control-attribution { background: rgba(15,17,23,0.8) !important; color: #6b7280 !important; }
    .leaflet-control-attribution a { color: #9ca3af !important; }
    .marker-dot {
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.5);
    }
    .active-filter { background: rgba(245,158,11,0.2) !important; color: #fbbf24 !important; border-color: rgba(245,158,11,0.4) !important; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-black text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                Peta Wisata Bondowoso
            </h1>
            <p class="text-gray-400 text-sm mt-1">
                <span id="visibleCount">{{ count($tempat) }}</span> lokasi wisata ditampilkan
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <span class="badge badge-amber flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span>
                Restoran ({{ collect($tempat)->where('kategori','restoran')->count() }})
            </span>
            <span class="badge badge-blue flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-400 inline-block"></span>
                Hotel ({{ collect($tempat)->where('kategori','hotel')->count() }})
            </span>
            <span class="badge badge-green flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                Ekraf ({{ collect($tempat)->where('kategori','ekraf')->count() }})
            </span>
        </div>
    </div>

    {{-- Filter Buttons --}}
    <div class="flex gap-2 mb-3">
        <button onclick="filterMarkers('semua')" data-filter="semua"
                class="btn btn-secondary btn-sm active-filter">
            Semua
        </button>
        <button onclick="filterMarkers('restoran')" data-filter="restoran"
                class="btn btn-secondary btn-sm">
            Restoran
        </button>
        <button onclick="filterMarkers('hotel')" data-filter="hotel"
                class="btn btn-secondary btn-sm">
            Hotel
        </button>
        <button onclick="filterMarkers('ekraf')" data-filter="ekraf"
                class="btn btn-secondary btn-sm">
            Ekraf
        </button>
    </div>

    {{-- Map Container --}}
    <div class="card overflow-hidden" style="height: 65vh; min-height: 500px;">
        <div id="bondowisata-map" style="height:100%; width:100%;"></div>
    </div>
</div>

{{-- Data debug (hanya untuk development) --}}
@if(config('app.debug'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-4">
    <p class="text-xs text-gray-600">Debug: {{ count($tempat) }} lokasi dimuat ke peta</p>
</div>
@endif
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data dari Laravel
    const tempatData = @json($tempat);
    const colors = {
        restoran: '#f59e0b',
        hotel:    '#3b82f6',
        ekraf:    '#10b981'
    };

    // Cek apakah ada data
    if (!tempatData || tempatData.length === 0) {
        document.getElementById('bondowisata-map').innerHTML =
            '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9ca3af;flex-direction:column;gap:8px;">' +
            '<p style="font-size:16px;">Tidak ada lokasi yang memiliki koordinat.</p>' +
            '</div>';
        return;
    }

    // Inisialisasi peta — center di Bondowoso
    const map = L.map('bondowisata-map', {
        center: [-7.9092, 113.8224],
        zoom: 12,
        zoomControl: true,
    });

    // Tile layer OpenStreetMap (gratis, tanpa API key)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    // Custom marker
    function createMarkerIcon(color) {
        return L.divIcon({
            className: '',
            html: `<div class="marker-dot" style="
                width: 26px; height: 26px;
                background: ${color};
            "></div>`,
            iconSize: [26, 26],
            iconAnchor: [13, 26],
            popupAnchor: [0, -30],
        });
    }

    // Tambahkan semua marker
    let allMarkers = [];
    const bounds = [];

    tempatData.forEach(function(t) {
        if (!t.lat || !t.lng) return;

        const lat = parseFloat(t.lat);
        const lng = parseFloat(t.lng);
        if (isNaN(lat) || isNaN(lng)) return;

        const color = colors[t.kategori] || '#f59e0b';
        const marker = L.marker([lat, lng], {
            icon: createMarkerIcon(color)
        });

        // Popup konten
        const kategoriLabel = t.kategori.charAt(0).toUpperCase() + t.kategori.slice(1);
        marker.bindPopup(`
            <div style="padding: 14px; min-width: 200px; font-family: system-ui, -apple-system, sans-serif;">
                <p style="font-weight: 700; font-size: 14px; color: #f1f5f9; margin: 0 0 4px;">${t.nama}</p>
                <p style="font-size: 12px; color: #9ca3af; margin: 0 0 8px;">${t.alamat || 'Bondowoso'}</p>
                <div style="margin-bottom: 10px;">
                    <span style="
                        background: ${color}25;
                        color: ${color};
                        padding: 2px 8px;
                        border-radius: 999px;
                        font-size: 11px;
                        font-weight: 600;
                    ">${kategoriLabel}</span>
                </div>
                <a href="/tempat/${t.id}" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                    color: #f59e0b;
                    font-size: 12px;
                    font-weight: 600;
                    text-decoration: none;
                ">Lihat Detail &rarr;</a>
            </div>
        `, { maxWidth: 260 });

        marker._kategori = t.kategori;
        marker.addTo(map);
        allMarkers.push(marker);
        bounds.push([lat, lng]);
    });

    // Fit peta ke semua marker (kalau ada)
    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [30, 30], maxZoom: 14 });
    }

    // Update visible count
    function updateCount() {
        const visible = allMarkers.filter(m => map.hasLayer(m)).length;
        const el = document.getElementById('visibleCount');
        if (el) el.textContent = visible;
    }

    // Filter by kategori
    window.filterMarkers = function(filter) {
        allMarkers.forEach(function(m) {
            if (filter === 'semua' || m._kategori === filter) {
                m.addTo(map);
            } else {
                map.removeLayer(m);
            }
        });

        // Update active button
        document.querySelectorAll('[data-filter]').forEach(function(btn) {
            btn.classList.remove('active-filter');
            if (btn.dataset.filter === filter) btn.classList.add('active-filter');
        });

        updateCount();
    };

    updateCount();
});
</script>
@endpush
