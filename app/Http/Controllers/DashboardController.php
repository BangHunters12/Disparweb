<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeSentimentJob;
use App\Models\Favorit;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalUlasan = $user->ulasan()->count();
        $totalFavorit = $user->favorit()->count();
        $recentUlasan = $user->ulasan()->with('tempat.kategori')->latest()->take(5)->get();
        $recentFavorit = $user->favorit()->with('tempat.kategori')->latest()->take(5)->get();

        return view('dashboard.index', compact('user', 'totalUlasan', 'totalFavorit', 'recentUlasan', 'recentFavorit'));
    }

    public function profile()
    {
        return view('dashboard.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,'.$user->id,
            'foto_profil' => 'nullable|image|max:2048',
            'preferensi' => 'nullable|array',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')
                ->store('profil', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function ulasan()
    {
        $ulasan = Auth::user()->ulasan()
            ->with(['tempat.kategori', 'analisisSentimen'])
            ->latest()
            ->paginate(10);

        return view('dashboard.ulasan', compact('ulasan'));
    }

    public function favoritList()
    {
        $favorit = Auth::user()->favorit()
            ->with(['tempat.kategori', 'tempat.kecamatan'])
            ->latest()
            ->paginate(12);

        return view('dashboard.favorit', compact('favorit'));
    }

    public function toggleFavorit(Request $request, string $tempatId)
    {
        $user = Auth::user();
        $existing = Favorit::where('user_id', $user->id)->where('tempat_id', $tempatId)->first();

        if ($existing) {
            $existing->delete();

            return back()->with('success', 'Dihapus dari favorit.');
        }

        Favorit::create([
            'user_id' => $user->id,
            'tempat_id' => $tempatId,
        ]);

        return back()->with('success', 'Ditambahkan ke favorit!');
    }

    public function storeUlasan(Request $request)
    {
        $validated = $request->validate([
            'tempat_id' => 'required|exists:tempat,id',
            'rating' => 'required|numeric|min:1|max:5',
            'teks_ulasan' => 'required|string|min:10|max:2000',
            'tgl_kunjungan' => 'nullable|date|before_or_equal:today',
        ], [
            'teks_ulasan.required' => 'Ulasan harus diisi.',
            'teks_ulasan.min' => 'Ulasan minimal 10 karakter.',
            'rating.required' => 'Rating harus diisi.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['platform_sumber'] = 'app';

        $ulasan = Ulasan::create($validated);
        AnalyzeSentimentJob::dispatch($ulasan);

        return back()->with('success', 'Ulasan berhasil ditambahkan!');
    }

    public function updateUlasan(Request $request, string $id)
    {
        $ulasan = Ulasan::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'teks_ulasan' => 'required|string|min:10|max:2000',
        ]);

        $ulasan->update($validated);
        AnalyzeSentimentJob::dispatch($ulasan->fresh());

        return back()->with('success', 'Ulasan berhasil diperbarui!');
    }

    public function deleteUlasan(string $id)
    {
        $ulasan = Ulasan::where('user_id', Auth::id())->findOrFail($id);
        $ulasan->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
