@extends('layouts.dashboard')
@section('title', 'Edit Tempat: ' . $tempat->nama_usaha)
@section('page-title', 'Edit Tempat')

@section('content')
@php
    $fallbackImage = 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1000&h=620&q=70';
    $imageSrc = $tempat->foto_utama ? Storage::url($tempat->foto_utama) : $fallbackImage;
@endphp

@if($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl px-4 py-3 text-sm mb-5">
        <ul class="space-y-1">@foreach($errors->all() as $e)<li>- {{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form id="admin-place-form" action="{{ route('admin.tempat.update', $tempat->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="admin-edit-shell">
        <section class="card overflow-hidden">
            <label class="admin-edit-image" for="foto_utama">
                <img id="admin-edit-image-preview" src="{{ $imageSrc }}" alt="{{ $tempat->nama_usaha }}">
                <span>Klik gambar untuk upload foto baru</span>
            </label>
            <input id="foto_utama" type="file" name="foto_utama" accept="image/*" class="admin-edit-file">

            <div class="admin-edit-main-fields">
                <div class="admin-edit-title-grid">
                    <div>
                        <label class="form-label">Nama Usaha *</label>
                        <input type="text" name="nama_usaha" value="{{ old('nama_usaha', $tempat->nama_usaha) }}" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Status *</label>
                        <select name="status" required class="form-input">
                            @foreach(['aktif' => 'Aktif', 'tutup' => 'Tutup', 'review' => 'Review'] as $val => $lbl)
                                <option value="{{ $val }}" {{ old('status', $tempat->status) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="5" class="form-input resize-none">{{ old('deskripsi', $tempat->deskripsi) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" rows="3" class="form-input resize-none">{{ old('alamat', $tempat->alamat) }}</textarea>
                </div>
            </div>
        </section>

        <aside class="admin-edit-side">
            <section class="card p-5">
                <h3 class="font-bold text-white mb-4">Data Kategori</h3>
                <div class="admin-edit-field-stack">
                    <div>
                        <label class="form-label">Kategori *</label>
                        <select name="kategori_id" required class="form-input">
                            @foreach($kategoriList as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id', $tempat->kategori_id) === $k->id ? 'selected' : '' }}>
                                    {{ $k->nama }} ({{ ucfirst($k->jenis) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Kecamatan *</label>
                        <select name="kecamatan_id" required class="form-input">
                            @foreach($kecamatanList as $kec)
                                <option value="{{ $kec->id }}" {{ old('kecamatan_id', $tempat->kecamatan_id) === $kec->id ? 'selected' : '' }}>{{ $kec->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Kode Dispar</label>
                        <input type="text" name="kode_dispar" value="{{ old('kode_dispar', $tempat->kode_dispar) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tgl Daftar Dispar</label>
                        <input type="date" name="tgl_daftar_dispar" value="{{ old('tgl_daftar_dispar', $tempat->tgl_daftar_dispar?->format('Y-m-d')) }}" class="form-input">
                    </div>
                </div>
            </section>

            <section class="card p-5">
                <h3 class="font-bold text-white mb-4">Lokasi & Harga</h3>
                <div class="admin-edit-field-stack">
                    <div>
                        <label class="form-label">Latitude</label>
                        <input type="number" step="0.00000001" name="latitude" value="{{ old('latitude', $tempat->latitude) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Longitude</label>
                        <input type="number" step="0.00000001" name="longitude" value="{{ old('longitude', $tempat->longitude) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $tempat->no_telepon) }}" class="form-input">
                    </div>
                    <div class="admin-edit-two-col">
                        <div>
                            <label class="form-label">Harga Min</label>
                            <input type="number" name="harga_min" value="{{ old('harga_min', $tempat->harga_min) }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Harga Max</label>
                            <input type="number" name="harga_max" value="{{ old('harga_max', $tempat->harga_max) }}" class="form-input">
                        </div>
                    </div>
                    <label class="admin-edit-checkbox">
                        <input type="checkbox" name="sumber_dispar" value="1" {{ old('sumber_dispar', $tempat->sumber_dispar ? '1' : '0') !== '0' ? 'checked' : '' }}>
                        <span>Sumber dari Dispar</span>
                    </label>
                </div>
            </section>

            <div class="admin-edit-sticky-actions">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.tempat.show', $tempat->id) }}" class="btn-secondary">Batal</a>
            </div>
        </aside>
    </div>
</form>

<section class="card p-6 mt-6">
    <div class="admin-section-heading">
        <h3 class="font-bold text-white">Edit Ulasan Pengguna</h3>
        <span class="text-gray-400 text-sm">{{ $tempat->ulasan->count() }} ulasan</span>
    </div>

    <div class="admin-review-edit-list">
        @forelse($tempat->ulasan as $u)
            <article class="admin-review-edit-item">
                <div class="admin-review-avatar">
                    {{ mb_substr($u->user?->nama_lengkap ?? 'A', 0, 1) }}
                </div>
                <div class="admin-review-edit-body">
                    <div class="admin-review-topline">
                        <div>
                            <h4>{{ $u->user?->nama_lengkap ?? 'Anonim' }}</h4>
                            <p>{{ $u->created_at->diffForHumans() }}</p>
                        </div>
                        @if($u->analisisSentimen)
                            @php $label = $u->analisisSentimen->label_sentimen; @endphp
                            <span class="badge {{ $label === 'positif' ? 'badge-green' : ($label === 'negatif' ? 'badge-red' : 'badge-gray') }} capitalize">{{ $label }}</span>
                        @endif
                    </div>

                    <form action="{{ route('admin.tempat.ulasan.update', [$tempat->id, $u->id]) }}" method="POST" class="admin-review-edit-form">
                        @csrf
                        @method('PUT')
                        <div class="admin-edit-two-col">
                            <div>
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-input">
                                    @for($rating = 1; $rating <= 5; $rating++)
                                        <option value="{{ $rating }}" {{ (int) $u->rating === $rating ? 'selected' : '' }}>{{ $rating }} bintang</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Komentar</label>
                            <textarea name="teks_ulasan" rows="3" class="form-input resize-none" required minlength="10">{{ $u->teks_ulasan }}</textarea>
                        </div>
                        <button type="submit" class="btn-secondary btn-sm">Simpan Komentar</button>
                    </form>

                    <form action="{{ route('admin.tempat.ulasan.destroy', [$tempat->id, $u->id]) }}" method="POST" onsubmit="return confirm('Hapus ulasan dari {{ addslashes($u->user?->nama_lengkap ?? 'pengguna') }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">Hapus Komentar</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="admin-empty-state">
                <h4>Belum ada ulasan</h4>
                <p>Tempat ini belum memiliki komentar dari pengguna.</p>
            </div>
        @endforelse
    </div>
</section>

@push('scripts')
<script>
document.getElementById('foto_utama')?.addEventListener('change', function (event) {
    const file = event.target.files && event.target.files[0];
    if (!file) return;

    const preview = document.getElementById('admin-edit-image-preview');
    if (preview) {
        preview.src = URL.createObjectURL(file);
    }
});
</script>
@endpush
@endsection
