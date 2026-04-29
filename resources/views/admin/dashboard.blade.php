@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('page-title', 'Overview Admin')

@section('content')
{{-- Stats Grid --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <div class="stat-icon bg-amber-500/20">
            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-white">{{ number_format($stats['total_tempat']) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Tempat</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-emerald-500/20">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-white">{{ number_format($stats['total_aktif']) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Tempat Aktif</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-blue-500/20">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-white">{{ number_format($stats['total_ulasan']) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total Ulasan</p>
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
</div>

{{-- Category Breakdown --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="card p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-500/15 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <p class="text-xl font-black text-white">{{ $stats['total_restoran'] }}</p>
            <p class="text-xs text-gray-400">Restoran & Kuliner</p>
        </div>
    </div>
    <div class="card p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div>
            <p class="text-xl font-black text-white">{{ $stats['total_hotel'] }}</p>
            <p class="text-xs text-gray-400">Hotel & Penginapan</p>
        </div>
    </div>
    <div class="card p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
        </div>
        <div>
            <p class="text-xl font-black text-white">{{ $stats['total_ekraf'] }}</p>
            <p class="text-xs text-gray-400">Ekonomi Kreatif</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Sentiment Chart --}}
    <div class="card p-6">
        <h3 class="font-bold text-white mb-5">Distribusi Sentimen Ulasan</h3>
        <canvas id="sentimenChart" height="200"></canvas>
        <div class="flex justify-center gap-6 mt-4 text-sm text-gray-400">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Positif ({{ $sentimenDist['positif'] }})</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span> Netral ({{ $sentimenDist['netral'] }})</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> Negatif ({{ $sentimenDist['negatif'] }})</span>
        </div>
    </div>

    {{-- Top SAW --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-white">Top 10 SAW Ranking</h3>
            <a href="{{ route('admin.saw.index') }}" class="text-amber-400 text-sm hover:text-amber-300 flex items-center gap-1">
                Kelola
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="space-y-2">
            @forelse($topSaw as $r)
                <div class="flex items-center gap-3 py-2 border-b border-dark-700 last:border-0">
                    <span class="w-7 h-7 rounded-lg {{ $r->peringkat <= 3 ? 'bg-amber-500/20 text-amber-400' : 'bg-dark-700 text-gray-500' }} text-xs font-bold flex items-center justify-center flex-shrink-0">
                        {{ $r->peringkat }}
                    </span>
                    <span class="flex-1 text-sm text-white truncate">{{ $r->tempat->nama_usaha }}</span>
                    <span class="text-xs font-mono text-amber-400">{{ number_format($r->skor_saw_final, 4) }}</span>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center py-4">Belum ada data SAW.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Recent Reviews --}}
<div class="card p-6 mt-6">
    <div class="flex items-center justify-between mb-5">
        <h3 class="font-bold text-white">Ulasan Terbaru</h3>
        <a href="{{ route('admin.sentimen.index') }}" class="text-amber-400 text-sm hover:text-amber-300 flex items-center gap-1">
            Kelola Sentimen
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-dark-700">
                    <th class="text-left py-2 px-3 text-gray-400 font-medium">Pengguna</th>
                    <th class="text-left py-2 px-3 text-gray-400 font-medium">Tempat</th>
                    <th class="text-left py-2 px-3 text-gray-400 font-medium">Rating</th>
                    <th class="text-left py-2 px-3 text-gray-400 font-medium">Sentimen</th>
                    <th class="text-left py-2 px-3 text-gray-400 font-medium">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUlasan as $u)
                    <tr class="border-b border-dark-700/50 hover:bg-dark-700/30 transition-colors">
                        <td class="py-2.5 px-3 text-gray-300 truncate max-w-[120px]">{{ $u->user->nama_lengkap }}</td>
                        <td class="py-2.5 px-3 text-gray-300 truncate max-w-[150px]">{{ $u->tempat->nama_usaha }}</td>
                        <td class="py-2.5 px-3">
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <span class="text-amber-400 font-semibold">{{ $u->rating }}</span>
                            </div>
                        </td>
                        <td class="py-2.5 px-3">
                            @if($u->analisisSentimen)
                                @php $label = $u->analisisSentimen->label_sentimen; @endphp
                                <span class="badge {{ $label === 'positif' ? 'badge-green' : ($label === 'negatif' ? 'badge-red' : 'badge-gray') }} capitalize">{{ $label }}</span>
                            @else
                                <span class="badge badge-gray">Pending</span>
                            @endif
                        </td>
                        <td class="py-2.5 px-3 text-gray-500 text-xs">{{ $u->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '65%',
    }
});
</script>
@endpush
