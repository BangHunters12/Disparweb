<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BondoWisata') — Kuliner Bondowoso</title>
    <meta name="description" content="@yield('meta-description', 'Temukan kuliner terbaik di Kabupaten Bondowoso — restoran, warung, dan cafe terpilih.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head-scripts')
</head>
<body class="bg-[#0f1117] text-slate-200 font-sans antialiased">

{{-- Navbar --}}
<nav id="navbar" class="sticky top-0 z-50 border-b border-[#2d3548] transition-all duration-300" style="background:rgba(15,17,23,0.92);backdrop-filter:blur(20px)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
                <div class="w-8 h-8 rounded-xl bg-amber-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#0f1117]" fill="currentColor" viewBox="0 0 24 24"><path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V3h-1.97v2.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v1.06h-2zm-3.66-4.29c0 .33-.27.6-.6.6-.33 0-.6-.27-.6-.6V14.6c0-.33.27-.6.6-.6.33 0 .6.27.6.6v4.1zM3 21.99V23h15v-1.01c0-2.76-2.24-5-5-5H8c-2.76 0-5 2.24-5 5zm0-6.94c0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3-3 1.34-3 3z"/></svg>
                </div>
                <span class="font-black text-lg text-white">Bondo<span class="text-amber-400">Wisata</span></span>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'text-amber-400 bg-amber-500/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }} transition-all">Beranda</a>
                <a href="{{ route('restoran.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('restoran.*') ? 'text-amber-400 bg-amber-500/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }} transition-all">Restoran</a>
                <a href="{{ route('peta') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('peta') ? 'text-amber-400 bg-amber-500/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }} transition-all">Peta</a>
            </div>

            {{-- CTA + Mobile Menu --}}
            <div class="flex items-center gap-3">
                <a href="#download-app" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-[#0f1117] font-bold text-sm transition-all hover:scale-105">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.7 9.05 7.42c1.32.07 2.25.8 3.03.82.97-.19 1.9-.95 3.14-.86 1.97.17 3.34 1.1 4.05 2.66-3.57 2.02-2.7 6.98.78 8.24zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
                    Download App
                </a>
                {{-- Hamburger --}}
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5" aria-label="Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-[#2d3548] mt-2 pt-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'text-amber-400 bg-amber-500/10' : 'text-gray-400' }}">Beranda</a>
            <a href="{{ route('restoran.index') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('restoran.*') ? 'text-amber-400 bg-amber-500/10' : 'text-gray-400' }}">Restoran</a>
            <a href="{{ route('peta') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('peta') ? 'text-amber-400 bg-amber-500/10' : 'text-gray-400' }}">Peta</a>
            <a href="#download-app" class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold text-amber-400">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.7 9.05 7.42c1.32.07 2.25.8 3.03.82.97-.19 1.9-.95 3.14-.86 1.97.17 3.34 1.1 4.05 2.66-3.57 2.02-2.7 6.98.78 8.24zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
                Download App
            </a>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

{{-- App Download CTA Banner (Mobile Sticky) --}}
<div id="app-cta-banner" class="fixed bottom-0 left-0 right-0 z-40 sm:hidden bg-[#1a1f2e] border-t border-[#2d3548] px-4 py-3 flex items-center gap-3">
    <div class="flex-1 min-w-0">
        <p class="text-xs font-bold text-white truncate">Ingin fitur lengkap?</p>
        <p class="text-xs text-gray-400 truncate">Ulasan, favorit & rekomendasi di aplikasi</p>
    </div>
    <a href="#download-app" class="flex-shrink-0 px-3 py-1.5 rounded-lg bg-amber-500 text-[#0f1117] text-xs font-black">Download</a>
    <button onclick="document.getElementById('app-cta-banner').style.display='none'" class="text-gray-500 hover:text-gray-300 flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

{{-- Footer --}}
<footer class="bg-[#161a24] border-t border-[#2d3548] mt-20 pb-20 sm:pb-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-amber-500 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#0f1117]" fill="currentColor" viewBox="0 0 24 24"><path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V3h-1.97v2.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v1.06h-2zm-3.66-4.29c0 .33-.27.6-.6.6-.33 0-.6-.27-.6-.6V14.6c0-.33.27-.6.6-.6.33 0 .6.27.6.6v4.1zM3 21.99V23h15v-1.01c0-2.76-2.24-5-5-5H8c-2.76 0-5 2.24-5 5zm0-6.94c0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3-3 1.34-3 3z"/></svg>
                    </div>
                    <span class="font-black text-xl text-white">Bondo<span class="text-amber-400">Wisata</span></span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    Platform informasi kuliner resmi Kabupaten Bondowoso dari Dinas Pariwisata.<br>
                    Temukan restoran terbaik di Bondowoso.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 rounded-lg bg-[#2d3548] flex items-center justify-center text-gray-400 hover:text-white hover:bg-amber-500/20 transition-all" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-[#2d3548] flex items-center justify-center text-gray-400 hover:text-white hover:bg-amber-500/20 transition-all" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </div>
            </div>
            <div>
                <h3 class="font-bold text-white text-sm mb-4">Navigasi</h3>
                <ul class="space-y-2.5 text-sm text-gray-500">
                    <li><a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('restoran.index') }}" class="hover:text-amber-400 transition-colors">Semua Restoran</a></li>
                    <li><a href="{{ route('peta') }}" class="hover:text-amber-400 transition-colors">Peta Kuliner</a></li>
                    <li><a href="{{ route('restoran.index', ['sort' => 'saw']) }}" class="hover:text-amber-400 transition-colors">Top Rekomendasi</a></li>
                </ul>
            </div>
            <div id="download-app">
                <h3 class="font-bold text-white text-sm mb-4">Unduh Aplikasi</h3>
                <p class="text-xs text-gray-500 mb-3 leading-relaxed">Fitur lengkap: ulasan, favorit, rekomendasi personal & analisis sentimen.</p>
                <div class="space-y-2">
                    <a href="#" class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-[#2d3548] hover:bg-[#3d4558] transition-all group">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.7 9.05 7.42c1.32.07 2.25.8 3.03.82.97-.19 1.9-.95 3.14-.86 1.97.17 3.34 1.1 4.05 2.66-3.57 2.02-2.7 6.98.78 8.24zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
                        <div><p class="text-[10px] text-gray-500">Download di</p><p class="text-xs font-bold text-white">App Store</p></div>
                    </a>
                    <a href="#" class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-[#2d3548] hover:bg-[#3d4558] transition-all group">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M3 20.5v-17c0-.83.94-1.3 1.6-.8l14 8.5c.6.36.6 1.24 0 1.6l-14 8.5c-.66.5-1.6.03-1.6-.8z"/></svg>
                        <div><p class="text-[10px] text-gray-500">Tersedia di</p><p class="text-xs font-bold text-white">Google Play</p></div>
                    </a>
                </div>
            </div>
        </div>
        <div class="mt-10 pt-6 border-t border-[#2d3548] flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-600">
            <span>&copy; {{ date('Y') }} BondoWisata — Dinas Pariwisata Kabupaten Bondowoso</span>
            <a href="{{ route('admin.login') }}" class="hover:text-gray-400 transition-colors">Admin</a>
        </div>
    </div>
</footer>

@stack('scripts')
<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
</body>
</html>
