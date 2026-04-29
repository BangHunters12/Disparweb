<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalisisSentimen;
use App\Models\RekomendasiSaw;
use App\Models\Tempat;
use App\Models\Ulasan;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tempat' => Tempat::count(),
            'total_aktif' => Tempat::aktif()->count(),
            'total_ulasan' => Ulasan::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_restoran' => Tempat::byKategori('restoran')->count(),
            'total_hotel' => Tempat::byKategori('hotel')->count(),
            'total_ekraf' => Tempat::byKategori('ekraf')->count(),
        ];

        $sentimenDist = [
            'positif' => AnalisisSentimen::where('label_sentimen', 'positif')->count(),
            'netral' => AnalisisSentimen::where('label_sentimen', 'netral')->count(),
            'negatif' => AnalisisSentimen::where('label_sentimen', 'negatif')->count(),
        ];

        $topSaw = RekomendasiSaw::whereNull('user_id')
            ->with(['tempat.kategori'])
            ->orderBy('peringkat')
            ->take(10)
            ->get();

        $recentUlasan = Ulasan::with(['user', 'tempat', 'analisisSentimen'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'sentimenDist', 'topSaw', 'recentUlasan'));
    }
}
