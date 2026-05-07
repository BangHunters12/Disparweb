<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SawConfig;
use App\Jobs\RecalculateSawJob;
use App\Services\SawRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SawController extends Controller
{
    public function __construct(protected SawRecommendationService $sawService) {}

    public function index()
    {
        $config   = SawConfig::current();
        $hasil    = $this->sawService->getScoredList();
        $lastCalc = $hasil->first()?->dihitung_at;

        return view('admin.saw.index', compact('config', 'hasil', 'lastCalc'));
    }

    public function updateWeights(Request $request)
    {
        $data = $request->validate([
            'bobot_rating'      => 'required|numeric|min:0|max:100',
            'bobot_sentimen'    => 'required|numeric|min:0|max:100',
            'bobot_harga'       => 'required|numeric|min:0|max:100',
            'bobot_popularitas' => 'required|numeric|min:0|max:100',
            'bobot_kebaruan'    => 'required|numeric|min:0|max:100',
        ]);

        $total = array_sum($data);
        if (abs($total - 100) > 0.01) {
            return back()->withErrors(['total' => "Total bobot harus 100%. Sekarang: {$total}%"])->withInput();
        }

        SawConfig::create(array_merge($data, ['updated_by' => Auth::guard('admin')->id()]));

        // Langsung hitung ulang setelah bobot diubah
        $count = $this->sawService->calculateAll();

        return back()->with('success', "Bobot SAW disimpan dan dihitung ulang untuk {$count} restoran.");
    }

    public function recalculate()
    {
        try {
            $count = $this->sawService->calculateAll();
            return back()->with('success', "SAW berhasil dihitung untuk {$count} restoran.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghitung SAW: ' . $e->getMessage());
        }
    }

    public function exportPdf()
    {
        $hasil  = $this->sawService->getScoredList();
        $config = SawConfig::current();
        $pdf    = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.saw.pdf', compact('hasil', 'config'));
        return $pdf->download('saw-bondowisata-' . now()->format('Y-m-d') . '.pdf');
    }
}

