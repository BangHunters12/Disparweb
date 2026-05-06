@extends('layouts.dashboard')
@section('title', 'Analisis Sentimen')
@section('page-title', 'Analisis Sentimen')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    @php $total = array_sum($distribusi); @endphp
    <div class="card p-6 text-center">
        <div class="w-12 h-12 rounded-xl bg-emerald-500/15 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-3xl font-black text-emerald-400">{{ $distribusi['positif'] }}</p>
        <p class="text-sm text-gray-400 mt-1">Positif</p>
        @if($total > 0)<p class="text-xs text-gray-500 mt-1">{{ round($distribusi['positif'] / $total * 100, 1) }}%</p>@endif
    </div>
    <div class="card p-6 text-center">
        <div class="w-12 h-12 rounded-xl bg-amber-500/15 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-3xl font-black text-amber-400">{{ $distribusi['netral'] }}</p>
        <p class="text-sm text-gray-400 mt-1">Netral</p>
        @if($total > 0)<p class="text-xs text-gray-500 mt-1">{{ round($distribusi['netral'] / $total * 100, 1) }}%</p>@endif
    </div>
    <div class="card p-6 text-center">
        <div class="w-12 h-12 rounded-xl bg-red-500/15 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-3xl font-black text-red-400">{{ $distribusi['negatif'] }}</p>
        <p class="text-sm text-gray-400 mt-1">Negatif</p>
        @if($total > 0)<p class="text-xs text-gray-500 mt-1">{{ round($distribusi['negatif'] / $total * 100, 1) }}%</p>@endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Pie Chart --}}
    <div class="card p-6">
        <h3 class="font-bold text-white mb-4">Distribusi Sentimen</h3>
        <div class="admin-chart-frame admin-chart-frame-lg">
            <canvas id="pieChart"></canvas>
        </div>
    </div>

    {{-- Keyword Cloud --}}
    <div class="card p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white">Kata Kunci Populer</h3>
            <form action="{{ route('admin.sentimen.reanalyze-all') }}" method="POST" onsubmit="return confirm('Analisis ulang semua ulasan?')">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Analisis Ulang Semua
                </button>
            </form>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($allKeywords as $word => $count)
                @php $size = max(12, min(22, 12 + $count * 1.5)); @endphp
                <span class="badge badge-{{ $count > 5 ? 'green' : ($count > 2 ? 'amber' : 'gray') }}" style="font-size:{{ $size }}px">
                    {{ $word }} <span class="opacity-60 text-xs">({{ $count }})</span>
                </span>
            @endforeach
            @if(empty($allKeywords))
                <p class="text-gray-500 text-sm">Belum ada data kata kunci.</p>
            @endif
        </div>
    </div>
</div>

{{-- Reviews Table --}}
<div class="card overflow-hidden">
    <div class="p-5 border-b border-dark-700 flex items-center justify-between">
        <h3 class="font-bold text-white">Semua Ulasan</h3>
        <span class="text-gray-400 text-sm">{{ $ulasan->total() }} ulasan</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-dark-700">
                <tr>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Pengguna</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Tempat</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Rating</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Ulasan</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Sentimen</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ulasan as $u)
                    <tr class="border-b border-dark-700/50 hover:bg-dark-700/20 transition-colors">
                        <td class="py-3 px-4 text-gray-300 text-xs">{{ $u->user->nama_lengkap }}</td>
                        <td class="py-3 px-4 text-gray-300 text-xs max-w-[140px] truncate">{{ $u->tempat->nama_usaha }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <span class="text-amber-400 font-semibold text-xs">{{ $u->rating }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-400 text-xs max-w-[200px]">
                            <span class="line-clamp-2">{{ Str::limit($u->teks_ulasan, 80) }}</span>
                        </td>
                        <td class="py-3 px-4">
                            @if($u->analisisSentimen)
                                @php $label = $u->analisisSentimen->label_sentimen; @endphp
                                <div>
                                    <span class="badge {{ $label === 'positif' ? 'badge-green' : ($label === 'negatif' ? 'badge-red' : 'badge-gray') }} capitalize mb-1">{{ $label }}</span>
                                    <div class="text-xs text-gray-600">
                                        P:{{ number_format($u->analisisSentimen->skor_positif, 2) }}
                                        N:{{ number_format($u->analisisSentimen->skor_netral, 2) }}
                                        G:{{ number_format($u->analisisSentimen->skor_negatif, 2) }}
                                    </div>
                                </div>
                            @else
                                <span class="badge badge-gray">Pending</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <form action="{{ route('admin.sentimen.reanalyze', $u->id) }}" method="POST">
                                @csrf
                                <button type="submit" title="Analisis Ulang" class="btn btn-secondary btn-sm p-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-gray-500">Tidak ada ulasan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-dark-700">
        {{ $ulasan->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Positif', 'Netral', 'Negatif'],
        datasets: [{
            data: [{{ $distribusi['positif'] }}, {{ $distribusi['netral'] }}, {{ $distribusi['negatif'] }}],
            backgroundColor: ['rgba(16,185,129,0.8)', 'rgba(245,158,11,0.7)', 'rgba(239,68,68,0.8)'],
            borderColor: '#0f1117', borderWidth: 3,
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { color: '#9ca3af' } } } }
});
</script>
@endpush
