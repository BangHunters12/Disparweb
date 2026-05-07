@extends('layouts.public')
@section('title', 'Peta Kuliner Bondowoso')

@push('head-scripts')
<style>
#map { width:100%; height:calc(100vh - 64px); }
.gm-info-window { background:#1a1f2e!important; }
</style>
@endpush

@section('content')
<div id="map"></div>

{{-- Filter chips --}}
<div class="fixed top-20 left-1/2 -translate-x-1/2 z-10 flex items-center gap-2 flex-wrap justify-center px-4">
    <button onclick="filterKecamatan(null)" id="chip-all"
            class="px-3 py-1.5 rounded-full bg-amber-500 text-[#0f1117] text-xs font-bold shadow-lg transition-all">
        Semua
    </button>
    @foreach($kecamatanList as $kec)
    <button onclick="filterKecamatan('{{ $kec->id }}')" id="chip-{{ $kec->id }}"
            class="px-3 py-1.5 rounded-full bg-[#1a1f2e]/90 border border-[#2d3548] text-white text-xs font-semibold shadow-lg hover:border-amber-500/50 transition-all backdrop-blur">
        {{ $kec->nama }}
    </button>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
const restoranData = @json($restoranJson);

let map, markers = [], infoWindow;

async function initMap() {
    const { Map, InfoWindow } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");

    map = new Map(document.getElementById('map'), {
        center: { lat: -7.9117, lng: 113.8231 },
        zoom: 12,
        mapId: 'bondowisata_map',
        styles: [
            {elementType:'geometry',stylers:[{color:'#1a1f2e'}]},
            {elementType:'labels.text.fill',stylers:[{color:'#94a3b8'}]},
            {elementType:'labels.text.stroke',stylers:[{color:'#0f1117'}]},
            {featureType:'road',elementType:'geometry',stylers:[{color:'#2d3548'}]},
            {featureType:'water',elementType:'geometry',stylers:[{color:'#0f1117'}]},
        ]
    });

    infoWindow = new InfoWindow();

    restoranData.forEach(r => {
        if (!r.lat || !r.lng) return;

        const pin = new PinElement({
            background: '#f59e0b',
            borderColor: '#0f1117',
            glyphColor: '#0f1117',
        });

        const marker = new AdvancedMarkerElement({
            position: { lat: r.lat, lng: r.lng },
            map,
            title: r.nama,
            content: pin.element,
        });
        marker.kecamatanId = r.kecamatan_id;

        marker.addListener('click', () => {
            infoWindow.setContent(`
                <div style="background:#1a1f2e;color:#f1f5f9;padding:12px;border-radius:12px;max-width:220px;font-family:sans-serif">
                    <img src="${r.foto}" style="width:100%;height:100px;object-fit:cover;border-radius:8px;margin-bottom:8px"
                         onerror="this.src='https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=400&q=80'">
                    <p style="font-weight:800;font-size:14px;margin:0 0 4px">${r.nama}</p>
                    <p style="font-size:11px;color:#94a3b8;margin:0 0 4px">⭐ ${r.rating.toFixed(1)} · ${r.harga}</p>
                    <a href="/restoran/${r.slug}"
                       style="display:inline-block;margin-top:8px;padding:6px 12px;background:#f59e0b;color:#0f1117;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none">
                        Lihat Detail →
                    </a>
                </div>
            `);
            infoWindow.open(map, marker);
        });

        markers.push(marker);
    });
}

function filterKecamatan(kecamatanId) {
    document.querySelectorAll('[id^="chip-"]').forEach(el => {
        el.className = el.className.replace('bg-amber-500 text-[#0f1117]', 'bg-[#1a1f2e]/90 border border-[#2d3548] text-white');
    });
    const activeChip = document.getElementById(kecamatanId ? `chip-${kecamatanId}` : 'chip-all');
    if (activeChip) activeChip.className = activeChip.className.replace('bg-[#1a1f2e]/90 border border-[#2d3548] text-white', 'bg-amber-500 text-[#0f1117]');

    markers.forEach(m => {
        m.map = (!kecamatanId || m.kecamatanId === kecamatanId) ? map : null;
    });
}

// Load Maps dengan loading=async (Google recommended pattern)
(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
({key: "{{ $mapsKey }}", v: "weekly", language: "id"});

initMap();
</script>
@endpush

