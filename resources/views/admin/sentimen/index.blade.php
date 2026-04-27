@extends('layouts.dashboard')
@section('title', 'Dashboard Sentimen')
@section('page-title', 'Analisis Sentimen')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    @php $total = array_sum($distribusi); @endphp
    @foreach([['positif','Positif','emerald','😊'],['netral','Netral','amber','😐'],['negatif','Negatif','red','😞']] as [$key,$label,$color,$icon])
        <div class="card p-6 text-center">
            <div class="text-3xl mb-2">{{ $icon }}</div>
            <p class="text-3xl font-black text-{{ $color }}-400">{{ $distribusi[$key] }}</p>
            <p class="text-sm text-gray-400 mt-1">{{ $label }}</p>
            @if($total > 0)
                <p class="text-xs text-gray-500 mt-1">{{ round($distribusi[$key] / $total * 100, 1) }}%</p>
            @endif
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Pie Chart --}}
    <div class="card p-6">
        <h3 class="font-bold text-white mb-4">Distribusi Sentimen</h3>
        <canvas id="pieChart" height="220"></canvas>
    </div>

    {{-- Keyword Cloud --}}
    <div class="card p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white">Kata Kunci Populer</h3>
            <form action="{{ route('admin.sentimen.reanalyze-all') }}" method="POST" onsubmit="return confirm('Analisis ulang semua ulasan?')">
                @csrf
                <button type="submit" class="btn-secondary btn-sm">🔄 Analisis Ulang Semua</button>
            </form>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($allKeywords as $word => $count)
                @php $size = max(12, min(24, 12 + $count * 1.5)); @endphp
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
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">User</th>
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
                        <td class="py-3 px-4 text-amber-400">{{ $u->rating }}⭐</td>
                        <td class="py-3 px-4 text-gray-400 text-xs max-w-[200px]">
                            <span class="line-clamp-2">{{ Str::limit($u->teks_ulasan, 80) }}</span>
                        </td>
                        <td class="py-3 px-4">
                            @if($u->analisisSentimen)
                                @php $label = $u->analisisSentimen->label_sentimen; @endphp
                                <div>
                                    <span class="badge {{ $label === 'positif' ? 'badge-green' : ($label === 'negatif' ? 'badge-red' : 'badge-gray') }} capitalize mb-1">{{ $label }}</span>
                                    <div class="text-xs text-gray-500">
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
                                <button type="submit" class="btn-secondary btn-sm">🔄</button>
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
