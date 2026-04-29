<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\TempatController;
use App\Http\Controllers\Controller;
use App\Models\AnalisisSentimen;
use App\Models\Favorit;
use App\Services\SawRecommendationService;
use Illuminate\Http\Request;

class ApiMiscController extends Controller
{
    // Favorit
    public function favoritIndex(Request $request)
    {
        $favorit = $request->user()->favorit()
            ->with(['tempat.kategori', 'tempat.kecamatan'])
            ->latest()
            ->paginate(15);

        return response()->json(['success' => true, 'data' => $favorit]);
    }

    public function favoritStore(Request $request, string $tempatId)
    {
        $existing = Favorit::where('user_id', $request->user()->id)
            ->where('tempat_id', $tempatId)->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Sudah ada di favorit.'], 409);
        }

        Favorit::create([
            'user_id' => $request->user()->id,
            'tempat_id' => $tempatId,
        ]);

        return response()->json(['success' => true, 'message' => 'Ditambahkan ke favorit.'], 201);
    }

    public function favoritDestroy(Request $request, string $tempatId)
    {
        Favorit::where('user_id', $request->user()->id)
            ->where('tempat_id', $tempatId)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Dihapus dari favorit.']);
    }

    // Profile
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->only(['id', 'nama_lengkap', 'email', 'role', 'foto_profil', 'preferensi']),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'sometimes|string|max:100',
            'preferensi' => 'nullable|array',
        ]);

        $request->user()->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $request->user()->fresh(),
        ]);
    }

    // Admin endpoints
    public function sentimenSummary()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'positif' => AnalisisSentimen::where('label_sentimen', 'positif')->count(),
                'netral' => AnalisisSentimen::where('label_sentimen', 'netral')->count(),
                'negatif' => AnalisisSentimen::where('label_sentimen', 'negatif')->count(),
            ],
        ]);
    }

    public function sentimenKeywords()
    {
        $keywords = AnalisisSentimen::whereNotNull('kata_kunci')
            ->pluck('kata_kunci')
            ->flatten()
            ->countBy()
            ->sortDesc()
            ->take(50)
            ->toArray();

        return response()->json(['success' => true, 'data' => $keywords]);
    }

    public function sawRecalculate(SawRecommendationService $service)
    {
        $count = $service->recalculateAll();

        return response()->json([
            'success' => true,
            'message' => "SAW dihitung ulang untuk {$count} tempat.",
        ]);
    }

    public function tempatImportCsv(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);

        // Delegate to admin controller logic
        $controller = app(TempatController::class);

        return $controller->importCsv($request);
    }
}
