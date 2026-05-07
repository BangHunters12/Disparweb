@extends('layouts.admin')
@section('title', 'Manajemen Restoran')
@section('page-title', 'Restoran')
@section('breadcrumb') Admin / Restoran @endsection

@section('page-actions')
<a href="{{ route('admin.restoran.create') }}" class="flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-400 text-[#0f1117] font-bold rounded-xl text-sm transition-all">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah Manual
</a>
<a href="{{ route('admin.restoran.import-gmaps') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600/20 border border-blue-500/30 text-blue-400 rounded-xl text-sm font-semibold hover:bg-blue-600/30 transition-all">
    Import GMaps
</a>
@endsection

@section('content')
{{-- Filter bar --}}
<form action="{{ route('admin.restoran.index') }}" method="GET" class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-4 mb-5 flex flex-wrap gap-3 items-center">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama restoran..."
           class="flex-1 min-w-40 px-3 py-2 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl placeholder-gray-600 focus:outline-none focus:border-amber-500">
    <select name="kecamatan_id" class="px-3 py-2 bg-[#0f1117] border border-[#2d3548] text-gray-300 text-sm rounded-xl focus:outline-none focus:border-amber-500">
        <option value="">Semua Kecamatan</option>
        @foreach($kecamatanList as $k)
        <option value="{{ $k->id }}" {{ request('kecamatan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
        @endforeach
    </select>
    <select name="sumber" class="px-3 py-2 bg-[#0f1117] border border-[#2d3548] text-gray-300 text-sm rounded-xl focus:outline-none focus:border-amber-500">
        <option value="">Semua Sumber</option>
        <option value="manual" {{ request('sumber') == 'manual' ? 'selected' : '' }}>Manual</option>
        <option value="gmaps" {{ request('sumber') == 'gmaps' ? 'selected' : '' }}>Google Maps</option>
        <option value="dispar" {{ request('sumber') == 'dispar' ? 'selected' : '' }}>Dispar</option>
    </select>
    <select name="status" class="px-3 py-2 bg-[#0f1117] border border-[#2d3548] text-gray-300 text-sm rounded-xl focus:outline-none focus:border-amber-500">
        <option value="">Semua Status</option>
        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="tutup" {{ request('status') == 'tutup' ? 'selected' : '' }}>Tutup</option>
        <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-amber-500 text-[#0f1117] font-bold rounded-xl text-sm hover:bg-amber-400 transition-all">Filter</button>
    @if(request()->hasAny(['search','kecamatan_id','sumber','status']))
    <a href="{{ route('admin.restoran.index') }}" class="text-xs text-gray-500 hover:text-gray-300">Reset</a>
    @endif
</form>

{{-- Table --}}
<div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-[#2d3548]">
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-semibold uppercase tracking-wider">Restoran</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-semibold uppercase tracking-wider hidden md:table-cell">Kecamatan</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-semibold uppercase tracking-wider hidden lg:table-cell">Sumber</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-semibold uppercase tracking-wider">Status</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-semibold uppercase tracking-wider hidden lg:table-cell">Rating</th>
                    <th class="text-left px-4 py-3 text-xs text-gray-500 font-semibold uppercase tracking-wider hidden xl:table-cell">SAW</th>
                    <th class="text-right px-4 py-3 text-xs text-gray-500 font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#2d3548]">
                @forelse($restoran as $r)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $r->foto_utama_url }}" alt="" class="w-10 h-10 rounded-lg object-cover bg-[#2d3548]" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=100&q=60'">
                            <div>
                                <p class="font-semibold text-white">{{ $r->nama_usaha }}</p>
                                <p class="text-xs text-gray-500 truncate max-w-48">{{ $r->alamat }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-gray-400 text-xs">{{ $r->kecamatan?->nama }}</td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="text-xs px-2 py-0.5 rounded font-medium
                            {{ $r->sumber === 'gmaps' ? 'bg-blue-500/20 text-blue-400' : ($r->sumber === 'dispar' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-500/20 text-gray-400') }}">
                            {{ ucfirst($r->sumber) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                            {{ $r->status === 'aktif' ? 'bg-emerald-500/20 text-emerald-400' : ($r->status === 'tutup' ? 'bg-red-500/20 text-red-400' : 'bg-yellow-500/20 text-yellow-400') }}">
                            {{ ucfirst($r->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="text-amber-400 font-bold text-sm">{{ number_format($r->avg_rating, 1) }}★</span>
                        <span class="text-gray-600 text-xs ml-1">({{ $r->total_ulasan }})</span>
                    </td>
                    <td class="px-4 py-3 hidden xl:table-cell text-amber-400 font-mono text-xs">
                        {{ $r->rekomendasiSaw ? number_format($r->rekomendasiSaw->skor_saw_final, 4) : '—' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('restoran.show', $r->slug) }}" target="_blank"
                               class="p-1.5 text-gray-500 hover:text-blue-400 hover:bg-blue-500/10 rounded-lg transition-all" title="Lihat publik">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            <a href="{{ route('admin.restoran.edit', $r->id) }}"
                               class="p-1.5 text-gray-500 hover:text-amber-400 hover:bg-amber-500/10 rounded-lg transition-all" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.restoran.destroy', $r->id) }}" onsubmit="return confirm('Hapus restoran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-12 text-gray-500">Tidak ada restoran ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($restoran->hasPages())
    <div class="px-4 py-3 border-t border-[#2d3548]">{{ $restoran->links() }}</div>
    @endif
</div>
@endsection
