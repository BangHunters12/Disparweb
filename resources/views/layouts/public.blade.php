<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BondoWisata') — Wisata Bondowoso</title>
    <meta name="description" content="@yield('description', 'Rekomendasi wisata terbaik di Bondowoso — restoran, hotel, dan ekonomi kreatif dari Dinas Pariwisata.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>
</head>
<body class="bg-dark-900 text-gray-100 min-h-screen flex flex-col" x-data>

    {{-- Top Navbar --}}
    <nav class="sticky top-0 z-50 bg-dark-900/80 backdrop-blur-xl border-b border-dark-700" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-amber-600 rounded-lg flex items-center justify-center">
                        <span class="text-dark-900 font-black text-sm">BW</span>
                    </div>
                    <span class="font-bold text-white text-lg">Bondo<span class="text-gradient">Wisata</span></span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-gray-400 hover:text-white hover:bg-dark-700 transition-all font-medium {{ request()->routeIs('home') ? 'text-amber-400 bg-dark-700' : '' }}">Beranda</a>
                    <a href="{{ route('explore') }}" class="px-4 py-2 rounded-xl text-gray-400 hover:text-white hover:bg-dark-700 transition-all font-medium {{ request()->routeIs('explore') ? 'text-amber-400 bg-dark-700' : '' }}">Jelajahi</a>
                    <a href="{{ route('map') }}" class="px-4 py-2 rounded-xl text-gray-400 hover:text-white hover:bg-dark-700 transition-all font-medium {{ request()->routeIs('map') ? 'text-amber-400 bg-dark-700' : '' }}">Peta</a>
                </div>

                {{-- Auth --}}
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? '/admin' : '/dashboard' }}" class="btn-secondary btn-sm">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-danger btn-sm">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary btn-sm">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-primary btn-sm">Daftar</a>
                    @endauth
                </div>

                {{-- Mobile Toggle --}}
                <button @click="open = !open" class="md:hidden p-2 rounded-lg text-gray-400 hover:text-white hover:bg-dark-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" x-transition class="md:hidden border-t border-dark-700 bg-dark-900 py-3 px-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-2 rounded-xl text-gray-400 hover:text-white hover:bg-dark-700">Beranda</a>
            <a href="{{ route('explore') }}" class="block px-4 py-2 rounded-xl text-gray-400 hover:text-white hover:bg-dark-700">Jelajahi</a>
            <a href="{{ route('map') }}" class="block px-4 py-2 rounded-xl text-gray-400 hover:text-white hover:bg-dark-700">Peta</a>
            <div class="pt-2 border-t border-dark-700 flex gap-2">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? '/admin' : '/dashboard' }}" class="btn-secondary btn-sm flex-1 justify-center">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-danger btn-sm w-full justify-center">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary btn-sm flex-1 justify-center">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary btn-sm flex-1 justify-center">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto w-full px-4 pt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto w-full px-4 pt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-dark-700 bg-dark-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-amber-600 rounded-lg flex items-center justify-center">
                            <span class="text-dark-900 font-black text-sm">BW</span>
                        </div>
                        <span class="font-bold text-white text-lg">BondoWisata</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">Sistem rekomendasi wisata resmi Kabupaten Bondowoso berdasarkan data Dinas Pariwisata. Temukan restoran, hotel, dan produk ekonomi kreatif terbaik.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Navigasi</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('explore') }}" class="hover:text-amber-400 transition-colors">Jelajahi</a></li>
                        <li><a href="{{ route('map') }}" class="hover:text-amber-400 transition-colors">Peta Wisata</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Kategori</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('explore', ['kategori' => 'restoran']) }}" class="hover:text-amber-400 transition-colors">Restoran</a></li>
                        <li><a href="{{ route('explore', ['kategori' => 'hotel']) }}" class="hover:text-amber-400 transition-colors">Hotel</a></li>
                        <li><a href="{{ route('explore', ['kategori' => 'ekraf']) }}" class="hover:text-amber-400 transition-colors">Ekonomi Kreatif</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-dark-700 mt-8 pt-8 text-center text-xs text-gray-500">
                © {{ date('Y') }} BondoWisata. Data resmi dari Dinas Pariwisata Kabupaten Bondowoso.
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
