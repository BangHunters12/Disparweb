<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BondoWisata') — Wisata Bondowoso</title>
    <meta name="description" content="@yield('meta-description', 'Temukan destinasi wisata terbaik di Kabupaten Bondowoso — Restoran, Hotel, dan Ekonomi Kreatif.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-900 text-slate-200 font-sans antialiased">

{{-- Navbar --}}
<nav class="sticky top-0 z-50 border-b border-dark-700" style="background:rgba(15,17,23,0.9);backdrop-filter:blur(20px)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-dark-900" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </div>
                <span class="font-black text-lg text-white">Bondo<span class="text-amber-400">Wisata</span></span>
            </a>

            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'text-amber-400 bg-amber-500/10' : 'text-gray-400 hover:text-white hover:bg-dark-700' }} transition-all">Beranda</a>
                <a href="{{ route('explore') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('explore') ? 'text-amber-400 bg-amber-500/10' : 'text-gray-400 hover:text-white hover:bg-dark-700' }} transition-all">Jelajahi</a>
                <a href="{{ route('map') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('map') ? 'text-amber-400 bg-amber-500/10' : 'text-gray-400 hover:text-white hover:bg-dark-700' }} transition-all">Peta</a>
            </div>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard.index') }}"
                       class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-dark-700 hover:bg-dark-600 text-sm text-gray-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ Str::limit(auth()->user()->nama_lengkap, 15) }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-dark-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary btn-sm">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-20 right-4 z-50 flex items-center gap-3 bg-emerald-500/90 backdrop-blur text-white px-4 py-3 rounded-xl shadow-2xl text-sm font-medium"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error') || $errors->any())
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-20 right-4 z-50 flex items-center gap-3 bg-red-500/90 backdrop-blur text-white px-4 py-3 rounded-xl shadow-2xl text-sm font-medium"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ session('error', 'Terjadi kesalahan. Periksa form Anda.') }}
    </div>
@endif

<main>@yield('content')</main>

<footer class="bg-dark-800 border-t border-dark-700 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-amber-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-dark-900" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    </div>
                    <span class="font-black text-white">Bondo<span class="text-amber-400">Wisata</span></span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed">Platform rekomendasi wisata resmi Kabupaten Bondowoso berbasis data Dinas Pariwisata.</p>
            </div>
            <div>
                <h3 class="font-semibold text-white text-sm mb-3">Navigasi</h3>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('explore') }}" class="hover:text-amber-400 transition-colors">Jelajahi Wisata</a></li>
                    <li><a href="{{ route('map') }}" class="hover:text-amber-400 transition-colors">Peta Interaktif</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-white text-sm mb-3">Kategori</h3>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="{{ route('explore', ['kategori' => 'restoran']) }}" class="hover:text-amber-400 transition-colors">Restoran & Kuliner</a></li>
                    <li><a href="{{ route('explore', ['kategori' => 'hotel']) }}" class="hover:text-amber-400 transition-colors">Hotel & Penginapan</a></li>
                    <li><a href="{{ route('explore', ['kategori' => 'ekraf']) }}" class="hover:text-amber-400 transition-colors">Ekonomi Kreatif</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 pt-6 border-t border-dark-700 text-center text-gray-600 text-xs">
            &copy; {{ date('Y') }} BondoWisata — Dinas Pariwisata Kabupaten Bondowoso
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
