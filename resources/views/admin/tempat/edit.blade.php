@extends('layouts.dashboard')
@section('title', 'Edit Tempat: ' . $tempat->nama_usaha)
@section('page-title', 'Edit: ' . $tempat->nama_usaha)

@section('content')
<div class="max-w-3xl">
    <div class="card p-8">
        <form action="{{ route('admin.tempat.update', $tempat->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl px-4 py-3 text-sm">
                    <ul class="space-y-1">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="form-label">Nama Usaha *</label>
                    <input type="text" name="nama_usaha" value="{{ old('nama_usaha', $tempat->nama_usaha) }}" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Kategori *</label>
                    <select name="kategori_id" required class="form-input">
                        @foreach($kategoriList as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id', $tempat->kategori_id) === $k->id ? 'selected' : '' }}>{{ $k->nama }} ({{ ucfirst($k->jenis) }})</option>
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
                <div class="md:col-span-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" rows="2" class="form-input resize-none">{{ old('alamat', $tempat->alamat) }}</textarea>
                </div>
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
                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        @foreach(['aktif' => 'Aktif', 'tutup' => 'Tutup', 'review' => 'Review'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('status', $tempat->status) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Harga Minimum (Rp)</label>
                    <input type="number" name="harga_min" value="{{ old('harga_min', $tempat->harga_min) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Harga Maximum (Rp)</label>
                    <input type="number" name="harga_max" value="{{ old('harga_max', $tempat->harga_max) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Kode Dispar</label>
                    <input type="text" name="kode_dispar" value="{{ old('kode_dispar', $tempat->kode_dispar) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Tgl Daftar Dispar</label>
                    <input type="date" name="tgl_daftar_dispar" value="{{ old('tgl_daftar_dispar', $tempat->tgl_daftar_dispar?->format('Y-m-d')) }}" class="form-input">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-input resize-none">{{ old('deskripsi', $tempat->deskripsi) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Foto Utama</label>
                    @if($tempat->foto_utama)
                        <img src="{{ Storage::url($tempat->foto_utama) }}" class="w-32 h-24 object-cover rounded-xl mb-2">
                    @endif
                    <input type="file" name="foto_utama" accept="image/*" class="form-input py-2 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:bg-amber-500/20 file:text-amber-400 file:font-medium file:cursor-pointer">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="sumber_dispar" id="sumber_dispar" value="1" {{ $tempat->sumber_dispar ? 'checked' : '' }} class="rounded bg-dark-700 border-dark-600 text-amber-500">
                    <label for="sumber_dispar" class="form-label mb-0">Sumber dari Dispar</label>
                </div>
            </div>
            <div class="flex gap-3 pt-4 border-t border-dark-700">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.tempat.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
