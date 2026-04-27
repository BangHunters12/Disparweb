@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<h2 class="text-2xl font-bold text-white mb-1">Selamat Datang!</h2>
<p class="text-gray-400 text-sm mb-8">Masuk untuk mengakses fitur lengkap BondoWisata</p>

@if($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl px-4 py-3 text-sm mb-5">
        {{ $errors->first() }}
    </div>
@endif

<form action="{{ route('login') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
               class="form-input @error('email') border-red-500 @enderror" placeholder="nama@email.com">
        @error('email') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Password</label>
        <input type="password" name="password" required autocomplete="current-password"
               class="form-input @error('password') border-red-500 @enderror" placeholder="••••••••">
    </div>
    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 text-sm text-gray-400">
            <input type="checkbox" name="remember" class="rounded bg-dark-700 border-dark-600 text-amber-500">
            Ingat saya
        </label>
    </div>
    <button type="submit" class="btn-primary w-full justify-center py-3">Masuk</button>
</form>

<div class="relative my-6">
    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-dark-700"></div></div>
    <div class="relative flex justify-center text-xs text-gray-500 bg-dark-800 px-2">atau masuk dengan</div>
</div>

<a href="{{ route('google.redirect') }}"
   class="flex items-center justify-center gap-3 w-full btn-secondary py-3">
    <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#ea4335" d="M5.26620003,9.76452941 C6.19878754,6.93863203 8.85444915,4.90909091 12,4.90909091 C13.6909091,4.90909091 15.2181818,5.50909091 16.4181818,6.49090909 L19.9090909,3 C17.7818182,1.14545455 15.0545455,0 12,0 C7.27006974,0 3.1977497,2.69829785 1.23999023,6.65002441 L5.26620003,9.76452941 Z"/><path fill="#34a853" d="M16.0407269,18.0125889 C14.9509167,18.7163016 13.5660892,19.0909091 12,19.0909091 C8.86648613,19.0909091 6.21911939,17.076871 5.27698177,14.2678769 L1.23746264,17.3349879 C3.19279051,21.2970142 7.26500293,24 12,24 C14.9328362,24 17.7353462,22.9573905 19.834192,20.9995801 L16.0407269,18.0125889 Z"/><path fill="#4a90e2" d="M19.834192,20.9995801 C22.0291676,18.9520994 23.4545455,15.903663 23.4545455,12 C23.4545455,11.2909091 23.3454545,10.5272727 23.1818182,9.81818182 L12,9.81818182 L12,14.4545455 L18.4363636,14.4545455 C18.1187732,16.013626 17.2662994,17.2212117 16.0407269,18.0125889 L19.834192,20.9995801 Z"/><path fill="#fbbc05" d="M5.27698177,14.2678769 C5.03832634,13.556323 4.90909091,12.7937589 4.90909091,12 C4.90909091,11.2182781 5.03443647,10.4668121 5.26620003,9.76452941 L1.23999023,6.65002441 C0.43658717,8.26043162 0,10.0753848 0,12 C0,13.9159572 0.444780743,15.7350653 1.23746264,17.3349879 L5.27698177,14.2678769 Z"/></svg>
    Masuk dengan Google
</a>

<p class="text-center text-gray-400 text-sm mt-6">
    Belum punya akun? <a href="{{ route('register') }}" class="text-amber-400 hover:text-amber-300 font-medium">Daftar sekarang</a>
</p>
@endsection
