<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use App\Models\AnalisisSentimen;
use App\Jobs\AnalyzeSentimentJob;
use Illuminate\Http\Request;

class SentimenController extends Controller
{
    public function index()
    {
        $distribusi = [
            'positif' => AnalisisSentimen::where('label_sentimen', 'positif')->count(),
            'netral' => AnalisisSentimen::where('label_sentimen', 'netral')->count(),
            'negatif' => AnalisisSentimen::where('label_sentimen', 'negatif')->count(),
        ];

        // Keyword cloud — kata_kunci disimpan sebagai JSON array
        $allKeywords = AnalisisSentimen::whereNotNull('kata_kunci')
            ->get()
            ->flatMap(function ($a) {
                $kw = $a->kata_kunci;
                if (is_string($kw)) {
                    $kw = json_decode($kw, true) ?? [];
                }
                return is_array($kw) ? $kw : [];
            })
            ->filter()
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

        // Jalankan langsung (synchronous) tanpa queue worker
        AnalyzeSentimentJob::dispatchSync($ulasan);

        return back()->with('success', 'Analisis sentimen berhasil diperbarui.');
    }

    public function reanalyzeAll()
    {
        $ulasan = Ulasan::whereNotNull('teks_ulasan')->get();

        $count = 0;
        foreach ($ulasan as $u) {
            // Jalankan langsung tanpa queue agar hasilnya langsung terlihat
            AnalyzeSentimentJob::dispatchSync($u);
            $count++;
        }

        return back()->with('success', "Analisis sentimen selesai untuk {$count} ulasan.");
    }
}
