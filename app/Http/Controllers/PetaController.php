<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Restoran;

class PetaController extends Controller
{
    public function index()
    {
        $restoran = Restoran::aktif()
            ->with(['kecamatan', 'rekomendasiSaw'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'nama_usaha', 'slug', 'alamat', 'latitude', 'longitude',
                'foto_utama', 'avg_rating', 'harga_min', 'harga_max', 'status', 'kecamatan_id']);

        $kecamatanList = Kecamatan::orderBy('nama')->get();

        $mapsKey = config('google.maps_key');

        // Transform untuk JavaScript — hindari closure di @json Blade
        $restoranJson = $restoran->map(fn($r) => [
            'id'           => $r->id,
            'nama'         => $r->nama_usaha,
            'slug'         => $r->slug,
            'alamat'       => $r->alamat,
            'lat'          => (float) $r->latitude,
            'lng'          => (float) $r->longitude,
            'rating'       => (float) $r->avg_rating,
            'harga'        => $r->harga_range_text,
            'foto'         => $r->foto_utama_url,
            'status'       => $r->status,
            'kecamatan_id' => $r->kecamatan_id,
        ])->values();

        return view('public.peta', compact('restoran', 'restoranJson', 'kecamatanList', 'mapsKey'));
    }
}
