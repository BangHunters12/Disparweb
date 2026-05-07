<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalisisSentimen;
use App\Models\Ulasan;
use App\Services\SentimentAnalysisService;
use Illuminate\Support\Facades\DB;

class SentimenController extends Controller
{
    public function index()
    {
        $total     = AnalisisSentimen::count();
        $positif   = AnalisisSentimen::where('label_sentimen', 'positif')->count();
        $netral    = AnalisisSentimen::where('label_sentimen', 'netral')->count();
        $negatif   = AnalisisSentimen::where('label_sentimen', 'negatif')->count();

        $pctPositif = $total > 0 ? round($positif / $total * 100, 1) : 0;
        $pctNetral  = $total > 0 ? round($netral  / $total * 100, 1) : 0;
        $pctNegatif = $total > 0 ? round($negatif / $total * 100, 1) : 0;

        // Trend per bulan (6 bulan)
        $trend = AnalisisSentimen::selectRaw("
                DATE_FORMAT(diproses_at, '%Y-%m') as bulan,
                label_sentimen,
                COUNT(*) as total
            ")
            ->where('diproses_at', '>=', now()->subMonths(6))
            ->groupBy('bulan', 'label_sentimen')
            ->orderBy('bulan')
            ->get()
            ->groupBy('bulan');

        // Per restoran breakdown
        $perRestoran = DB::table('analisis_sentimen')
            ->join('ulasan', 'analisis_sentimen.ulasan_id', '=', 'ulasan.id')
            ->join('restoran', 'ulasan.restoran_id', '=', 'restoran.id')
            ->selectRaw('restoran.id, restoran.nama_usaha,
                COUNT(*) as total,
                SUM(label_sentimen = "positif") as positif,
                SUM(label_sentimen = "netral") as netral,
                SUM(label_sentimen = "negatif") as negatif')
            ->groupBy('restoran.id', 'restoran.nama_usaha')
            ->orderByDesc('positif')
            ->limit(20)
            ->get();

        // Top keywords positif
        $allKataKunci = AnalisisSentimen::whereNotNull('kata_kunci')->pluck('kata_kunci');
        $posKeywords  = [];
        $negKeywords  = [];
        foreach ($allKataKunci as $kk) {
            foreach ($kk['positif'] ?? [] as $w) $posKeywords[$w] = ($posKeywords[$w] ?? 0) + 1;
            foreach ($kk['negatif'] ?? [] as $w) $negKeywords[$w] = ($negKeywords[$w] ?? 0) + 1;
        }
        arsort($posKeywords); arsort($negKeywords);
        $posKeywords = array_slice($posKeywords, 0, 20, true);
        $negKeywords = array_slice($negKeywords, 0, 20, true);

        $belumDianalisis = Ulasan::whereDoesntHave('analisisSentimen')
            ->whereNotNull('teks_ulasan')
            ->count();

        return view('admin.sentimen.index', compact(
            'total', 'positif', 'netral', 'negatif',
            'pctPositif', 'pctNetral', 'pctNegatif',
            'trend', 'perRestoran', 'posKeywords', 'negKeywords',
            'belumDianalisis'
        ));
    }

    public function analyzeAll(SentimentAnalysisService $service)
    {
        $ulasan = Ulasan::whereDoesntHave('analisisSentimen')
            ->whereNotNull('teks_ulasan')
            ->get();

        $berhasil = 0;
        foreach ($ulasan as $u) {
            try {
                $service->analyzeAndSave($u->id, $u->teks_ulasan);
                $berhasil++;
            } catch (\Throwable) {}
        }

        // Update avg_rating semua restoran yang terdampak
        $restoranIds = $ulasan->pluck('restoran_id')->unique();
        foreach ($restoranIds as $rid) {
            $restoran = \App\Models\Restoran::find($rid);
            if ($restoran) {
                $avg = $restoran->ulasanVisible()->avg('rating') ?? 0;
                $restoran->update([
                    'avg_rating'   => round($avg, 2),
                    'total_ulasan' => $restoran->ulasanVisible()->count(),
                ]);
            }
        }

        return back()->with('success', "✅ {$berhasil} ulasan berhasil dianalisis sentimen-nya.");
    }
}
