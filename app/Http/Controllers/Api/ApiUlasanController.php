<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\AnalyzeSentimentJob;
use App\Models\Favorit;
use App\Models\Restoran;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class ApiUlasanController extends Controller
{
    public function index(string $id)
    {
        $restoran = Restoran::aktif()->findOrFail($id);
        $ulasan = $restoran->ulasanVisible()->with('analisisSentimen')->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $ulasan->getCollection()->map(fn($u) => [
                'id'            => $u->id,
                'nama_reviewer' => $u->nama_reviewer,
                'foto_reviewer' => $u->foto_reviewer,
                'rating'        => $u->rating,
                'teks_ulasan'   => $u->teks_ulasan,
                'platform'      => $u->platform_sumber,
                'tgl_kunjungan' => $u->tgl_kunjungan?->toDateString(),
                'sentimen'      => $u->analisisSentimen?->label_sentimen,
                'skor_positif'  => $u->analisisSentimen?->skor_positif,
            ]),
            'meta' => ['total' => $ulasan->total(), 'last_page' => $ulasan->lastPage()],
        ]);
    }

    public function store(Request $request, string $id)
    {
        $restoran = Restoran::aktif()->findOrFail($id);

        $data = $request->validate([
            'nama_reviewer'  => 'required|string|max:100',
            'foto_reviewer'  => 'nullable|url',
            'rating'         => 'required|integer|min:1|max:5',
            'teks_ulasan'    => 'required|string|min:10|max:1000',
            'tgl_kunjungan'  => 'nullable|date',
        ], [
            'teks_ulasan.min'     => 'Ulasan minimal 10 karakter.',
            'teks_ulasan.required'=> 'Teks ulasan wajib diisi.',
        ]);

        $ulasan = Ulasan::create(array_merge($data, [
            'restoran_id'     => $restoran->id,
            'platform_sumber' => 'app',
            'is_visible'      => true,
        ]));

        AnalyzeSentimentJob::dispatch($ulasan);

        return response()->json(['success' => true, 'message' => 'Ulasan berhasil dikirim.', 'data' => ['id' => $ulasan->id]], 201);
    }

    public function toggleFavorit(Request $request)
    {
        $request->validate([
            'restoran_id' => 'required|exists:restoran,id',
            'device_id'   => 'required|string|max:100',
        ]);

        $existing = Favorit::where('restoran_id', $request->restoran_id)
            ->where('device_id', $request->device_id)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['success' => true, 'favorit' => false]);
        }

        Favorit::create(['restoran_id' => $request->restoran_id, 'device_id' => $request->device_id]);
        return response()->json(['success' => true, 'favorit' => true]);
    }

    public function favorit(string $deviceId)
    {
        $favorit = Favorit::where('device_id', $deviceId)
            ->with(['restoran' => fn($q) => $q->with(['kecamatan', 'rekomendasiSaw'])])
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $favorit->map(fn($f) => [
                'restoran_id'  => $f->restoran_id,
                'nama'         => $f->restoran?->nama_usaha,
                'slug'         => $f->restoran?->slug,
                'foto_utama'   => $f->restoran?->foto_utama_url,
                'rating'       => $f->restoran?->avg_rating,
                'skor_saw'     => $f->restoran?->rekomendasiSaw?->skor_saw_final,
            ]),
        ]);
    }
}
