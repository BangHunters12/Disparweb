<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\RekomendasiSaw;
use App\Models\Tempat;
use Illuminate\Http\Request;

class ApiTempatController extends Controller
{
    public function index(Request $request)
    {
        $query = Tempat::aktif()->with(['kategori', 'kecamatan']);

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', fn ($q) => $q->where('jenis', $request->kategori));
        }
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }
        if ($request->filled('harga_min')) {
            $query->where('harga_min', '>=', $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $query->where('harga_max', '<=', $request->harga_max);
        }
        if ($request->filled('rating_min')) {
            $query->withAvg('ulasan', 'rating')
                ->having('ulasan_avg_rating', '>=', $request->rating_min);
        }
        if ($request->filled('search')) {
            $query->where('nama_usaha', 'like', '%'.$request->search.'%');
        }

        $sortBy = $request->get('sort_by', 'terbaru');
        $query = match ($sortBy) {
            'rating' => $query->withAvg('ulasan', 'rating')->orderByDesc('ulasan_avg_rating'),
            'harga_asc' => $query->orderBy('harga_min'),
            'harga_desc' => $query->orderByDesc('harga_min'),
            default => $query->latest(),
        };

        $perPage = min($request->get('per_page', 15), 50);
        $tempat = $query->withCount('ulasan')->withAvg('ulasan', 'rating')->paginate($perPage);

        $tempat->getCollection()->transform(function ($t) {
            $t->rata_rating = round($t->ulasan_avg_rating ?? 0, 1);
            $t->total_ulasan = $t->ulasan_count ?? 0;
            return $t;
        });

        return response()->json([
            'success' => true,
            'data' => $tempat->items(),
            'meta' => [
                'total' => $tempat->total(),
                'last_page' => $tempat->lastPage(),
                'current_page' => $tempat->currentPage(),
            ],
        ]);
    }

    public function show(string $id)
    {
        $tempat = Tempat::aktif()->with([
            'kategori',
            'kecamatan',
            'ulasan' => fn ($q) => $q->with(['user:id,nama_lengkap,foto_profil', 'analisisSentimen'])->latest(),
        ])->findOrFail($id);

        $tempat->rata_rating = $tempat->ulasan->avg('rating') ?? 0;
        $tempat->total_ulasan = $tempat->ulasan->count();

        return response()->json([
            'success' => true,
            'data' => $tempat,
        ]);
    }

    public function rekomendasi(Request $request)
    {
        $query = RekomendasiSaw::whereNull('user_id')
            ->whereHas('tempat', fn ($q) => $q->aktif())
            ->with(['tempat.kategori', 'tempat.kecamatan'])
            ->orderBy('peringkat');

        if ($request->filled('kategori')) {
            $query->whereHas('tempat.kategori', fn ($q) => $q->where('jenis', $request->kategori));
        }

        $limit = min($request->get('limit', $request->get('per_page', 15)), 50);
        $results = $query->take($limit)->get();

        $data = $results->map(function ($r) {
            $t = $r->tempat;
            if (!$t) return null;
            $t->rata_rating = round($t->ulasan()->avg('rating') ?? 0, 1);
            $t->total_ulasan = $t->ulasan()->count();
            $t->skor_saw = $r->skor_saw_final;
            $t->peringkat = $r->peringkat;
            return $t;
        })->filter()->values();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function kecamatan()
    {
        return response()->json([
            'success' => true,
            'data' => Kecamatan::orderBy('nama')->get(),
        ]);
    }

    public function kategori()
    {
        return response()->json([
            'success' => true,
            'data' => Kategori::all(),
        ]);
    }
}
