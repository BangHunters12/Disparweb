<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — BondoWisata</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0f1117] flex items-center justify-center min-h-screen p-4">
<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-amber-500 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-[#0f1117]" fill="currentColor" viewBox="0 0 24 24"><path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V3h-1.97v2.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v1.06h-2zm-3.66-4.29c0 .33-.27.6-.6.6-.33 0-.6-.27-.6-.6V14.6c0-.33.27-.6.6-.6.33 0 .6.27.6.6v4.1zM3 21.99V23h15v-1.01c0-2.76-2.24-5-5-5H8c-2.76 0-5 2.24-5 5zm0-6.94c0 1.66 1.34 3 3 3s3-1.34 3-3-1.34-3-3-3-3 1.34-3 3z"/></svg>
        </div>
        <h1 class="text-2xl font-black text-white">BondoWisata Admin</h1>
        <p class="text-gray-500 text-sm mt-1">Panel manajemen kuliner Bondowoso</p>
    </div>

    <div class="bg-[#1a1f2e] border border-[#2d3548] rounded-2xl p-8">
        @if(session('error'))
        <div class="mb-4 p-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-300 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="admin@bondowisata.id"
                       class="w-full px-4 py-3 bg-[#0f1117] border border-[#2d3548] text-white placeholder-gray-600 rounded-xl text-sm focus:outline-none focus:border-amber-500 @error('email') border-red-500 @enderror">
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-300 mb-2">Kata Sandi</label>
                <input type="password" name="password" required
                       placeholder="••••••••"
                       class="w-full px-4 py-3 bg-[#0f1117] border border-[#2d3548] text-white placeholder-gray-600 rounded-xl text-sm focus:outline-none focus:border-amber-500 @error('password') border-red-500 @enderror">
                @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <label class="flex items-center gap-2 mb-6 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 accent-amber-500">
                <span class="text-sm text-gray-400">Ingat saya</span>
            </label>
            <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-400 text-[#0f1117] font-black rounded-xl transition-all hover:scale-[1.02] text-sm">
                Masuk ke Panel Admin
            </button>
        </form>
    </div>

    <p class="text-center mt-6 text-xs text-gray-600">
        <a href="{{ route('home') }}" class="hover:text-gray-400 transition-colors">← Kembali ke website</a>
    </p>
</div>
</body>
</html>
