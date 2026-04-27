@extends('layouts.auth')
@section('title', 'Daftar Akun')

@section('content')
<h2 class="text-2xl font-bold text-white mb-1">Buat Akun Baru</h2>
<p class="text-gray-400 text-sm mb-8">Bergabunglah dan temukan wisata terbaik Bondowoso</p>

@if($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl px-4 py-3 text-sm mb-5">
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('register') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
               class="form-input @error('nama_lengkap') border-red-500 @enderror" placeholder="Nama lengkap Anda">
        @error('nama_lengkap') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="form-input @error('email') border-red-500 @enderror" placeholder="nama@email.com">
        @error('email') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Password</label>
        <input type="password" name="password" required
               class="form-input @error('password') border-red-500 @enderror" placeholder="Min. 8 karakter">
        @error('password') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required
               class="form-input" placeholder="Ulangi password">
    </div>
    <button type="submit" class="btn-primary w-full justify-center py-3">Buat Akun</button>
</form>

<p class="text-center text-gray-400 text-sm mt-6">
    Sudah punya akun? <a href="{{ route('login') }}" class="text-amber-400 hover:text-amber-300 font-medium">Masuk</a>
</p>
@endsection
