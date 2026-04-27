@extends('layouts.public')
@section('title', 'Peta Wisata Bondowoso')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-white">🗺️ Peta Wisata Bondowoso</h1>
            <p class="text-gray-400 text-sm mt-1">{{ count($tempat) }} lokasi wisata ditampilkan</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach([['restoran','🍽️','amber','Restoran'],['hotel','🏨','blue','Hotel'],['ekraf','🎨','green','Ekraf']] as [$jenis,$icon,$color,$label])
                <span class="badge badge-{{ $jenis === 'restoran' ? 'amber' : ($jenis === 'hotel' ? 'blue' : 'green') }}">{{ $icon }} {{ $label }}</span>
            @endforeach
        </div>
    </div>

    <div class="card overflow-hidden" style="height: 70vh;">
        <div id="bondowisata-map" class="w-full h-full"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const tempatData = @json($tempat);
const colors = { restoran: '#f59e0b', hotel: '#3b82f6', ekraf: '#10b981' };

function initMap() {
    const center = { lat: -7.9092, lng: 113.8224 };
    const map = new google.maps.Map(document.getElementById('bondowisata-map'), {
        center, zoom: 12,
        styles: [
            { elementType: 'geometry', stylers: [{ color: '#1a1f2e' }] },
            { elementType: 'labels.text.stroke', stylers: [{ color: '#0f1117' }] },
            { elementType: 'labels.text.fill', stylers: [{ color: '#9ca3af' }] },
            { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#252a3a' }] },
            { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#0f1117' }] },
        ],
        mapTypeControl: false, streetViewControl: false,
    });

    const infoWindow = new google.maps.InfoWindow();

    tempatData.forEach(t => {
        const marker = new google.maps.Marker({
            position: { lat: t.lat, lng: t.lng },
            map,
            title: t.nama,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 10,
                fillColor: colors[t.kategori] || '#f59e0b',
                fillOpacity: 0.9,
                strokeColor: '#fff',
                strokeWeight: 2,
            },
        });

        marker.addListener('click', () => {
            infoWindow.setContent(`
                <div style="background:#1a1f2e;color:#f1f5f9;padding:12px;border-radius:12px;min-width:180px;font-family:'Plus Jakarta Sans',sans-serif;">
                    <p style="font-weight:700;font-size:14px;margin:0 0 4px">${t.nama}</p>
                    <p style="font-size:12px;color:#9ca3af;margin:0 0 8px">${t.alamat || ''}</p>
                    <a href="/tempat/${t.id}" style="color:#f59e0b;font-size:12px;font-weight:600;">Lihat Detail →</a>
                </div>
            `);
            infoWindow.open(map, marker);
        });
    });
}

window.addEventListener('load', () => { if (typeof google !== 'undefined') initMap(); });
</script>
@endpush
