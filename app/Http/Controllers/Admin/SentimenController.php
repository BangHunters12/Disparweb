<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\AnalyzeSentimentJob;
use App\Models\AnalisisSentimen;
use App\Models\Ulasan;

class SentimenController extends Controller
{
    public function index()
    {
        $distribusi = [
            'positif' => AnalisisSentimen::where('label_sentimen', 'positif')->count(),
            'netral' => AnalisisSentimen::where('label_sentimen', 'netral')->count(),
            'negatif' => AnalisisSentimen::where('label_sentimen', 'negatif')->count(),
        ];

        // Keyword cloud data
        $allKeywords = AnalisisSentimen::whereNotNull('kata_kunci')
            ->pluck('kata_kunci')
            ->flatten()
            ->countBy()
            ->sortDesc()
            ->take(50)
            ->toArray();

        $ulasan = Ulasan::with(['tempat', 'user', 'analisisSentimen'])
            ->latest()
            ->paginate(20);

        return view('admin.sentimen.index', compact('distribusi', 'allKeywords', 'ulasan'));
    }

    public function reanalyze(string $ulasanId)
    {
        $ulasan = Ulasan::findOrFail($ulasanId);
        AnalyzeSentimentJob::dispatch($ulasan);

        return back()->with('success', 'Analisis sentimen dijadwalkan ulang.');
    }

    public function reanalyzeAll()
    {
        $ulasan = Ulasan::whereNotNull('teks_ulasan')->get();

        foreach ($ulasan as $u) {
            AnalyzeSentimentJob::dispatch($u);
        }

        return back()->with('success', 'Analisis sentimen untuk '.$ulasan->count().' ulasan dijadwalkan.');
    }
}
