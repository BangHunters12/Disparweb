@extends('layouts.admin')
@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-6">
        <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h3 class="font-bold text-white text-sm mb-1">Laporan Bulanan PDF</h3>
        <p class="text-xs text-gray-500 mb-4">Laporan restoran, SAW, dan sentimen per bulan.</p>
        <a href="{{ route('admin.laporan.export-pdf', ['month'=>now()->month,'year'=>now()->year]) }}"
           class="flex items-center justify-center gap-2 w-full py-2.5 bg-red-600/20 border border-red-500/30 text-red-400 font-semibold rounded-xl text-sm hover:bg-red-600/30 transition-all">
            Download PDF
        </a>
    </div>

    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-6">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="font-bold text-white text-sm mb-1">Export Excel</h3>
        <p class="text-xs text-gray-500 mb-4">Export data restoran lengkap ke spreadsheet.</p>
        <a href="{{ route('admin.laporan.export-excel') }}"
           class="flex items-center justify-center gap-2 w-full py-2.5 bg-emerald-600/20 border border-emerald-500/30 text-emerald-400 font-semibold rounded-xl text-sm hover:bg-emerald-600/30 transition-all">
            Download Excel
        </a>
    </div>

    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-6">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <h3 class="font-bold text-white text-sm mb-1">Laporan SAW PDF</h3>
        <p class="text-xs text-gray-500 mb-4">Peringkat rekomendasi SAW lengkap dengan skor.</p>
        <a href="{{ route('admin.saw.export-pdf') }}"
           class="flex items-center justify-center gap-2 w-full py-2.5 bg-amber-500/20 border border-amber-500/30 text-amber-400 font-semibold rounded-xl text-sm hover:bg-amber-500/30 transition-all">
            Download PDF SAW
        </a>
    </div>
</div>
@endsection
