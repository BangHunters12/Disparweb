@extends('layouts.admin')
@section('title', 'Konfigurasi SAW')
@section('page-title', 'SAW — Sistem Rekomendasi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Config Form --}}
    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
        <h3 class="font-bold text-white text-sm mb-1">Bobot Kriteria SAW</h3>
        <p class="text-xs text-gray-500 mb-5">Total bobot harus = 100%</p>

        <form method="POST" action="{{ route('admin.saw.update-weights') }}">
            @csrf
            @php
            $fields = [
                'bobot_rating'      => ['label'=>'Rating Pengunjung',  'color'=>'amber'],
                'bobot_sentimen'    => ['label'=>'Sentimen Publik',    'color'=>'emerald'],
                'bobot_harga'       => ['label'=>'Efisiensi Harga',    'color'=>'blue'],
                'bobot_popularitas' => ['label'=>'Popularitas',        'color'=>'purple'],
                'bobot_kebaruan'    => ['label'=>'Kebaruan Data',      'color'=>'orange'],
            ];
            @endphp

            <div class="space-y-4" id="sliders-container">
                @foreach($fields as $name => $meta)
                <div>
                    <div class="flex justify-between mb-1.5">
                        <label class="text-xs font-semibold text-gray-300">{{ $meta['label'] }}</label>
                        <span id="val-{{ $name }}" class="text-xs font-black text-amber-400">{{ number_format($config->$name, 0) }}%</span>
                    </div>
                    <input type="range" name="{{ $name }}" id="{{ $name }}"
                           value="{{ $config->$name }}" min="0" max="100" step="1"
                           class="w-full accent-amber-500"
                           oninput="updateSlider('{{ $name }}')">
                </div>
                @endforeach
            </div>

            <div class="mt-4 p-3 bg-[#0f1117] rounded-xl flex items-center justify-between">
                <span class="text-xs text-gray-400">Total Bobot:</span>
                <span id="total-bobot" class="text-sm font-black text-white">{{ number_format($config->bobot_rating + $config->bobot_sentimen + $config->bobot_harga + $config->bobot_popularitas + $config->bobot_kebaruan, 0) }}%</span>
            </div>

            @error('total')<p class="text-red-400 text-xs mt-2">{{ $message }}</p>@enderror

            <button type="submit" class="w-full mt-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-[#0f1117] font-black rounded-xl text-sm transition-all">
                Simpan Bobot
            </button>
        </form>

        <div class="mt-3 pt-3 border-t border-[#2d3548]">
            <form method="POST" action="{{ route('admin.saw.recalculate') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 bg-emerald-600/20 border border-emerald-500/30 text-emerald-400 font-bold rounded-xl text-sm hover:bg-emerald-600/30 transition-all">
                    🔄 Hitung Ulang SAW
                </button>
            </form>
            @if($lastCalc)
            <p class="text-xs text-gray-600 text-center mt-2">Terakhir dihitung: {{ $lastCalc->diffForHumans() }}</p>
            @endif
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.saw.export-pdf') }}" class="flex items-center justify-center gap-2 w-full py-2.5 bg-red-600/20 border border-red-500/30 text-red-400 font-semibold rounded-xl text-sm hover:bg-red-600/30 transition-all">
                📄 Export PDF
            </a>
        </div>
    </div>

    {{-- Results Table --}}
    <div class="lg:col-span-2 bg-[#1a1f2e] border border-[#2d3548] rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-[#2d3548]">
            <h3 class="font-bold text-white text-sm">Peringkat Restoran</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#2d3548]">
                        <th class="px-4 py-3 text-left text-xs text-gray-500 font-semibold">#</th>
                        <th class="px-4 py-3 text-left text-xs text-gray-500 font-semibold">Restoran</th>
                        <th class="px-4 py-3 text-center text-xs text-gray-500 font-semibold hidden md:table-cell">Rating</th>
                        <th class="px-4 py-3 text-center text-xs text-gray-500 font-semibold hidden md:table-cell">Sentimen</th>
                        <th class="px-4 py-3 text-center text-xs text-gray-500 font-semibold hidden lg:table-cell">Harga</th>
                        <th class="px-4 py-3 text-center text-xs text-gray-500 font-semibold hidden lg:table-cell">Populer</th>
                        <th class="px-4 py-3 text-center text-xs text-gray-500 font-semibold">SAW Final</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3548]">
                    @forelse($hasil as $h)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-4 py-3 text-center">
                            <span class="w-7 h-7 rounded-lg inline-flex items-center justify-center text-xs font-black
                                {{ $h->peringkat === 1 ? 'bg-amber-400 text-[#0f1117]' : ($h->peringkat === 2 ? 'bg-gray-300 text-[#0f1117]' : ($h->peringkat === 3 ? 'bg-orange-600 text-white' : 'bg-[#2d3548] text-gray-400')) }}">
                                {{ $h->peringkat }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-white text-sm">{{ $h->restoran?->nama_usaha }}</p>
                            <p class="text-xs text-gray-500">{{ $h->restoran?->kecamatan?->nama }}</p>
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-amber-400 font-mono hidden md:table-cell">{{ number_format($h->skor_rating, 4) }}</td>
                        <td class="px-4 py-3 text-center text-xs text-emerald-400 font-mono hidden md:table-cell">{{ number_format($h->skor_sentimen, 4) }}</td>
                        <td class="px-4 py-3 text-center text-xs text-blue-400 font-mono hidden lg:table-cell">{{ number_format($h->skor_harga, 4) }}</td>
                        <td class="px-4 py-3 text-center text-xs text-purple-400 font-mono hidden lg:table-cell">{{ number_format($h->skor_popularitas, 4) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-amber-400 font-black font-mono">{{ number_format($h->skor_saw_final, 4) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-10 text-gray-500 text-sm">Belum ada data SAW. Klik "Hitung Ulang SAW" untuk memulai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateSlider(name) {
    const val = document.getElementById(name).value;
    document.getElementById('val-' + name).textContent = val + '%';
    updateTotal();
}

function updateTotal() {
    const names = ['bobot_rating','bobot_sentimen','bobot_harga','bobot_popularitas','bobot_kebaruan'];
    const total = names.reduce((sum, n) => sum + parseInt(document.getElementById(n).value || 0), 0);
    const el = document.getElementById('total-bobot');
    el.textContent = total + '%';
    el.className = 'text-sm font-black ' + (total === 100 ? 'text-emerald-400' : 'text-red-400');
}
</script>
@endpush
