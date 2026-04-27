<?php

namespace App\Http\Controllers;

use App\Models\Tempat;
use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Ulasan;
use App\Models\RekomendasiSaw;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalTempat = Tempat::aktif()->count();
        $totalUlasan = Ulasan::count();
        $totalKecamatan = Kecamatan::count();
        $kategoriList = Kategori::withCount(['tempat' => fn($q) => $q->where('status', 'aktif')])->get();

        $topRekomendasi = RekomendasiSaw::whereNull('user_id')
            ->with(['tempat.kategori', 'tempat.kecamatan'])
            ->orderBy('peringkat')
            ->take(6)
            ->get();

        $tempatTerbaru = Tempat::aktif()
            ->with(['kategori', 'kecamatan'])
            ->latest()
            ->take(4)
            ->get();

        return view('public.home', compact(
            'totalTempat',
            'totalUlasan',
            'totalKecamatan',
            'kategoriList',
            'topRekomendasi',
            'tempatTerbaru'
        ));
    }
}
