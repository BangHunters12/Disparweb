@extends('layouts.dashboard')
@section('title', 'Profil Saya')
@section('page-title', 'Profil')

@section('content')
<div class="max-w-2xl">
    <div class="card p-8">
        <div class="flex items-center gap-5 mb-8 pb-8 border-b border-dark-700">
            @if($user->foto_profil)
                <img src="{{ Storage::url($user->foto_profil) }}" class="w-20 h-20 rounded-2xl object-cover ring-2 ring-amber-500/30">
            @else
                <div class="w-20 h-20 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-400 text-3xl font-black">
                    {{ mb_substr($user->nama_lengkap, 0, 1) }}
                </div>
            @endif
            <div>
                <h2 class="text-xl font-bold text-white">{{ $user->nama_lengkap }}</h2>
                <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                <span class="badge badge-amber mt-2 capitalize">{{ $user->role }}</span>
            </div>
        </div>

        <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required class="form-input @error('nama_lengkap') border-red-500 @enderror">
                @error('nama_lengkap') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input @error('email') border-red-500 @enderror">
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Foto Profil</label>
                <input type="file" name="foto_profil" accept="image/*" class="form-input py-2 text-gray-300 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:bg-amber-500/20 file:text-amber-400 file:font-medium file:cursor-pointer hover:file:bg-amber-500/30">
                @error('foto_profil') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Preferensi Wisata</label>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach(['restoran', 'hotel', 'ekraf', 'alam', 'budaya', 'kuliner'] as $pref)
                        @php $selected = is_array($user->preferensi) && in_array($pref, $user->preferensi ?? []); @endphp
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="preferensi[]" value="{{ $pref }}" {{ $selected ? 'checked' : '' }} class="rounded bg-dark-700 border-dark-600 text-amber-500">
                            <span class="text-sm text-gray-300 capitalize">{{ $pref }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-dark-700">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
