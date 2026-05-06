@extends('layouts.dashboard')
@section('title', 'Rekomendasi SAW')
@section('page-title', 'Rekomendasi SAW')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Weight Config --}}
    <div class="card p-6">
        <h3 class="font-bold text-white mb-4">⚙️ Konfigurasi Bobot</h3>
        @if($lastCalculated)
            <p class="text-xs text-gray-500 mb-4">Terakhir dihitung: {{ $lastCalculated->diffForHumans() }}</p>
        @endif
        <form action="{{ route('admin.saw.recalculate') }}" method="POST" class="space-y-4">
            @csrf
            @php
                $criteria = [
                    ['key'=>'rating',      'label'=>'Rating',       'pct'=>40],
                    ['key'=>'sentimen',    'label'=>'Sentimen',     'pct'=>25],
                    ['key'=>'harga',       'label'=>'Harga',        'pct'=>15],
                    ['key'=>'popularitas', 'label'=>'Popularitas',  'pct'=>10],
                    ['key'=>'kebaruan',    'label'=>'Kebaruan',     'pct'=>10],
                ];
            @endphp
            @foreach($criteria as $c)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <label class="text-gray-300">{{ $c['label'] }}</label>
                        <span class="text-amber-400 font-semibold" id="pct_{{ $c['key'] }}">{{ round($weights[$c['key']] * 100) }}%</span>
                    </div>
                    <input type="range" name="w_{{ $c['key'] }}" min="0" max="1" step="0.05"
                           value="{{ $weights[$c['key']] }}"
                           class="w-full accent-amber-500"
                           oninput="document.getElementById('pct_{{ $c['key'] }}').textContent = Math.round(this.value * 100) + '%'">
                </div>
            @endforeach
            <div class="pt-3 border-t border-dark-700">
                <p class="text-xs text-gray-500 mb-3">Total bobot harus = 100%. Slider menggunakan nilai terakhir yang tersimpan.</p>
                <button type="submit" class="btn-primary w-full justify-center">🔄 Hitung Ulang SAW</button>
            </div>
        </form>
    </div>

    {{-- Rankings --}}
    <div class="lg:col-span-2 card overflow-hidden">
        <div class="p-5 border-b border-dark-700">
            <h3 class="font-bold text-white">🏆 Peringkat SAW Global</h3>
            <p class="text-xs text-gray-500 mt-1">{{ $rankings->total() }} tempat diranking</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-dark-700">
                    <tr>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">#</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">Tempat</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">Rating</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">Sentimen</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">Harga</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">Final SAW</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rankings as $r)
                        <tr class="border-b border-dark-700/50 hover:bg-dark-700/20 transition-colors">
                            <td class="py-3 px-4">
                                <span class="w-7 h-7 rounded-lg {{ $r->peringkat <= 3 ? 'bg-amber-500/20 text-amber-400' : 'bg-dark-700 text-gray-400' }} text-xs font-bold flex items-center justify-center">
                                    {{ $r->peringkat <= 3 ? ['🥇','🥈','🥉'][$r->peringkat-1] : $r->peringkat }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-medium text-white text-sm truncate max-w-[160px]">{{ $r->tempat->nama_usaha }}</div>
                                <div class="text-xs text-gray-500">{{ $r->tempat->kecamatan->nama }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="w-full bg-dark-700 rounded-full h-1.5 mb-1"><div class="bg-amber-400 h-1.5 rounded-full" style="width:{{ $r->skor_rating * 100 }}%"></div></div>
                                <span class="text-xs font-mono text-amber-400">{{ number_format($r->skor_rating, 4) }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="w-full bg-dark-700 rounded-full h-1.5 mb-1"><div class="bg-emerald-400 h-1.5 rounded-full" style="width:{{ $r->skor_sentimen * 100 }}%"></div></div>
                                <span class="text-xs font-mono text-emerald-400">{{ number_format($r->skor_sentimen, 4) }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="w-full bg-dark-700 rounded-full h-1.5 mb-1"><div class="bg-blue-400 h-1.5 rounded-full" style="width:{{ $r->skor_harga * 100 }}%"></div></div>
                                <span class="text-xs font-mono text-blue-400">{{ number_format($r->skor_harga, 4) }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-lg font-black text-white">{{ number_format($r->skor_saw_final, 4) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-12 text-gray-500">
                            <p class="text-3xl mb-2">📊</p>
                            <p>Belum ada data SAW. Klik "Hitung Ulang SAW" untuk memulai.</p>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-dark-700">
            {{ $rankings->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
@endsection
