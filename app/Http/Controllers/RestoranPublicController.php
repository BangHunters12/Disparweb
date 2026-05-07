<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Restoran;
use Illuminate\Http\Request;

class RestoranPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Restoran::aktif()
            ->with(['kecamatan', 'rekomendasiSaw']);

        // Search
        if ($request->filled('search')) {
            $query->where('nama_usaha', 'like', '%' . $request->search . '%');
        }

        // Filter kecamatan
        if ($request->filled('kecamatan')) {
            $query->whereIn('kecamatan_id', (array) $request->kecamatan);
        }

        // Harga
        if ($request->filled('harga_min')) {
            $query->where('harga_min', '>=', $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $query->where('harga_max', '<=', $request->harga_max);
        }

        // Rating minimum
        if ($request->filled('rating_min')) {
            $query->where('avg_rating', '>=', $request->rating_min);
        }

        // Buka sekarang
        if ($request->boolean('buka_sekarang')) {
            $hari = strtolower(now()->locale('id')->isoFormat('dddd'));
            $jam  = now()->format('H:i');
            $query->whereRaw(
                "JSON_SEARCH(jam_buka, 'one', ?, NULL, '$[*].hari') IS NOT NULL",
                [$hari]
            );
        }

        // Sort
        $query = match ($request->get('sort', 'saw')) {
            'rating'     => $query->orderByDesc('restoran.avg_rating'),
            'harga_asc'  => $query->orderBy('restoran.harga_min'),
            'harga_desc' => $query->orderByDesc('restoran.harga_min'),
            'nama'       => $query->orderBy('restoran.nama_usaha'),
            'terbaru'    => $query->orderByDesc('restoran.created_at'),
            default      => $query->leftJoin('rekomendasi_saw', 'restoran.id', '=', 'rekomendasi_saw.restoran_id')
                ->orderByDesc('rekomendasi_saw.skor_saw_final')
                ->select('restoran.*'),
        };

        $restoran      = $query->paginate(12)->withQueryString();
        $kecamatanList = Kecamatan::orderBy('nama')->get();

        return view('public.restoran.index', compact('restoran', 'kecamatanList'));
    }

    public function show(string $slug)
    {
        $restoran = Restoran::aktif()
            ->with(['kecamatan', 'rekomendasiSaw'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views (session-based debounce)
        $sessionKey = "viewed_restoran_{$restoran->id}";
        if (! session()->has($sessionKey)) {
            $restoran->increment('total_views');
            session()->put($sessionKey, true);
        }

        // Ulasan visible (6 per page)
        $ulasan = $restoran->ulasanVisible()
            ->with('analisisSentimen')
            ->latest()
            ->paginate(6);

        // Sentiment summary
        $sentimentSummary = $this->getSentimentSummary($restoran);

        // Related (same kecamatan, sorted by SAW)
        $related = Restoran::aktif()
            ->where('restoran.kecamatan_id', $restoran->kecamatan_id)
            ->where('restoran.id', '!=', $restoran->id)
            ->with(['kecamatan', 'rekomendasiSaw'])
            ->leftJoin('rekomendasi_saw', 'restoran.id', '=', 'rekomendasi_saw.restoran_id')
            ->orderByDesc('rekomendasi_saw.skor_saw_final')
            ->select('restoran.*')
            ->take(4)
            ->get();

        return view('public.restoran.show', compact(
            'restoran',
            'ulasan',
            'sentimentSummary',
            'related'
        ));
    }

    private function getSentimentSummary(Restoran $restoran): array
    {
        $total    = $restoran->ulasanVisible()->count();
        $positif  = $restoran->ulasanVisible()->whereHas('analisisSentimen', fn($q) => $q->where('label_sentimen', 'positif'))->count();
        $netral   = $restoran->ulasanVisible()->whereHas('analisisSentimen', fn($q) => $q->where('label_sentimen', 'netral'))->count();
        $negatif  = $restoran->ulasanVisible()->whereHas('analisisSentimen', fn($q) => $q->where('label_sentimen', 'negatif'))->count();

        return [
            'total'          => $total,
            'positif'        => $positif,
            'netral'         => $netral,
            'negatif'        => $negatif,
            'pct_positif'    => $total > 0 ? round($positif / $total * 100) : 0,
            'pct_netral'     => $total > 0 ? round($netral / $total * 100) : 0,
            'pct_negatif'    => $total > 0 ? round($negatif / $total * 100) : 0,
        ];
    }
}
