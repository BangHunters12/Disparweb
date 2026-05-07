<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — BondoWisata Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('head-scripts')
</head>
<body class="bg-[#0f1117] text-slate-200 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside id="sidebar" class="w-64 flex-shrink-0 bg-[#161a24] border-r border-[#2d3548] flex flex-col transition-all duration-300 overflow-y-auto">
        {{-- Logo --}}
        <div class="flex items-center gap-2.5 px-5 py-5 border-b border-[#2d3548]">
            <div class="w-8 h-8 rounded-xl bg-amber-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[#0f1117]" fill="currentColor" viewBox="0 0 24 24"><path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V3h-1.97v2.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v1.06h-2zm-3.66-4.29c0 .33-.27.6-.6.6-.33 0-.6-.27-.6-.6V14.6c0-.33.27-.6.6-.6.33 0 .6.27.6.6v4.1zM3 21.99V23h15v-1.01c0-2.76-2.24-5-5-5H8c-2.76 0-5 2.24-5 5zm0-6.94c0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3-3 1.34-3 3z"/></svg>
            </div>
            <div>
                <p class="font-black text-white text-sm">BondoWisata</p>
                <p class="text-[10px] text-gray-500 font-medium">Admin Panel</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5">
            @php
            $navItems = [
                ['route' => 'admin.dashboard',      'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard'],
                ['route' => 'admin.restoran.index', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'label' => 'Restoran'],
                ['route' => 'admin.ulasan.index',   'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'label' => 'Ulasan'],
                ['route' => 'admin.sentimen.index', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Sentimen'],
                ['route' => 'admin.saw.index',      'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'label' => 'SAW'],
                ['route' => 'admin.laporan.index',  'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Laporan'],
                ['route' => 'admin.kecamatan.index','icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Kecamatan'],
            ];
            @endphp

            @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150
                      {{ request()->routeIs($item['route']) || request()->routeIs($item['route'].'*')
                         ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20'
                         : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach

            <div class="pt-3 border-t border-[#2d3548] mt-3 space-y-0.5">
                <a href="{{ route('admin.restoran.import-gmaps') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm text-blue-400 hover:bg-blue-500/10 transition-all">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Google Maps
                </a>
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm text-gray-500 hover:text-gray-300 hover:bg-white/5 transition-all">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Lihat Website
                </a>
            </div>
        </nav>

        {{-- Admin info --}}
        <div class="px-3 py-4 border-t border-[#2d3548]">
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-[#1a1f2e]">
                <div class="w-8 h-8 rounded-full bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-bold text-amber-400">{{ strtoupper(substr(auth('admin')->user()?->nama ?? 'A', 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-white truncate">{{ auth('admin')->user()?->nama }}</p>
                    <p class="text-[10px] text-gray-500 truncate">{{ auth('admin')->user()?->email }}</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-red-400 transition-colors" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Topbar --}}
        <header class="flex-shrink-0 flex items-center justify-between px-6 py-4 bg-[#161a24] border-b border-[#2d3548]">
            <div>
                <h1 class="text-base font-black text-white">@yield('page-title', 'Dashboard')</h1>
                @hasSection('breadcrumb')
                <nav class="text-xs text-gray-500 mt-0.5">@yield('breadcrumb')</nav>
                @endif
            </div>
            <div class="flex items-center gap-2">
                @yield('page-actions')
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            {{-- Flash messages --}}
            @if(session('success'))
            <div class="mb-4 flex items-center gap-3 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400 text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 flex items-center gap-3 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
<script>
// CSRF token for AJAX
window.csrfToken = '{{ csrf_token() }}';
</script>
</body>
</html>
