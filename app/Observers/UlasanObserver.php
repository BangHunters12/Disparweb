<?php

namespace App\Observers;

use App\Jobs\AnalyzeSentimentJob;
use App\Models\Ulasan;

class UlasanObserver
{
    public function created(Ulasan $ulasan): void
    {
        AnalyzeSentimentJob::dispatch($ulasan);
    }

    public function deleted(Ulasan $ulasan): void
    {
        $restoran = $ulasan->restoran;
        if ($restoran) {
            $avg = $restoran->ulasanVisible()->avg('rating') ?? 0;
            $restoran->update([
                'avg_rating'   => round($avg, 2),
                'total_ulasan' => $restoran->ulasanVisible()->count(),
            ]);
        }
    }
}
