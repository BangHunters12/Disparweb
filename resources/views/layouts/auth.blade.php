<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — BondoWisata</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-900 min-h-screen flex items-center justify-center p-4" x-data>
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 justify-center">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center">
                    <span class="text-dark-900 font-black">BW</span>
                </div>
                <span class="font-bold text-white text-xl">Bondo<span class="text-gradient">Wisata</span></span>
            </a>
        </div>
        <div class="card p-8">
            @yield('content')
        </div>
        <p class="text-center text-gray-500 text-xs mt-6">© {{ date('Y') }} BondoWisata. Dinas Pariwisata Bondowoso.</p>
    </div>
</body>
</html>
