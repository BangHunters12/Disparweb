@extends('layouts.admin')
@section('title', 'Kecamatan')
@section('page-title', 'Kecamatan')

@section('page-actions')
<a href="{{ route('admin.kecamatan.create') }}" class="flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-400 text-[#0f1117] font-bold rounded-xl text-sm transition-all">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah
</a>
@endsection

@section('content')
<div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-[#2d3548]">
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-semibold">Nama Kecamatan</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-semibold hidden md:table-cell">Kode Pos</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-semibold hidden lg:table-cell">Koordinat Pusat</th>
                <th class="px-4 py-3 text-center text-xs text-gray-500 font-semibold">Restoran</th>
                <th class="px-4 py-3 text-right text-xs text-gray-500 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#2d3548]">
            @forelse($kecamatan as $k)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-4 py-3 font-semibold text-white">{{ $k->nama }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs hidden md:table-cell">{{ $k->kode_pos ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs font-mono hidden lg:table-cell">
                    {{ $k->lat_center ? number_format($k->lat_center, 4).','.number_format($k->lng_center, 4) : '—' }}
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="text-white font-bold">{{ $k->restoran_count }}</span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.kecamatan.edit', $k->id) }}" class="p-1.5 text-gray-500 hover:text-amber-400 hover:bg-amber-500/10 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.kecamatan.destroy', $k->id) }}" onsubmit="return confirm('Hapus kecamatan {{ $k->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-10 text-gray-500">Tidak ada kecamatan.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($kecamatan->hasPages())
    <div class="px-4 py-3 border-t border-[#2d3548]">{{ $kecamatan->links() }}</div>
    @endif
</div>
@endsection
