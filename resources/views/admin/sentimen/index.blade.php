@extends('layouts.admin')
@section('title', 'Analisis Sentimen')
@section('page-title', 'Analisis Sentimen')

@section('page-actions')
@if($belumDianalisis > 0)
<form method="POST" action="{{ route('admin.sentimen.analyze-all') }}">
    @csrf
    <button type="submit"
            onclick="return confirm('Analisis {{ $belumDianalisis }} ulasan yang belum diproses?')"
            class="flex items-center gap-2 px-4 py-2 bg-amber-500/20 border border-amber-500/30 text-amber-400 rounded-xl text-sm font-bold hover:bg-amber-500/30 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.347.347a3.001 3.001 0 00-.879 2.122V19a2 2 0 01-2 2H9a2 2 0 01-2-2v-.879a3.001 3.001 0 00-.879-2.122L5.07 14.9z"/></svg>
        Analisis {{ $belumDianalisis }} Ulasan
    </button>
</form>
@else
<span class="text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-3 py-2 rounded-xl">✅ Semua ulasan sudah dianalisis</span>
@endif
@endsection

@section('content')
{{-- Summary cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    @php $cards = [['label'=>'Total Dianalisis','value'=>number_format($total),'color'=>'blue'],['label'=>'Positif','value'=>$pctPositif.'%','color'=>'green'],['label'=>'Netral','value'=>$pctNetral.'%','color'=>'gray'],['label'=>'Negatif','value'=>$pctNegatif.'%','color'=>'red']]; @endphp
    @foreach($cards as $c)
    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-4">
        <p class="text-xs text-gray-500 font-medium mb-2">{{ $c['label'] }}</p>
        <p class="text-2xl font-black {{ $c['color'] === 'green' ? 'text-emerald-400' : ($c['color'] === 'red' ? 'text-red-400' : ($c['color'] === 'blue' ? 'text-blue-400' : 'text-gray-400')) }}">{{ $c['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
    {{-- Per restoran --}}
    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-[#2d3548]"><h3 class="font-bold text-white text-sm">Sentimen per Restoran</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead><tr class="border-b border-[#2d3548]">
                    <th class="px-4 py-2.5 text-left text-gray-500 font-semibold">Restoran</th>
                    <th class="px-4 py-2.5 text-center text-emerald-400 font-semibold">Pos</th>
                    <th class="px-4 py-2.5 text-center text-gray-400 font-semibold">Net</th>
                    <th class="px-4 py-2.5 text-center text-red-400 font-semibold">Neg</th>
                </tr></thead>
                <tbody class="divide-y divide-[#2d3548]">
                    @forelse($perRestoran as $r)
                    <tr class="hover:bg-white/[0.02]">
                        <td class="px-4 py-2.5 text-white font-semibold truncate max-w-36">{{ $r->nama_usaha }}</td>
                        <td class="px-4 py-2.5 text-center text-emerald-400 font-bold">{{ $r->positif }}</td>
                        <td class="px-4 py-2.5 text-center text-gray-400">{{ $r->netral }}</td>
                        <td class="px-4 py-2.5 text-center text-red-400">{{ $r->negatif }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-6 text-gray-500">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Keywords --}}
    <div class="space-y-5">
        <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
            <h3 class="font-bold text-white text-sm mb-3">Kata Kunci Positif</h3>
            <div class="flex flex-wrap gap-1.5">
                @foreach($posKeywords as $word => $count)
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    {{ $word }} <span class="opacity-60 font-normal">({{ $count }})</span>
                </span>
                @endforeach
                @if(empty($posKeywords)) <p class="text-gray-600 text-xs">Belum ada data.</p> @endif
            </div>
        </div>
        <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
            <h3 class="font-bold text-white text-sm mb-3">Kata Kunci Negatif</h3>
            <div class="flex flex-wrap gap-1.5">
                @foreach($negKeywords as $word => $count)
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                    {{ $word }} <span class="opacity-60 font-normal">({{ $count }})</span>
                </span>
                @endforeach
                @if(empty($negKeywords)) <p class="text-gray-600 text-xs">Belum ada data.</p> @endif
            </div>
        </div>
    </div>
</div>
@endsection
