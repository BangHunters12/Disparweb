@extends('layouts.dashboard')
@section('title', 'Manajemen Tempat')
@section('page-title', 'Tempat & Ulasan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex gap-3">
        <a href="{{ route('admin.tempat.create') }}" class="btn-primary">+ Tambah Tempat</a>
        <button onclick="document.getElementById('modal-import').classList.remove('hidden')" class="btn-secondary">Import CSV</button>
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
        @if(request()->hasAny(['search', 'kategori', 'status']))
            <a href="{{ route('admin.tempat.index') }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

@if(session('import_errors'))
    <div class="card p-4 mb-4 border-amber-500/30">
        <p class="text-amber-400 text-sm font-semibold mb-2">Error saat import:</p>
        <ul class="text-xs text-gray-400 space-y-1">
            @foreach(session('import_errors') as $err)
                <li>- {{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-5 text-sm text-gray-400">
    Menampilkan <span class="text-white font-semibold">{{ $tempat->total() }}</span> tempat
    @if(request('search')) untuk "<span class="text-amber-400">{{ request('search') }}</span>" @endif
</div>

@if($tempat->isEmpty())
    <div class="card p-12 text-center">
        <h3 class="text-lg font-bold text-white mb-2">Tidak ada tempat</h3>
        <p class="text-gray-500 mb-4">Coba ubah filter atau tambah data tempat baru.</p>
        <a href="{{ route('admin.tempat.create') }}" class="btn-primary">Tambah Tempat</a>
    </div>
@else
    <div class="admin-place-grid">
        @foreach($tempat as $t)
            @php
                $placeholders = [
                    'restoran' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=420&h=280&q=55',
                    'hotel' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=420&h=280&q=55',
                    'ekraf' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=420&h=280&q=55',
                ];
                $imgSrc = $t->foto_utama
                    ? Storage::url($t->foto_utama)
                    : ($placeholders[$t->kategori->jenis] ?? $placeholders['ekraf']);
                $avgRating = $t->ulasan_avg_rating ?? 0;
                $latestReview = $t->latestUlasan;
            @endphp

            <article class="admin-place-card">
                <a href="{{ route('admin.tempat.show', $t->id) }}" class="admin-place-media">
                    <img
                        src="{{ $imgSrc }}"
                        alt="{{ $t->nama_usaha }}"
                        width="420"
                        height="280"
                        loading="lazy"
                        decoding="async"
                        fetchpriority="low">
                    <span class="badge {{ $t->kategori->jenis === 'restoran' ? 'badge-amber' : ($t->kategori->jenis === 'hotel' ? 'badge-blue' : 'badge-green') }} capitalize">
                        {{ $t->kategori->jenis }}
                    </span>
                    @if($t->status !== 'aktif')
                        <span class="admin-place-status badge {{ $t->status === 'tutup' ? 'badge-red' : 'badge-gray' }} capitalize">{{ $t->status }}</span>
                    @endif
                </a>

                <div class="admin-place-content">
                    <div class="admin-place-title-row">
                        <div class="min-w-0">
                            <a href="{{ route('admin.tempat.show', $t->id) }}" class="admin-place-title">{{ $t->nama_usaha }}</a>
                            <p class="admin-place-meta">{{ $t->kecamatan->nama }} @if($t->kode_dispar) - {{ $t->kode_dispar }} @endif</p>
                        </div>
                        <div class="admin-place-rating">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span>{{ number_format($avgRating, 1) }}</span>
                        </div>
                    </div>

                    <div class="admin-place-stats">
                        <span>{{ $t->ulasan_count }} ulasan</span>
                        @if($t->harga_min)
                            <span>Rp {{ number_format($t->harga_min, 0, ',', '.') }}+</span>
                        @else
                            <span>Harga belum diisi</span>
                        @endif
                    </div>

                    <div class="admin-review-preview">
                        @if($latestReview)
                            <p class="admin-review-user">{{ $latestReview->user?->nama_lengkap ?? 'Anonim' }}</p>
                            <p>{{ Str::limit($latestReview->teks_ulasan, 92) }}</p>
                        @else
                            <p>Belum ada ulasan dari pengguna.</p>
                        @endif
                    </div>

                    <div class="admin-place-actions">
                        <a href="{{ route('admin.tempat.show', $t->id) }}" class="btn-secondary btn-sm">Lihat Ulasan</a>
                        <a href="{{ route('admin.tempat.edit', $t->id) }}" class="btn-primary btn-sm">Edit</a>
                        <form action="{{ route('admin.tempat.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus {{ addslashes($t->nama_usaha) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-6">{{ $tempat->withQueryString()->links('vendor.pagination.custom') }}</div>
@endif

<div id="modal-import" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
    <div class="card p-6 w-full max-w-md">
        <h3 class="font-bold text-white mb-4">Import Data CSV</h3>
        <p class="text-gray-400 text-sm mb-4">Format CSV: kode_dispar, nama_usaha, jenis_kategori, kecamatan, alamat, latitude, longitude, no_telepon, harga_min, harga_max, deskripsi, tgl_daftar. Data import masuk status review sampai admin mempublikasikannya.</p>
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
