@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('page-title', 'Overview Admin')

@section('content')
{{-- Stats Grid --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
        $statCards = [
            ['label' => 'Total Tempat', 'value' => $stats['total_tempat'], 'icon' => '🏪', 'color' => 'amber'],
            ['label' => 'Aktif', 'value' => $stats['total_aktif'], 'icon' => '✅', 'color' => 'green'],
            ['label' => 'Total Ulasan', 'value' => $stats['total_ulasan'], 'icon' => '💬', 'color' => 'blue'],
            ['label' => 'Total User', 'value' => $stats['total_users'], 'icon' => '👥', 'color' => 'purple'],
        ];
    @endphp
    @foreach($statCards as $card)
        <div class="stat-card">
            <div class="stat-icon bg-{{ $card['color'] === 'amber' ? 'amber' : ($card['color'] === 'green' ? 'emerald' : ($card['color'] === 'blue' ? 'blue' : 'purple')) }}-500/20 text-2xl">{{ $card['icon'] }}</div>
            <div>
                <p class="text-2xl font-black text-white">{{ number_format($card['value']) }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $card['label'] }}</p>
            </div>
        </div>
    @endforeach
</div>

{{-- Category Breakdown --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    @foreach([['Restoran', $stats['total_restoran'], '🍽️', 'amber'], ['Hotel', $stats['total_hotel'], '🏨', 'blue'], ['Ekraf', $stats['total_ekraf'], '🎨', 'green']] as [$label, $val, $icon, $color])
        <div class="card p-5 flex items-center gap-4">
            <span class="text-3xl">{{ $icon }}</span>
            <div>
                <p class="text-xl font-black text-white">{{ $val }}</p>
                <p class="text-xs text-gray-400">{{ $label }}</p>
            </div>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Sentiment Pie --}}
    <div class="card p-6">
        <h3 class="font-bold text-white mb-5">Distribusi Sentimen Ulasan</h3>
        @php $total = array_sum($sentimenDist); @endphp
        <canvas id="sentimenChart" height="200"></canvas>
        <div class="flex justify-center gap-6 mt-4 text-sm">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Positif ({{ $sentimenDist['positif'] }})</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span> Netral ({{ $sentimenDist['netral'] }})</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> Negatif ({{ $sentimenDist['negatif'] }})</span>
        </div>
    </div>

    {{-- Top SAW --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-white">Top 10 SAW Ranking</h3>
            <a href="{{ route('admin.saw.index') }}" class="text-amber-400 text-sm hover:text-amber-300">Kelola →</a>
        </div>
        <div class="space-y-2">
            @foreach($topSaw as $r)
                <div class="flex items-center gap-3 py-2 border-b border-dark-700 last:border-0">
                    <span class="w-6 h-6 rounded-lg bg-amber-500/20 text-amber-400 text-xs font-bold flex items-center justify-center flex-shrink-0">#{{ $r->peringkat }}</span>
                    <span class="flex-1 text-sm text-white truncate">{{ $r->tempat->nama_usaha }}</span>
                    <span class="text-xs font-mono text-amber-400">{{ number_format($r->skor_saw_final, 4) }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Recent Reviews --}}
<div class="card p-6 mt-6">
    <div class="flex items-center justify-between mb-5">
        <h3 class="font-bold text-white">Ulasan Terbaru</h3>
        <a href="{{ route('admin.sentimen.index') }}" class="text-amber-400 text-sm hover:text-amber-300">Kelola Sentimen →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-dark-700">
                    <th class="text-left py-2 px-3 text-gray-400 font-medium">User</th>
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
                        <td class="py-2.5 px-3 text-amber-400">{{ $u->rating }}⭐</td>
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
