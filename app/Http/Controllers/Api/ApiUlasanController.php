<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class ApiUlasanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tempat_id' => 'required|exists:tempat,id',
            'rating' => 'required|numeric|min:1|max:5',
            'teks_ulasan' => 'required|string|min:10|max:2000',
            'tgl_kunjungan' => 'nullable|date|before_or_equal:today',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['platform_sumber'] = 'app';

        $ulasan = Ulasan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil ditambahkan.',
            'data' => $ulasan->load(['user:id,nama_lengkap', 'analisisSentimen']),
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $ulasan = Ulasan::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'teks_ulasan' => 'required|string|min:10|max:2000',
        ]);

        $ulasan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil diperbarui.',
            'data' => $ulasan->fresh(['analisisSentimen']),
        ]);
    }

    public function destroy(Request $request, string $id)
    {
        $ulasan = Ulasan::where('user_id', $request->user()->id)->findOrFail($id);
        $ulasan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dihapus.',
        ]);
    }
}
