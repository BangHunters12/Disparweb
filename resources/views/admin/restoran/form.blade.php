@extends('layouts.admin')
@section('title', isset($restoran) ? 'Edit Restoran' : 'Tambah Restoran')
@section('page-title', isset($restoran) ? 'Edit Restoran' : 'Tambah Restoran')

@section('content')
<form method="POST"
      action="{{ isset($restoran) ? route('admin.restoran.update', $restoran->id) : route('admin.restoran.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if(isset($restoran)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Left col --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Info Dasar --}}
            <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
                <h3 class="font-bold text-white text-sm mb-4 pb-3 border-b border-[#2d3548]">Informasi Dasar</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Nama Usaha <span class="text-red-400">*</span></label>
                        <input type="text" name="nama_usaha" value="{{ old('nama_usaha', $restoran->nama_usaha ?? '') }}" required
                               class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500 @error('nama_usaha') border-red-500 @enderror">
                        @error('nama_usaha')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Kecamatan <span class="text-red-400">*</span></label>
                        <select name="kecamatan_id" required class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-gray-300 text-sm rounded-xl focus:outline-none focus:border-amber-500 @error('kecamatan_id') border-red-500 @enderror">
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatanList as $k)
                            <option value="{{ $k->id }}" {{ old('kecamatan_id', $restoran->kecamatan_id ?? '') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('kecamatan_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500">{{ old('alamat', $restoran->alamat ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Latitude</label>
                        <input type="number" step="any" name="latitude" value="{{ old('latitude', $restoran->latitude ?? '') }}"
                               class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500" placeholder="-7.9117">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Longitude</label>
                        <input type="number" step="any" name="longitude" value="{{ old('longitude', $restoran->longitude ?? '') }}"
                               class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500" placeholder="113.8231">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">No. Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $restoran->no_telepon ?? '') }}"
                               class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500" placeholder="0812xxxxxxx">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Website</label>
                        <input type="url" name="website" value="{{ old('website', $restoran->website ?? '') }}"
                               class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500" placeholder="https://...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Harga Minimum (Rp)</label>
                        <input type="number" name="harga_min" value="{{ old('harga_min', $restoran->harga_min ?? '') }}"
                               class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500" placeholder="15000">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Harga Maksimum (Rp)</label>
                        <input type="number" name="harga_max" value="{{ old('harga_max', $restoran->harga_max ?? '') }}"
                               class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500" placeholder="50000">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500">{{ old('deskripsi', $restoran->deskripsi ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Fasilitas --}}
            <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
                <h3 class="font-bold text-white text-sm mb-4">Fasilitas</h3>
                @php $fasilitas = ['Parkir', 'WiFi', 'AC', 'Mushola', 'Toilet', 'TV', 'Colokan', 'Area Merokok', 'Delivery', 'Takeaway']; @endphp
                <div class="flex flex-wrap gap-3">
                    @foreach($fasilitas as $f)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="fasilitas[]" value="{{ strtolower($f) }}"
                               {{ in_array(strtolower($f), old('fasilitas', $restoran->fasilitas ?? [])) ? 'checked' : '' }}
                               class="w-4 h-4 accent-amber-500">
                        <span class="text-sm text-gray-300">{{ $f }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right col --}}
        <div class="space-y-5">
            {{-- Status + Foto --}}
            <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
                <h3 class="font-bold text-white text-sm mb-4">Status</h3>
                <select name="status" class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-gray-300 text-sm rounded-xl focus:outline-none focus:border-amber-500">
                    <option value="aktif" {{ old('status', $restoran->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tutup" {{ old('status', $restoran->status ?? '') === 'tutup' ? 'selected' : '' }}>Tutup</option>
                    <option value="review" {{ old('status', $restoran->status ?? '') === 'review' ? 'selected' : '' }}>Review</option>
                </select>
            </div>

            <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
                <h3 class="font-bold text-white text-sm mb-4">Foto Utama</h3>
                @if(isset($restoran) && $restoran->foto_utama)
                <img src="{{ $restoran->foto_utama_url }}" alt="" class="w-full h-36 object-cover rounded-xl mb-3">
                @endif
                <input type="file" name="foto_utama" accept="image/*"
                       class="w-full text-sm text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-[#0f1117] hover:file:bg-amber-400">
                <p class="text-xs text-gray-600 mt-2">JPG, PNG, WebP. Maks 4MB.</p>
                @error('foto_utama')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <a href="{{ route('admin.restoran.index') }}" class="flex-1 py-2.5 text-center text-sm font-semibold text-gray-400 bg-[#1a1f2e] border border-[#2d3548] rounded-xl hover:bg-[#2d3548] transition-all">
                    Batal
                </a>
                <button type="submit" class="flex-1 py-2.5 text-sm font-black text-[#0f1117] bg-amber-500 hover:bg-amber-400 rounded-xl transition-all">
                    {{ isset($restoran) ? 'Simpan Perubahan' : 'Tambah Restoran' }}
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
