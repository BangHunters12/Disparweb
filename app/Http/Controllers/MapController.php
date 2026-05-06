<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Tempat;

class MapController extends Controller
{
    public function index()
    {
        $tempat = Tempat::aktif()
            ->with('kategori')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'nama' => $t->nama_usaha,
                'lat' => (float) $t->latitude,
                'lng' => (float) $t->longitude,
                'kategori' => $t->kategori->jenis,
                'warna' => $t->kategori->warna,
                'icon' => $t->kategori->icon,
                'alamat' => $t->alamat,
                'foto' => $t->foto_utama,
            ]);

        $kategoriList = Kategori::all();

        return view('public.map', compact('tempat', 'kategoriList'));
    }
}
