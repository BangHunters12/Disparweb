@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('page-title', 'Overview Admin')

@section('content')

{{-- Alert jika ada ulasan belum dianalisis --}}
@if($stats['belum_sentimen'] > 0)
<div class="flex items-center justify-between gap-3 bg-amber-500/10 border border-amber-500/30 rounded-xl px-4 py-3 mb-6 text-sm">
    <div class="flex items-center gap-2 text-amber-300">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span><strong>{{ $stats['belum_sentimen'] }} ulasan</strong> belum dianalisis sentimen.</span>
    </div>
    <a href="{{ route('admin.sentimen.index') }}" class="btn btn-sm bg-amber-500/20 text-amber-300 border border-amber-500/30 hover:bg-amber-500/30 flex-shrink-0">
        Analisis Sekarang
    </a>
</div>
@endif

{{-- Stats Grid --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon bg-amber-500/20">
            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-white">{{ number_format($stats['total_tempat']) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Tempat</p>
            <p class="text-xs text-emerald-400 mt-0.5">{{ $stats['total_aktif'] }} aktif</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-blue-500/20">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-white">{{ number_format($stats['total_ulasan']) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Ulasan</p>
            <p class="text-xs text-blue-400 mt-0.5">+{{ $stats['ulasan_hari_ini'] }} hari ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-purple-500/20">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-white">{{ number_format($stats['total_users']) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Pengguna</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-emerald-500/20">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-white">{{ number_format($stats['saw_total']) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Ranking SAW</p>
            <a href="{{ route('admin.saw.index') }}" class="text-xs text-emerald-400 mt-0.5 hover:underline">Kelola →</a>
        </div>
    </div>
</div>

{{-- Category Breakdown --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="card p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-500/15 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div class="flex-1">
            <p class="text-xl font-black text-white">{{ $stats['total_restoran'] }}</p>
            <p class="text-xs text-gray-400">Restoran & Kuliner</p>
            @php $pctR = $stats['total_tempat'] > 0 ? round($stats['total_restoran']/$stats['total_tempat']*100) : 0; @endphp
            <div class="mt-2 w-full bg-dark-700 rounded-full h-1">
                <div class="bg-amber-400 h-1 rounded-full" style="width:{{ $pctR }}%"></div>
            </div>
        </div>
        <span class="text-amber-400 font-bold text-sm">{{ $pctR }}%</span>
    </div>
    <div class="card p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div class="flex-1">
            <p class="text-xl font-black text-white">{{ $stats['total_hotel'] }}</p>
            <p class="text-xs text-gray-400">Hotel & Penginapan</p>
            @php $pctH = $stats['total_tempat'] > 0 ? round($stats['total_hotel']/$stats['total_tempat']*100) : 0; @endphp
            <div class="mt-2 w-full bg-dark-700 rounded-full h-1">
                <div class="bg-blue-400 h-1 rounded-full" style="width:{{ $pctH }}%"></div>
            </div>
        </div>
        <span class="text-blue-400 font-bold text-sm">{{ $pctH }}%</span>
    </div>
    <div class="card p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
        </div>
        <div class="flex-1">
            <p class="text-xl font-black text-white">{{ $stats['total_ekraf'] }}</p>
            <p class="text-xs text-gray-400">Ekonomi Kreatif</p>
            @php $pctE = $stats['total_tempat'] > 0 ? round($stats['total_ekraf']/$stats['total_tempat']*100) : 0; @endphp
            <div class="mt-2 w-full bg-dark-700 rounded-full h-1">
                <div class="bg-emerald-400 h-1 rounded-full" style="width:{{ $pctE }}%"></div>
            </div>
        </div>
        <span class="text-emerald-400 font-bold text-sm">{{ $pctE }}%</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Sentiment Doughnut --}}
    <div class="card p-6">
        <h3 class="font-bold text-white mb-1 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Distribusi Sentimen Ulasan
        </h3>
        <p class="text-xs text-gray-500 mb-4">{{ $sentimenDist['positif'] + $sentimenDist['netral'] + $sentimenDist['negatif'] }} ulasan dianalisis</p>
        @if(($sentimenDist['positif'] + $sentimenDist['netral'] + $sentimenDist['negatif']) > 0)
            <div class="relative" style="height:200px;">
                <canvas id="sentimenChart"></canvas>
            </div>
            <div class="flex justify-center gap-6 mt-4 text-sm text-gray-400">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span>
                    Positif ({{ $sentimenDist['positif'] }})
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>
                    Netral ({{ $sentimenDist['netral'] }})
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span>
                    Negatif ({{ $sentimenDist['negatif'] }})
                </span>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm">Belum ada data sentimen</p>
                <a href="{{ route('admin.sentimen.reanalyze-all') }}" class="text-amber-400 text-xs mt-1 hover:underline"
                   onclick="return confirm('Jalankan analisis sentimen untuk semua ulasan?')"
                   hx-boost="false">Jalankan Analisis Sekarang</a>
            </div>
        @endif
    </div>

    {{-- Ulasan Chart 7 hari --}}
    <div class="card p-6">
        <h3 class="font-bold text-white mb-1 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
            Ulasan 7 Hari Terakhir
        </h3>
        <p class="text-xs text-gray-500 mb-4">Aktivitas pengguna harian</p>
        <div style="height:200px;">
            <canvas id="ulasanChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Top SAW --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Top 10 SAW
            </h3>
            <a href="{{ route('admin.saw.index') }}" class="text-amber-400 text-xs hover:underline flex items-center gap-1">
                Kelola
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="space-y-1.5">
            @forelse($topSaw as $r)
                <div class="flex items-center gap-3 py-2 border-b border-dark-700 last:border-0">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black flex-shrink-0
                        {{ $r->peringkat === 1 ? 'bg-amber-400/20 text-amber-400' : ($r->peringkat === 2 ? 'bg-slate-400/20 text-slate-300' : ($r->peringkat === 3 ? 'bg-orange-600/20 text-orange-400' : 'bg-dark-700 text-gray-500')) }}">
                        {{ $r->peringkat }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-white truncate">{{ $r->tempat->nama_usaha }}</p>
                        <p class="text-xs text-gray-500">{{ $r->tempat->kategori->nama ?? '-' }}</p>
                    </div>
                    <span class="text-xs font-mono text-amber-400 flex-shrink-0">{{ number_format($r->skor_saw_final, 4) }}</span>
                </div>
            @empty
                <div class="text-center py-10">
                    <svg class="w-10 h-10 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <p class="text-gray-500 text-sm">Belum ada ranking SAW</p>
                    <a href="{{ route('admin.saw.index') }}" class="text-amber-400 text-xs mt-1 inline-block hover:underline">Hitung Sekarang →</a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Reviews --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Ulasan Terbaru
            </h3>
            <a href="{{ route('admin.sentimen.index') }}" class="text-blue-400 text-xs hover:underline flex items-center gap-1">
                Sentimen
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="space-y-3">
            @foreach($recentUlasan as $u)
                <div class="flex items-start gap-3 border-b border-dark-700 pb-3 last:border-0 last:pb-0">
                    <div class="w-8 h-8 rounded-full bg-blue-500/15 flex items-center justify-center text-blue-400 text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($u->user->nama_lengkap ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-xs text-white font-medium truncate">{{ $u->tempat->nama_usaha }}</p>
                            <div class="flex items-center gap-0.5 flex-shrink-0">
                                <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <span class="text-amber-400 text-xs font-bold">{{ $u->rating }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $u->teks_ulasan }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            @if($u->analisisSentimen)
                                @php $label = $u->analisisSentimen->label_sentimen; @endphp
                                <span class="badge text-[10px] py-0.5 {{ $label === 'positif' ? 'badge-green' : ($label === 'negatif' ? 'badge-red' : 'badge-gray') }} capitalize">{{ $label }}</span>
                            @else
                                <span class="badge badge-gray text-[10px] py-0.5">Pending</span>
                            @endif
                            <span class="text-gray-600 text-[10px]">{{ $u->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sentimen Donut
@if(($sentimenDist['positif'] + $sentimenDist['netral'] + $sentimenDist['negatif']) > 0)
new Chart(document.getElementById('sentimenChart'), {
    type: 'doughnut',
    data: {
        labels: ['Positif', 'Netral', 'Negatif'],
        datasets: [{
            data: [{{ $sentimenDist['positif'] }}, {{ $sentimenDist['netral'] }}, {{ $sentimenDist['negatif'] }}],
            backgroundColor: ['rgba(16,185,129,0.8)', 'rgba(245,158,11,0.8)', 'rgba(239,68,68,0.8)'],
            borderColor: ['#10b981', '#f59e0b', '#ef4444'],
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '70%',
    }
});
@endif

// Ulasan bar chart
new Chart(document.getElementById('ulasanChart'), {
    type: 'bar',
    data: {
        labels: {!! $ulasanChart->pluck('label')->toJson() !!},
        datasets: [{
            label: 'Ulasan',
            data: {!! $ulasanChart->pluck('count')->toJson() !!},
            backgroundColor: 'rgba(59,130,246,0.5)',
            borderColor: '#3b82f6',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#6b7280' } },
            y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#6b7280', stepSize: 1 }, beginAtZero: true }
        },
        plugins: { legend: { display: false } },
    }
});
</script>
@endpush
