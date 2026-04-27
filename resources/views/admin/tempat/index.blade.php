@extends('layouts.dashboard')
@section('title', 'Manajemen Tempat')
@section('page-title', 'Data Tempat')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex gap-3">
        <a href="{{ route('admin.tempat.create') }}" class="btn-primary">+ Tambah Tempat</a>
        <button onclick="document.getElementById('modal-import').classList.remove('hidden')" class="btn-secondary">📥 Import CSV</button>
    </div>

    <form method="GET" class="flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..." class="form-input w-48">
        <select name="kategori" class="form-input w-36">
            <option value="">Semua Jenis</option>
            <option value="restoran" {{ request('kategori') === 'restoran' ? 'selected' : '' }}>Restoran</option>
            <option value="hotel" {{ request('kategori') === 'hotel' ? 'selected' : '' }}>Hotel</option>
            <option value="ekraf" {{ request('kategori') === 'ekraf' ? 'selected' : '' }}>Ekraf</option>
        </select>
        <select name="status" class="form-input w-32">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="tutup" {{ request('status') === 'tutup' ? 'selected' : '' }}>Tutup</option>
            <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>Review</option>
        </select>
        <button type="submit" class="btn-secondary">Filter</button>
    </form>
</div>

{{-- Import Error Display --}}
@if(session('import_errors'))
    <div class="card p-4 mb-4 border-amber-500/30">
        <p class="text-amber-400 text-sm font-semibold mb-2">Error saat import:</p>
        <ul class="text-xs text-gray-400 space-y-1">
            @foreach(session('import_errors') as $err)
                <li>• {{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-dark-700">
                <tr>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Tempat</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Kategori</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Kecamatan</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Ulasan</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Status</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tempat as $t)
                    <tr class="border-b border-dark-700/50 hover:bg-dark-700/30 transition-colors">
                        <td class="py-3 px-4">
                            <div class="font-medium text-white truncate max-w-[200px]">{{ $t->nama_usaha }}</div>
                            @if($t->kode_dispar)
                                <div class="text-xs text-gray-500">{{ $t->kode_dispar }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="badge {{ $t->kategori->jenis === 'restoran' ? 'badge-amber' : ($t->kategori->jenis === 'hotel' ? 'badge-blue' : 'badge-green') }} capitalize">{{ $t->kategori->jenis }}</span>
                        </td>
                        <td class="py-3 px-4 text-gray-300">{{ $t->kecamatan->nama }}</td>
                        <td class="py-3 px-4 text-gray-300">{{ $t->ulasan_count }}</td>
                        <td class="py-3 px-4">
                            <span class="badge {{ $t->status === 'aktif' ? 'badge-green' : ($t->status === 'tutup' ? 'badge-red' : 'badge-gray') }} capitalize">{{ $t->status }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.tempat.edit', $t->id) }}" class="btn-secondary btn-sm">Edit</a>
                                <form action="{{ route('admin.tempat.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus {{ addslashes($t->nama_usaha) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-500">Tidak ada data tempat</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-dark-700">
        {{ $tempat->withQueryString()->links('vendor.pagination.custom') }}
    </div>
</div>

{{-- Import Modal --}}
<div id="modal-import" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
    <div class="card p-6 w-full max-w-md">
        <h3 class="font-bold text-white mb-4">Import Data CSV</h3>
        <p class="text-gray-400 text-sm mb-4">Format CSV: kode_dispar, nama_usaha, jenis_kategori, kecamatan, alamat, latitude, longitude, no_telepon, harga_min, harga_max, deskripsi, tgl_daftar</p>
        <form action="{{ route('admin.tempat.import-csv') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="csv_file" accept=".csv,.txt" class="form-input mb-4" required>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Upload & Import</button>
                <button type="button" onclick="document.getElementById('modal-import').classList.add('hidden')" class="btn-secondary">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection
