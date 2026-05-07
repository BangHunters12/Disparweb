<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\RekomendasiSaw;
use App\Models\Restoran;
use App\Models\Ulasan;

class HomeController extends Controller
{
    public function index()
    {
        $totalRestoran = Restoran::aktif()->count();
        $totalUlasan   = Ulasan::count();
        $avgRatingKota = Restoran::aktif()->where('avg_rating', '>', 0)->avg('avg_rating') ?? 0;

        // Top 6 by SAW score
        $topRestoran = Restoran::aktif()
            ->with(['kecamatan', 'rekomendasiSaw'])
            ->join('rekomendasi_saw', 'restoran.id', '=', 'rekomendasi_saw.restoran_id')
            ->orderByDesc('rekomendasi_saw.skor_saw_final')
            ->select('restoran.*')
            ->take(6)
            ->get();

        // Fallback: jika SAW belum dihitung, ambil by avg_rating
        if ($topRestoran->isEmpty()) {
            $topRestoran = Restoran::aktif()
                ->with(['kecamatan', 'rekomendasiSaw'])
                ->orderByDesc('avg_rating')
                ->take(6)
                ->get();
        }

        $kecamatanList = Kecamatan::orderBy('nama')->get();

        return view('public.home', compact(
            'totalRestoran',
            'totalUlasan',
            'avgRatingKota',
            'topRestoran',
            'kecamatanList'
        ));
    }
}
