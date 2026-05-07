@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('page-actions')
<a href="{{ route('admin.restoran.import-gmaps') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600/20 border border-blue-500/30 text-blue-400 rounded-xl text-sm font-semibold hover:bg-blue-600/30 transition-all">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
    Import GMaps
</a>
<form method="POST" action="{{ route('admin.saw.recalculate') }}" class="inline">
    @csrf
    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-amber-500/20 border border-amber-500/30 text-amber-400 rounded-xl text-sm font-semibold hover:bg-amber-500/30 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Hitung SAW
    </button>
</form>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    @php
    $stats = [
        ['label'=>'Total Restoran',  'value'=>number_format($totalRestoran),  'color'=>'amber',   'icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ['label'=>'Total Ulasan',    'value'=>number_format($totalUlasan),    'color'=>'blue',    'icon'=>'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
        ['label'=>'Avg Rating',      'value'=>number_format($avgRatingKota,1),'color'=>'yellow',  'icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
        ['label'=>'Views Hari Ini',  'value'=>number_format($totalViewsHariIni), 'color'=>'purple','icon'=>'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
        ['label'=>'Positif %',       'value'=>$sentimentPositif.'%',          'color'=>'green',   'icon'=>'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Peringkat #1',    'value'=>Str::limit($top1, 10),          'color'=>'orange',  'icon'=>'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
    ];
    $colorMap = ['amber'=>'text-amber-400 bg-amber-400/10','blue'=>'text-blue-400 bg-blue-400/10','yellow'=>'text-yellow-400 bg-yellow-400/10','purple'=>'text-purple-400 bg-purple-400/10','green'=>'text-emerald-400 bg-emerald-400/10','orange'=>'text-orange-400 bg-orange-400/10'];
    @endphp
    @foreach($stats as $s)
    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-4">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-500 font-medium">{{ $s['label'] }}</span>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $colorMap[$s['color']] }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/></svg>
            </div>
        </div>
        <p class="text-xl font-black text-white">{{ $s['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Ulasan per Bulan --}}
    <div class="lg:col-span-2 bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
        <h3 class="font-bold text-white text-sm mb-4">Ulasan Masuk (12 Bulan)</h3>
        <div class="h-52"><canvas id="chartUlasan"></canvas></div>
    </div>
    {{-- Distribusi Sentimen --}}
    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
        <h3 class="font-bold text-white text-sm mb-4">Distribusi Sentimen</h3>
        <div class="h-52 flex items-center justify-center"><canvas id="chartSentimen"></canvas></div>
    </div>
</div>

{{-- Top 10 SAW + Recent --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Top 10 SAW --}}
    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
        <h3 class="font-bold text-white text-sm mb-4">Top 10 Restoran (SAW)</h3>
        <div class="space-y-2">
            @foreach($top10Restoran as $i => $saw)
            <div class="flex items-center gap-3">
                <span class="w-5 text-xs text-gray-500 text-center font-bold">{{ $i+1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-white font-semibold truncate">{{ $saw->restoran?->nama_usaha }}</p>
                    <div class="h-1.5 bg-[#2d3548] rounded-full mt-1 overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width:{{ ($saw->skor_saw_final * 100) }}%"></div>
                    </div>
                </div>
                <span class="text-xs text-amber-400 font-bold w-12 text-right">{{ number_format($saw->skor_saw_final, 3) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Ulasan --}}
    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white text-sm">Ulasan Terbaru</h3>
            <a href="{{ route('admin.ulasan.index') }}" class="text-xs text-amber-400 hover:underline">Lihat semua</a>
        </div>
        <div class="space-y-3">
            @foreach($recentUlasan as $u)
            <div class="flex gap-3 items-start">
                <div class="w-7 h-7 rounded-full bg-[#2d3548] flex items-center justify-center flex-shrink-0 text-xs font-bold text-gray-400">
                    {{ strtoupper(substr($u->nama_reviewer ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ $u->restoran?->nama_usaha }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Str::limit($u->teks_ulasan, 50) }}</p>
                </div>
                <div class="text-xs text-amber-400 font-bold">{{ $u->rating }}★</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('scripts')
@php
$labels   = $ulasanPerBulan->keys()->map(fn($m) => \Carbon\Carbon::createFromFormat('Y-m',$m)->translatedFormat('M Y'))->toArray();
$values   = $ulasanPerBulan->values()->toArray();
$posSkor  = $distribusiSentimen['positif'] ?? 0;
$neuSkor  = $distribusiSentimen['netral'] ?? 0;
$negSkor  = $distribusiSentimen['negatif'] ?? 0;
@endphp
<script>
const chartDefaults = { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false } } };

// Ulasan per bulan
new Chart(document.getElementById('chartUlasan'), {
    type:'bar',
    data:{
        labels: @json($labels),
        datasets:[{ data:@json($values), backgroundColor:'rgba(245,158,11,0.7)', borderRadius:6, borderSkipped:false }]
    },
    options:{...chartDefaults, scales:{ x:{ticks:{color:'#6b7280',font:{size:10}},grid:{display:false}}, y:{ticks:{color:'#6b7280',font:{size:10}},grid:{color:'rgba(45,53,72,0.8)'}} }}
});

// Sentimen pie
new Chart(document.getElementById('chartSentimen'), {
    type:'doughnut',
    data:{
        labels:['Positif','Netral','Negatif'],
        datasets:[{ data:[{{ $posSkor }},{{ $neuSkor }},{{ $negSkor }}], backgroundColor:['rgba(16,185,129,0.8)','rgba(107,114,128,0.8)','rgba(239,68,68,0.8)'], borderWidth:0, hoverOffset:4 }]
    },
    options:{...chartDefaults, cutout:'70%', plugins:{legend:{display:true,position:'bottom',labels:{color:'#9ca3af',font:{size:11},padding:12}}}}
});
</script>
@endpush
