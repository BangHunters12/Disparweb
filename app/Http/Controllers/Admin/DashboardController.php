<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportLog;
use App\Models\Restoran;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRestoran    = Restoran::aktif()->count();
        $totalUlasan      = Ulasan::count();
        $avgRatingKota    = Restoran::aktif()->where('avg_rating', '>', 0)->avg('avg_rating') ?? 0;
        $totalViewsHariIni = Restoran::whereDate('updated_at', today())->sum('total_views');

        $sentimentPositif = 0;
        $totalAnalyzed    = \App\Models\AnalisisSentimen::count();
        if ($totalAnalyzed > 0) {
            $sentimentPositif = round(
                \App\Models\AnalisisSentimen::where('label_sentimen', 'positif')->count() / $totalAnalyzed * 100
            );
        }

        $top1 = \App\Models\RekomendasiSaw::orderBy('peringkat')
            ->with('restoran')
            ->first()?->restoran?->nama_usaha ?? '-';

        // Chart data: ulasan per bulan (12 bulan terakhir)
        $ulasanPerBulan = Ulasan::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as bulan, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Chart data: distribusi sentimen
        $distribusiSentimen = \App\Models\AnalisisSentimen::selectRaw('label_sentimen, COUNT(*) as total')
            ->groupBy('label_sentimen')
            ->pluck('total', 'label_sentimen');

        // Chart data: top 10 restoran by SAW
        $top10Restoran = \App\Models\RekomendasiSaw::with('restoran')
            ->orderBy('peringkat')
            ->take(10)
            ->get();

        $recentImports  = ImportLog::with('admin')->latest()->take(5)->get();
        $recentUlasan   = Ulasan::with('restoran')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalRestoran', 'totalUlasan', 'avgRatingKota', 'totalViewsHariIni',
            'sentimentPositif', 'top1',
            'ulasanPerBulan', 'distribusiSentimen', 'top10Restoran',
            'recentImports', 'recentUlasan'
        ));
    }

    public function profil()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profil', compact('admin'));
    }

    public function updateProfil(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'nama'         => 'required|string|max:100',
            'email'        => 'required|email|unique:admins,email,' . $admin->id,
            'password'     => 'nullable|string|min:8|confirmed',
            'foto_profil'  => 'nullable|image|max:2048',
        ], [
            'nama.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan.',
        ]);

        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('admin', 'public');
        }

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
