<?php

namespace App\Http\Controllers;

use App\Models\Tempat;
use App\Models\Kategori;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $kategoriList = Kategori::all();
        $kecamatanList = Kecamatan::orderBy('nama')->get();

        $query = Tempat::aktif()->with(['kategori', 'kecamatan', 'ulasan']);

        if ($request->filled('search')) {
            $query->where('nama_usaha', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', fn($q) => $q->where('jenis', $request->kategori));
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

        $sortBy = $request->get('sort_by', 'terbaru');
        $query = match ($sortBy) {
            'rating' => $query->withAvg('ulasan', 'rating')->orderByDesc('ulasan_avg_rating'),
            'harga_asc' => $query->orderBy('harga_min'),
            'harga_desc' => $query->orderByDesc('harga_min'),
            'nama' => $query->orderBy('nama_usaha'),
            'saw' => $query->leftJoin('rekomendasi_saw', function ($join) {
                $join->on('tempat.id', '=', 'rekomendasi_saw.tempat_id')
                    ->whereNull('rekomendasi_saw.user_id');
            })->orderByDesc('rekomendasi_saw.skor_saw_final')->select('tempat.*'),
            default => $query->latest(),
        };

        $tempat = $query->paginate(12)->appends($request->query());

        return view('public.explore', compact('tempat', 'kategoriList', 'kecamatanList'));
    }

    public function show(string $id)
    {
        $tempat = Tempat::with([
            'kategori',
            'kecamatan',
            'ulasan' => fn($q) => $q->with(['user', 'analisisSentimen'])->latest()->take(20),
        ])->findOrFail($id);

        $similar = Tempat::aktif()
            ->where('id', '!=', $tempat->id)
            ->where('kategori_id', $tempat->kategori_id)
            ->with(['kategori', 'kecamatan'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        $rataRating = $tempat->ulasan()->avg('rating') ?? 0;
        $totalUlasan = $tempat->ulasan()->count();
        $distribusiRating = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribusiRating[$i] = $tempat->ulasan()->where('rating', '>=', $i)->where('rating', '<', $i + 1)->count();
        }

        return view('public.detail', compact('tempat', 'similar', 'rataRating', 'totalUlasan', 'distribusiRating'));
    }
}
