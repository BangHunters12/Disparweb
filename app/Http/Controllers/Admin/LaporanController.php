<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restoran;
use App\Models\Ulasan;
use App\Services\SawRecommendationService;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function exportPdf(Request $request)
    {
        $month  = $request->get('month', now()->month);
        $year   = $request->get('year', now()->year);

        $data = [
            'totalRestoran' => Restoran::aktif()->count(),
            'totalUlasan'   => Ulasan::whereMonth('created_at', $month)->whereYear('created_at', $year)->count(),
            'avgRating'     => Restoran::aktif()->avg('avg_rating') ?? 0,
            'topRestoran'   => (new SawRecommendationService())->getScoredList()->take(10),
            'bulan'         => $month,
            'tahun'         => $year,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', $data);
        return $pdf->download("laporan-bondowisata-{$year}-{$month}.pdf");
    }

    public function exportExcel()
    {
        return response()->json(['message' => 'Export Excel - implementasi dengan maatwebsite/excel']);
    }
}
