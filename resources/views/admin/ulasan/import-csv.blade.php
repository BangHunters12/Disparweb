@extends('layouts.admin')
@section('title', 'Import Ulasan CSV')
@section('page-title', 'Import Ulasan dari CSV')

@section('page-actions')
<a href="{{ route('admin.ulasan.index') }}" class="flex items-center gap-2 px-4 py-2 text-gray-400 bg-[#1a1f2e] border border-[#2d3548] rounded-xl text-sm hover:border-amber-500/50 transition-all">
    ← Kembali
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Form Upload --}}
    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-6">
        <h3 class="font-bold text-white text-sm mb-4">📂 Upload File CSV</h3>

        <form method="POST" action="{{ route('admin.ulasan.import-csv') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">File CSV <span class="text-red-400">*</span></label>
                    <input type="file" name="file" accept=".csv,.txt" required
                           class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500/20 file:text-amber-400 hover:file:bg-amber-500/30">
                    @error('file')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5">Platform Sumber</label>
                    <select name="platform_sumber"
                            class="w-full px-3 py-2.5 bg-[#0f1117] border border-[#2d3548] text-white text-sm rounded-xl focus:outline-none focus:border-amber-500">
                        <option value="dispar">Dinas Pariwisata</option>
                        <option value="gmaps">Google Maps (Outscraper/Manual)</option>
                        <option value="app">Aplikasi</option>
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="auto_analyze" value="1" id="auto_analyze" checked class="w-4 h-4 accent-amber-500">
                    <label for="auto_analyze" class="text-sm text-gray-400">Analisis sentimen otomatis setelah import</label>
                </div>

                <button type="submit"
                        class="w-full py-3 bg-amber-500 hover:bg-amber-400 text-[#0f1117] font-black text-sm rounded-xl transition-all">
                    📥 Import Ulasan
                </button>
            </div>
        </form>
    </div>

    {{-- Petunjuk Format --}}
    <div class="space-y-4">
        <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-6">
            <h3 class="font-bold text-white text-sm mb-3">📋 Format CSV</h3>
            <p class="text-xs text-gray-500 mb-3">Header baris pertama wajib seperti berikut:</p>
            <div class="bg-[#0f1117] rounded-xl p-3 overflow-x-auto">
                <code class="text-xs text-amber-400 whitespace-nowrap">nama_restoran,nama_reviewer,rating,teks_ulasan,tgl_kunjungan</code>
            </div>
            <p class="text-xs text-gray-600 mt-2">Kolom <code class="text-amber-400">tgl_kunjungan</code> opsional. Format tanggal: YYYY-MM-DD</p>
        </div>

        <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-6">
            <h3 class="font-bold text-white text-sm mb-3">📄 Contoh Isi CSV</h3>
            <div class="bg-[#0f1117] rounded-xl p-3 overflow-x-auto">
                <pre class="text-xs text-gray-400 whitespace-pre">nama_restoran,nama_reviewer,rating,teks_ulasan,tgl_kunjungan
Warung Soto Pak Budi,Andi S,5,"Enak banget, kuahnya gurih",2024-03-15
Warung Soto Pak Budi,Rina W,4,"Porsi besar harga terjangkau",2024-03-10
RM Nusantara,Budi H,3,"Biasa saja tidak istimewa",
RM Nusantara,Siti M,5,"Masakan rumahan yang lezat",2024-02-28</pre>
            </div>
        </div>

        <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
            <h3 class="font-bold text-white text-sm mb-3">⬇️ Template</h3>
            <a href="{{ route('admin.ulasan.csv-template') }}"
               class="flex items-center justify-center gap-2 w-full py-2.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-semibold rounded-xl text-sm hover:bg-emerald-500/20 transition-all">
                Download Template CSV
            </a>
        </div>
    </div>
</div>

{{-- Hasil Import --}}
@if(session('import_result'))
<div class="mt-5 bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-5">
    <h3 class="font-bold text-white text-sm mb-3">📊 Hasil Import</h3>
    @php $result = session('import_result'); @endphp
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-3 text-center">
            <p class="text-2xl font-black text-emerald-400">{{ $result['berhasil'] }}</p>
            <p class="text-xs text-gray-500">Berhasil</p>
        </div>
        <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3 text-center">
            <p class="text-2xl font-black text-red-400">{{ $result['gagal'] }}</p>
            <p class="text-xs text-gray-500">Gagal/Dilewati</p>
        </div>
        <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-3 text-center">
            <p class="text-2xl font-black text-amber-400">{{ $result['dianalisis'] }}</p>
            <p class="text-xs text-gray-500">Dianalisis</p>
        </div>
    </div>
    @if(!empty($result['errors']))
    <div class="mt-3 text-xs text-red-400 space-y-1">
        @foreach(array_slice($result['errors'], 0, 5) as $err)
        <p>⚠ {{ $err }}</p>
        @endforeach
    </div>
    @endif
</div>
@endif
@endsection
