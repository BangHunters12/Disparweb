@extends('layouts.admin')
@section('title', 'Import Google Maps')
@section('page-title', 'Import dari Google Maps')
@section('breadcrumb') Admin / Restoran / Import GMaps @endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Search Panel --}}
    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
        <h3 class="font-bold text-white text-sm mb-4">Cari di Google Places</h3>
        <div class="space-y-3">
            <div>
                <label class="block text-xs text-gray-400 font-semibold mb-1.5">Kata Kunci Pencarian</label>
                <input type="text" id="keyword" placeholder="warung makan bondowoso"
                       class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500">
            </div>
            <p class="text-xs text-gray-500">Contoh: "restoran bondowoso", "warung soto bondowoso", "cafe kopi bondowoso"</p>
            <button id="btn-search" onclick="searchGmaps()"
                    class="w-full py-2.5 bg-amber-500 hover:bg-amber-400 text-[#0f1117] font-black rounded-xl text-sm transition-all flex items-center justify-center gap-2">
                <svg id="icon-search" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span id="btn-search-text">Cari di Google Maps</span>
            </button>
        </div>

        {{-- Recent logs --}}
        @if($recentLogs->isNotEmpty())
        <div class="mt-5 pt-5 border-t border-[#2d3548]">
            <p class="text-xs font-bold text-gray-500 uppercase mb-3">Riwayat Import</p>
            <div class="space-y-2">
                @foreach($recentLogs as $log)
                <div class="text-xs bg-[#0f1117] rounded-lg p-2.5">
                    <div class="flex justify-between text-gray-400">
                        <span>{{ $log->created_at->diffForHumans() }}</span>
                        <span class="text-emerald-400 font-bold">{{ $log->jumlah_berhasil }} berhasil</span>
                    </div>
                    @if($log->jumlah_gagal > 0)
                    <p class="text-red-400 mt-0.5">{{ $log->jumlah_gagal }} gagal</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Results Panel --}}
    <div class="lg:col-span-2">
        <div id="results-empty" class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-12 text-center">
            <div class="w-14 h-14 rounded-2xl bg-[#0f1117] border border-[#2d3548] flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            </div>
            <p class="text-gray-500 text-sm">Ketik kata kunci dan klik cari untuk melihat hasil dari Google Maps.</p>
        </div>

        <div id="results-container" class="hidden">
            <div class="flex items-center justify-between mb-3">
                <p id="results-count" class="text-sm text-gray-400"></p>
                <div class="flex gap-2">
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                        <input type="checkbox" id="select-all" class="w-4 h-4 accent-amber-500" onchange="toggleAll(this.checked)">
                        Pilih Semua
                    </label>
                    <button id="btn-import" onclick="importSelected()"
                            class="px-4 py-2 bg-emerald-600/20 border border-emerald-500/30 text-emerald-400 rounded-xl text-sm font-bold hover:bg-emerald-600/30 transition-all">
                        Import Terpilih
                    </button>
                </div>
            </div>
            <div id="results-list" class="space-y-3"></div>
        </div>

        {{-- Import Result --}}
        <div id="import-result" class="hidden mt-4 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-sm text-emerald-400"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let searchResults = [];

async function searchGmaps() {
    const keyword = document.getElementById('keyword').value.trim();
    if (!keyword) return;

    const btn = document.getElementById('btn-search');
    document.getElementById('btn-search-text').textContent = 'Mencari...';
    btn.disabled = true;

    try {
        const res = await fetch('{{ route('admin.restoran.import-gmaps.search') }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken},
            body: JSON.stringify({keyword})
        });
        const data = await res.json();

        if (!data.success) throw new Error(data.message);

        searchResults = data.data;
        renderResults(data.data, data.total);
    } catch (e) {
        alert('Error: ' + e.message);
    } finally {
        document.getElementById('btn-search-text').textContent = 'Cari di Google Maps';
        btn.disabled = false;
    }
}

function renderResults(data, total) {
    document.getElementById('results-empty').classList.add('hidden');
    document.getElementById('results-container').classList.remove('hidden');
    document.getElementById('results-count').textContent = `${total} tempat ditemukan`;

    const list = document.getElementById('results-list');
    list.innerHTML = data.map((r, i) => `
        <div class="bg-[#1a1f2e] border ${r.sudah_ada ? 'border-gray-700 opacity-60' : 'border-[#2d3548]'} rounded-2xl p-4 flex gap-4">
            <input type="checkbox" value="${r.place_id}" class="result-checkbox w-4 h-4 accent-amber-500 mt-1 flex-shrink-0" ${r.sudah_ada ? 'disabled' : ''}>
            <img src="${r.foto || 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=200&q=60'}"
                 alt="" class="w-20 h-20 object-cover rounded-xl flex-shrink-0"
                 onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=200&q=60'">
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <p class="font-bold text-white text-sm">${r.nama}</p>
                    ${r.sudah_ada ? '<span class="text-xs px-2 py-0.5 rounded-full bg-gray-700 text-gray-400 flex-shrink-0">Sudah Ada</span>' : ''}
                </div>
                <p class="text-xs text-gray-500 mt-0.5 truncate">${r.alamat}</p>
                <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                    <span class="text-amber-400 font-bold">⭐ ${r.rating}</span>
                    <span>${r.total_ulasan} ulasan</span>
                    ${r.price_level ? '<span>' + '💰'.repeat(r.price_level) + '</span>' : ''}
                </div>
            </div>
        </div>
    `).join('');
}

function toggleAll(checked) {
    document.querySelectorAll('.result-checkbox:not(:disabled)').forEach(cb => cb.checked = checked);
}

async function importSelected() {
    const selected = [...document.querySelectorAll('.result-checkbox:checked')].map(cb => cb.value);
    if (!selected.length) { alert('Pilih minimal satu tempat.'); return; }

    const btn = document.getElementById('btn-import');
    btn.textContent = `Mengimpor ${selected.length} tempat...`;
    btn.disabled = true;

    try {
        const res = await fetch('{{ route('admin.restoran.import-gmaps.import') }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken},
            body: JSON.stringify({place_ids: selected})
        });
        const data = await res.json();
        const resultEl = document.getElementById('import-result');
        resultEl.classList.remove('hidden');
        resultEl.innerHTML = `✅ ${data.message}`;
    } catch(e) {
        alert('Import gagal: ' + e.message);
    } finally {
        btn.textContent = 'Import Terpilih';
        btn.disabled = false;
    }
}

document.getElementById('keyword').addEventListener('keydown', e => {
    if (e.key === 'Enter') searchGmaps();
});
</script>
@endpush
