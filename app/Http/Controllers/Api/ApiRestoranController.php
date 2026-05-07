<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Restoran;
use Illuminate\Http\Request;

class ApiRestoranController extends Controller
{
    public function index(Request $request)
    {
        $query = Restoran::aktif()->with(['kecamatan', 'rekomendasiSaw']);

        if ($request->filled('search')) {
            $query->where('nama_usaha', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }
        if ($request->filled('harga_max')) {
            $query->where('harga_max', '<=', $request->harga_max);
        }
        if ($request->filled('rating_min')) {
            $query->where('avg_rating', '>=', $request->rating_min);
        }

        $sort = match ($request->get('sort', 'saw')) {
            'rating'     => fn($q) => $q->orderByDesc('avg_rating'),
            'harga_asc'  => fn($q) => $q->orderBy('harga_min'),
            'terbaru'    => fn($q) => $q->latest(),
            default      => fn($q) => $q->leftJoin('rekomendasi_saw', 'restoran.id', '=', 'rekomendasi_saw.restoran_id')
                ->orderByDesc('rekomendasi_saw.skor_saw_final')
                ->select('restoran.*'),
        };
        $sort($query);

        $data = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => $data->getCollection()->map(fn($r) => $this->formatRestoran($r)),
            'meta'    => [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'total'        => $data->total(),
                'per_page'     => $data->perPage(),
            ],
        ]);
    }

    public function show(string $slug)
    {
        $restoran = Restoran::aktif()
            ->with(['kecamatan', 'rekomendasiSaw'])
            ->where('slug', $slug)
            ->firstOrFail();

        $restoran->increment('total_views');

        $ulasan = $restoran->ulasanVisible()
            ->with('analisisSentimen')
            ->latest()
            ->take(10)
            ->get();

        $sentimenSummary = [
            'total'   => $restoran->ulasanVisible()->count(),
            'positif' => $restoran->ulasanVisible()->whereHas('analisisSentimen', fn($q) => $q->where('label_sentimen','positif'))->count(),
            'netral'  => $restoran->ulasanVisible()->whereHas('analisisSentimen', fn($q) => $q->where('label_sentimen','netral'))->count(),
            'negatif' => $restoran->ulasanVisible()->whereHas('analisisSentimen', fn($q) => $q->where('label_sentimen','negatif'))->count(),
        ];

        return response()->json([
            'success' => true,
            'data'    => array_merge($this->formatRestoran($restoran, true), [
                'ulasan'           => $ulasan->map(fn($u) => [
                    'id'             => $u->id,
                    'nama_reviewer'  => $u->nama_reviewer,
                    'foto_reviewer'  => $u->foto_reviewer,
                    'rating'         => $u->rating,
                    'teks_ulasan'    => $u->teks_ulasan,
                    'platform'       => $u->platform_sumber,
                    'tgl_kunjungan'  => $u->tgl_kunjungan?->toDateString(),
                    'sentimen'       => $u->analisisSentimen?->label_sentimen,
                ]),
                'sentimen_summary' => $sentimenSummary,
            ]),
        ]);
    }

    public function kecamatan()
    {
        $data = Kecamatan::withCount(['restoran' => fn($q) => $q->where('status', 'aktif')])->orderBy('nama')->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    protected function formatRestoran(Restoran $r, bool $full = false): array
    {
        $base = [
            'id'          => $r->id,
            'nama'        => $r->nama_usaha,
            'slug'        => $r->slug,
            'alamat'      => $r->alamat,
            'kecamatan'   => $r->kecamatan?->nama,
            'latitude'    => $r->latitude,
            'longitude'   => $r->longitude,
            'foto_utama'  => $r->foto_utama_url,
            'rating'      => $r->avg_rating,
            'total_ulasan'=> $r->total_ulasan,
            'harga_min'   => $r->harga_min,
            'harga_max'   => $r->harga_max,
            'harga_range' => $r->harga_range_text,
            'status'      => $r->status,
            'sumber'      => $r->sumber,
            'skor_saw'    => $r->rekomendasiSaw?->skor_saw_final,
            'peringkat'   => $r->rekomendasiSaw?->peringkat,
            'is_buka'     => $r->is_buka,
        ];

        if ($full) {
            $base = array_merge($base, [
                'deskripsi'    => $r->deskripsi,
                'no_telepon'   => $r->no_telepon,
                'website'      => $r->website,
                'gmaps_url'    => $r->gmaps_url,
                'fasilitas'    => $r->fasilitas ?? [],
                'foto_galeri'  => collect($r->foto_galeri ?? [])->map(fn($f) => asset('storage/'.$f))->toArray(),
                'jam_buka'     => $r->jam_buka ?? [],
                'total_views'  => $r->total_views,
            ]);
        }

        return $base;
    }
}
