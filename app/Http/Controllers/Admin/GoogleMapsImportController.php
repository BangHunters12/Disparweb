<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportLog;
use App\Services\GooglePlacesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleMapsImportController extends Controller
{
    public function __construct(protected GooglePlacesService $gmaps) {}

    public function index()
    {
        $recentLogs = ImportLog::where('jenis', 'gmaps')
            ->with('admin')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.restoran.import-gmaps', compact('recentLogs'));
    }

    public function search(Request $request)
    {
        $request->validate(['keyword' => 'required|string|min:3|max:100']);

        try {
            $results  = $this->gmaps->searchRestaurants($request->keyword);
            $preview  = $this->gmaps->buildImportPreview($results);
            return response()->json(['success' => true, 'data' => $preview, 'total' => count($preview)]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'place_ids'   => 'required|array|min:1|max:20',
            'place_ids.*' => 'required|string',
        ]);

        $berhasil = 0;
        $gagal    = 0;
        $detail   = [];

        foreach ($request->place_ids as $placeId) {
            try {
                $restoran = $this->gmaps->importPlace(
                    ['place_id' => $placeId],
                    Auth::guard('admin')->id()
                );
                if ($restoran) {
                    $berhasil++;
                    $detail[] = ['place_id' => $placeId, 'nama' => $restoran->nama_usaha, 'status' => 'berhasil'];
                }
            } catch (\Throwable $e) {
                $gagal++;
                $detail[] = ['place_id' => $placeId, 'status' => 'gagal', 'error' => $e->getMessage()];
            }
        }

        ImportLog::create([
            'admin_id'        => Auth::guard('admin')->id(),
            'jenis'           => 'gmaps',
            'jumlah_berhasil' => $berhasil,
            'jumlah_gagal'    => $gagal,
            'detail'          => $detail,
        ]);

        return response()->json([
            'success'    => true,
            'berhasil'   => $berhasil,
            'gagal'      => $gagal,
            'message'    => "Import selesai: {$berhasil} berhasil, {$gagal} gagal.",
        ]);
    }
}
