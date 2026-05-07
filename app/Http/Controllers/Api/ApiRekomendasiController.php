<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RekomendasiSaw;
use App\Models\Restoran;
use Illuminate\Http\Request;

class ApiRekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $limit = min($request->get('limit', 10), 50);

        $hasil = RekomendasiSaw::with(['restoran.kecamatan'])
            ->whereHas('restoran', fn($q) => $q->where('status', 'aktif'))
            ->orderBy('peringkat')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $hasil->map(fn($h) => [
                'peringkat'    => $h->peringkat,
                'restoran_id'  => $h->restoran_id,
                'nama'         => $h->restoran?->nama_usaha,
                'slug'         => $h->restoran?->slug,
                'kecamatan'    => $h->restoran?->kecamatan?->nama,
                'foto_utama'   => $h->restoran?->foto_utama_url,
                'rating'       => $h->restoran?->avg_rating,
                'harga_range'  => $h->restoran?->harga_range_text,
                'status'       => $h->restoran?->status,
                'skor'         => [
                    'final'       => $h->skor_saw_final,
                    'rating'      => $h->skor_rating,
                    'sentimen'    => $h->skor_sentimen,
                    'harga'       => $h->skor_harga,
                    'popularitas' => $h->skor_popularitas,
                    'kebaruan'    => $h->skor_kebaruan,
                ],
                'dihitung_at'  => $h->dihitung_at?->toDateTimeString(),
            ]),
        ]);
    }

    public function show(string $slug)
    {
        $restoran = Restoran::aktif()->with('rekomendasiSaw')->where('slug', $slug)->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => [
                'restoran_id' => $restoran->id,
                'nama'        => $restoran->nama_usaha,
                'slug'        => $restoran->slug,
                'peringkat'   => $restoran->rekomendasiSaw?->peringkat,
                'skor'        => $restoran->rekomendasiSaw ? [
                    'final'       => $restoran->rekomendasiSaw->skor_saw_final,
                    'rating'      => $restoran->rekomendasiSaw->skor_rating,
                    'sentimen'    => $restoran->rekomendasiSaw->skor_sentimen,
                    'harga'       => $restoran->rekomendasiSaw->skor_harga,
                    'popularitas' => $restoran->rekomendasiSaw->skor_popularitas,
                    'kebaruan'    => $restoran->rekomendasiSaw->skor_kebaruan,
                ] : null,
                'dihitung_at' => $restoran->rekomendasiSaw?->dihitung_at?->toDateTimeString(),
            ],
        ]);
    }
}
