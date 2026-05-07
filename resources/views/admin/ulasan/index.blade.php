@extends('layouts.admin')
@section('title', 'Manajemen Ulasan')
@section('page-title', 'Ulasan')

@section('content')
<form action="{{ route('admin.ulasan.index') }}" method="GET" class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-4 mb-5 flex flex-wrap gap-3 items-center">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari teks ulasan..."
           class="flex-1 min-w-40 px-3 py-2 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500 placeholder-gray-600">
    <select name="platform" class="px-3 py-2 bg-[#0f1117] border border-[#2d3548] text-gray-300 text-sm rounded-xl focus:outline-none focus:border-amber-500">
        <option value="">Semua Platform</option>
        <option value="app" {{ request('platform') == 'app' ? 'selected' : '' }}>Aplikasi</option>
        <option value="gmaps" {{ request('platform') == 'gmaps' ? 'selected' : '' }}>Google Maps</option>
        <option value="dispar" {{ request('platform') == 'dispar' ? 'selected' : '' }}>Dispar</option>
    </select>
    <select name="sentimen" class="px-3 py-2 bg-[#0f1117] border border-[#2d3548] text-gray-300 text-sm rounded-xl focus:outline-none focus:border-amber-500">
        <option value="">Semua Sentimen</option>
        <option value="positif" {{ request('sentimen') == 'positif' ? 'selected' : '' }}>Positif</option>
        <option value="netral" {{ request('sentimen') == 'netral' ? 'selected' : '' }}>Netral</option>
        <option value="negatif" {{ request('sentimen') == 'negatif' ? 'selected' : '' }}>Negatif</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-amber-500 text-[#0f1117] font-bold rounded-xl text-sm hover:bg-amber-400 transition-all">Filter</button>
</form>

<div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-[#2d3548]">
                    <th class="px-4 py-3 text-left text-xs text-gray-500 font-semibold">Restoran / Reviewer</th>
                    <th class="px-4 py-3 text-left text-xs text-gray-500 font-semibold hidden md:table-cell">Ulasan</th>
                    <th class="px-4 py-3 text-center text-xs text-gray-500 font-semibold">Platform</th>
                    <th class="px-4 py-3 text-center text-xs text-gray-500 font-semibold">Sentimen</th>
                    <th class="px-4 py-3 text-center text-xs text-gray-500 font-semibold">Tampil</th>
                    <th class="px-4 py-3 text-right text-xs text-gray-500 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#2d3548]">
                @forelse($ulasan as $u)
                <tr class="hover:bg-white/[0.02] transition-colors {{ !$u->is_visible ? 'opacity-50' : '' }}">
                    <td class="px-4 py-3">
                        <p class="font-semibold text-white text-xs">{{ $u->restoran?->nama_usaha }}</p>
                        <p class="text-gray-500 text-xs mt-0.5">{{ $u->nama_reviewer ?? 'Anonim' }} · {{ $u->rating }}★</p>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <p class="text-xs text-gray-400 line-clamp-2 max-w-xs">{{ $u->teks_ulasan }}</p>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $u->platform_sumber === 'gmaps' ? 'bg-blue-500/20 text-blue-400' : ($u->platform_sumber === 'dispar' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400') }}">
                            {{ $u->platform_badge }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($u->analisisSentimen)
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $u->analisisSentimen->label_sentimen === 'positif' ? 'bg-emerald-500/10 text-emerald-400' : ($u->analisisSentimen->label_sentimen === 'negatif' ? 'bg-red-500/10 text-red-400' : 'bg-gray-500/10 text-gray-400') }}">
                            {{ ucfirst($u->analisisSentimen->label_sentimen) }}
                        </span>
                        @else
                        <span class="text-xs text-gray-600">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <form method="POST" action="{{ route('admin.ulasan.toggle-visibility', $u->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs px-2 py-0.5 rounded-full font-medium transition-all
                                {{ $u->is_visible ? 'bg-emerald-500/10 text-emerald-400 hover:bg-red-500/10 hover:text-red-400' : 'bg-red-500/10 text-red-400 hover:bg-emerald-500/10 hover:text-emerald-400' }}">
                                {{ $u->is_visible ? 'Tampil' : 'Tersembunyi' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <form method="POST" action="{{ route('admin.ulasan.reanalyze', $u->id) }}">
                                @csrf
                                <button type="submit" title="Analisis ulang" class="p-1.5 text-gray-500 hover:text-amber-400 hover:bg-amber-500/10 rounded-lg transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.ulasan.destroy', $u->id) }}" onsubmit="return confirm('Hapus ulasan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-gray-500">Tidak ada ulasan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ulasan->hasPages())
    <div class="px-4 py-3 border-t border-[#2d3548]">{{ $ulasan->links() }}</div>
    @endif
</div>
@endsection
