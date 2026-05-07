@extends('layouts.admin')
@section('title', isset($kecamatan) ? 'Edit Kecamatan' : 'Tambah Kecamatan')
@section('page-title', isset($kecamatan) ? 'Edit Kecamatan' : 'Tambah Kecamatan')

@section('content')
<div class="max-w-lg">
    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-6">
        <form method="POST" action="{{ isset($kecamatan) ? route('admin.kecamatan.update', $kecamatan->id) : route('admin.kecamatan.store') }}">
            @csrf
            @if(isset($kecamatan)) @method('PUT') @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Nama Kecamatan <span class="text-red-400">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $kecamatan->nama ?? '') }}" required
                           class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500 @error('nama') border-red-500 @enderror">
                    @error('nama')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Kode Pos</label>
                    <input type="text" name="kode_pos" value="{{ old('kode_pos', $kecamatan->kode_pos ?? '') }}"
                           class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500" placeholder="68211">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Latitude Pusat</label>
                        <input type="number" step="any" name="lat_center" value="{{ old('lat_center', $kecamatan->lat_center ?? '') }}"
                               class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500" placeholder="-7.9117">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5">Longitude Pusat</label>
                        <input type="number" step="any" name="lng_center" value="{{ old('lng_center', $kecamatan->lng_center ?? '') }}"
                               class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500" placeholder="113.8231">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.kecamatan.index') }}" class="flex-1 py-2.5 text-center text-sm font-semibold text-gray-400 bg-[#0f1117] border border-[#2d3548] rounded-xl hover:bg-[#2d3548] transition-all">Batal</a>
                <button type="submit" class="flex-1 py-2.5 text-sm font-black text-[#0f1117] bg-amber-500 hover:bg-amber-400 rounded-xl transition-all">
                    {{ isset($kecamatan) ? 'Simpan' : 'Tambah' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
