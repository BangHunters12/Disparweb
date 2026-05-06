@extends('layouts.dashboard')
@section('title', 'Rekomendasi SAW')
@section('page-title', 'Rekomendasi SAW')

@section('content')

{{-- Info Banner --}}
<div class="flex items-center gap-3 bg-amber-500/10 border border-amber-500/30 rounded-xl px-4 py-3 mb-6 text-sm text-amber-300">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>Metode <strong>SAW (Simple Additive Weighting)</strong> — Sesuaikan bobot lalu klik Hitung Ulang SAW. Total bobot harus <strong>100%</strong>.</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Weight Config --}}
    <div class="card p-6" x-data="sawBobot()">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Konfigurasi Bobot
            </h3>
        </div>
        @if($lastCalculated)
            <p class="text-xs text-gray-500 mb-4 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Terakhir dihitung: {{ $lastCalculated->diffForHumans() }}
            </p>
        @endif

        {{-- Form: slider pakai nilai 0.0–1.0 langsung agar sesuai validasi Laravel --}}
        <form action="{{ route('admin.saw.recalculate') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Rating --}}
            <div>
                <div class="flex justify-between text-sm mb-1.5">
                    <label class="text-gray-300 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        Rating
                    </label>
                    <span class="text-amber-400 font-bold" x-text="pctLabel(w.rating)"></span>
                </div>
                <input type="range" name="w_rating" min="0" max="1" step="0.05"
                       x-model.number="w.rating" @input="snap('rating')"
                       class="w-full accent-amber-500">
            </div>

            {{-- Sentimen --}}
            <div>
                <div class="flex justify-between text-sm mb-1.5">
                    <label class="text-gray-300 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Sentimen
                    </label>
                    <span class="text-emerald-400 font-bold" x-text="pctLabel(w.sentimen)"></span>
                </div>
                <input type="range" name="w_sentimen" min="0" max="1" step="0.05"
                       x-model.number="w.sentimen" @input="snap('sentimen')"
                       class="w-full accent-emerald-500">
            </div>

            {{-- Harga --}}
            <div>
                <div class="flex justify-between text-sm mb-1.5">
                    <label class="text-gray-300 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/></svg>
                        Harga (cost)
                    </label>
                    <span class="text-blue-400 font-bold" x-text="pctLabel(w.harga)"></span>
                </div>
                <input type="range" name="w_harga" min="0" max="1" step="0.05"
                       x-model.number="w.harga" @input="snap('harga')"
                       class="w-full accent-blue-500">
            </div>

            {{-- Popularitas --}}
            <div>
                <div class="flex justify-between text-sm mb-1.5">
                    <label class="text-gray-300 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                        Popularitas
                    </label>
                    <span class="text-purple-400 font-bold" x-text="pctLabel(w.popularitas)"></span>
                </div>
                <input type="range" name="w_popularitas" min="0" max="1" step="0.05"
                       x-model.number="w.popularitas" @input="snap('popularitas')"
                       class="w-full accent-purple-500">
            </div>

            {{-- Kebaruan --}}
            <div>
                <div class="flex justify-between text-sm mb-1.5">
                    <label class="text-gray-300 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Kebaruan
                    </label>
                    <span class="text-pink-400 font-bold" x-text="pctLabel(w.kebaruan)"></span>
                </div>
                <input type="range" name="w_kebaruan" min="0" max="1" step="0.05"
                       x-model.number="w.kebaruan" @input="snap('kebaruan')"
                       class="w-full accent-pink-500">
            </div>

            {{-- Total indikator --}}
            <div class="pt-3 border-t border-dark-700">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs text-gray-400">Total Bobot:</span>
                    <span class="text-sm font-black"
                          :class="isValid ? 'text-emerald-400' : 'text-red-400'"
                          x-text="pctLabel(total)"></span>
                </div>
                <div class="w-full bg-dark-700 rounded-full h-2 mb-3">
                    <div class="h-2 rounded-full transition-all"
                         :class="isValid ? 'bg-emerald-400' : 'bg-red-400'"
                         :style="`width:${Math.min(total*100,100)}%`"></div>
                </div>
                <p class="text-xs mb-3">
                    <span x-show="!isValid" class="text-red-400">Total harus tepat 100% — sekarang: <span x-text="pctLabel(total)"></span></span>
                    <span x-show="isValid" class="text-emerald-400">✓ Bobot valid, siap dihitung.</span>
                </p>
                <button type="submit" :disabled="!isValid"
                        class="btn btn-primary w-full justify-center disabled:opacity-40 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Hitung Ulang SAW
                </button>
            </div>
        </form>
    </div>

    {{-- Rankings --}}
    <div class="lg:col-span-2 card overflow-hidden">
        <div class="p-5 border-b border-dark-700 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Peringkat SAW Global
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ $rankings->total() }} tempat diranking</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-dark-700">
                    <tr>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium w-12">#</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">Tempat</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">Rating</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">Sentimen</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">Harga</th>
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">Skor SAW</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rankings as $r)
                        <tr class="border-b border-dark-700/50 hover:bg-dark-700/20 transition-colors">
                            <td class="py-3 px-4">
                                @if($r->peringkat <= 3)
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black
                                        {{ $r->peringkat === 1 ? 'bg-amber-400/20 text-amber-400' : ($r->peringkat === 2 ? 'bg-slate-400/20 text-slate-300' : 'bg-orange-600/20 text-orange-400') }}">
                                        {{ $r->peringkat }}
                                    </div>
                                @else
                                    <span class="text-gray-500 font-mono text-xs pl-2">{{ $r->peringkat }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-white text-sm truncate max-w-[150px]">{{ $r->tempat->nama_usaha }}</div>
                                <div class="text-xs text-gray-500">{{ $r->tempat->kecamatan->nama }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="w-20 bg-dark-700 rounded-full h-1.5 mb-1">
                                    <div class="bg-amber-400 h-1.5 rounded-full" style="width:{{ min($r->skor_rating * 100, 100) }}%"></div>
                                </div>
                                <span class="text-xs font-mono text-amber-400">{{ number_format($r->skor_rating, 4) }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="w-20 bg-dark-700 rounded-full h-1.5 mb-1">
                                    <div class="bg-emerald-400 h-1.5 rounded-full" style="width:{{ min($r->skor_sentimen * 100, 100) }}%"></div>
                                </div>
                                <span class="text-xs font-mono text-emerald-400">{{ number_format($r->skor_sentimen, 4) }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="w-20 bg-dark-700 rounded-full h-1.5 mb-1">
                                    <div class="bg-blue-400 h-1.5 rounded-full" style="width:{{ min($r->skor_harga * 100, 100) }}%"></div>
                                </div>
                                <span class="text-xs font-mono text-blue-400">{{ number_format($r->skor_harga, 4) }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-base font-black text-white">{{ number_format($r->skor_saw_final, 4) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <p class="text-gray-400 font-medium">Belum ada data SAW</p>
                                <p class="text-gray-600 text-sm mt-1">Klik "Hitung Ulang SAW" untuk memulai perhitungan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-dark-700">
            {{ $rankings->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>

{{-- SAW Explanation --}}
<div class="card p-6">
    <h3 class="font-bold text-white mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Cara Kerja Metode SAW
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
        <div class="bg-dark-700/50 rounded-xl p-4 text-center">
            <div class="w-8 h-8 bg-amber-500/20 rounded-lg flex items-center justify-center mx-auto mb-2 text-amber-400 font-black">1</div>
            <p class="text-white font-semibold mb-1">Bangun Matriks</p>
            <p class="text-gray-500 text-xs">Kumpulkan nilai mentah tiap kriteria untuk semua tempat</p>
        </div>
        <div class="bg-dark-700/50 rounded-xl p-4 text-center">
            <div class="w-8 h-8 bg-amber-500/20 rounded-lg flex items-center justify-center mx-auto mb-2 text-amber-400 font-black">2</div>
            <p class="text-white font-semibold mb-1">Normalisasi</p>
            <p class="text-gray-500 text-xs">Benefit: Xij/Max | Cost: Min/Xij → skala 0–1</p>
        </div>
        <div class="bg-dark-700/50 rounded-xl p-4 text-center">
            <div class="w-8 h-8 bg-amber-500/20 rounded-lg flex items-center justify-center mx-auto mb-2 text-amber-400 font-black">3</div>
            <p class="text-white font-semibold mb-1">Pembobotan</p>
            <p class="text-gray-500 text-xs">Vi = Σ(Wj × Rij) untuk setiap alternatif</p>
        </div>
        <div class="bg-dark-700/50 rounded-xl p-4 text-center">
            <div class="w-8 h-8 bg-amber-500/20 rounded-lg flex items-center justify-center mx-auto mb-2 text-amber-400 font-black">4</div>
            <p class="text-white font-semibold mb-1">Ranking</p>
            <p class="text-gray-500 text-xs">Urutkan dari skor tertinggi → rekomendasi terbaik</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function sawBobot() {
    return {
        // Nilai desimal 0.0–1.0 — langsung dikirim ke server tanpa konversi
        w: {
            rating:      {{ $weights['rating'] }},
            sentimen:    {{ $weights['sentimen'] }},
            harga:       {{ $weights['harga'] }},
            popularitas: {{ $weights['popularitas'] }},
            kebaruan:    {{ $weights['kebaruan'] }},
        },
        // Total harus ≈ 1.0
        get total() {
            const sum = Object.values(this.w).reduce((a, b) => a + parseFloat(b || 0), 0);
            return Math.round(sum * 1000) / 1000; // bulatkan 3 desimal untuk hindari floating point error
        },
        get isValid() {
            return Math.abs(this.total - 1.0) < 0.001;
        },
        // Tampilkan sebagai persen
        pctLabel(val) {
            return Math.round(parseFloat(val || 0) * 100) + '%';
        },
        // Snap ke 2 desimal agar tidak ada masalah floating point
        snap(key) {
            this.w[key] = Math.round(parseFloat(this.w[key]) * 20) / 20;
        },
    }
}
</script>
@endpush

