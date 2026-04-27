<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekomendasiSaw;
use App\Services\SawRecommendationService;
use Illuminate\Http\Request;

class SawController extends Controller
{
    public function index()
    {
        $weights = config('saw.weights');

        $rankings = RekomendasiSaw::whereNull('user_id')
            ->with(['tempat.kategori', 'tempat.kecamatan'])
            ->orderBy('peringkat')
            ->paginate(20);

        $lastCalculated = RekomendasiSaw::whereNull('user_id')
            ->latest('dihitung_at')
            ->first()?->dihitung_at;

        return view('admin.saw.index', compact('weights', 'rankings', 'lastCalculated'));
    }

    public function recalculate(Request $request, SawRecommendationService $service)
    {
        $weights = $request->validate([
            'w_rating' => 'required|numeric|min:0|max:1',
            'w_sentimen' => 'required|numeric|min:0|max:1',
            'w_harga' => 'required|numeric|min:0|max:1',
            'w_popularitas' => 'required|numeric|min:0|max:1',
            'w_kebaruan' => 'required|numeric|min:0|max:1',
        ]);

        $total = $weights['w_rating'] + $weights['w_sentimen'] + $weights['w_harga']
            + $weights['w_popularitas'] + $weights['w_kebaruan'];

        if (abs($total - 1.0) > 0.01) {
            return back()->with('error', 'Total bobot harus sama dengan 1.0 (100%).');
        }

        $service->updateWeights([
            'rating' => $weights['w_rating'],
            'sentimen' => $weights['w_sentimen'],
            'harga' => $weights['w_harga'],
            'popularitas' => $weights['w_popularitas'],
            'kebaruan' => $weights['w_kebaruan'],
        ]);

        $count = $service->recalculateAll();

        return back()->with('success', "SAW berhasil dihitung ulang untuk {$count} tempat.");
    }
}
