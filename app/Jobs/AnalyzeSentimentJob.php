<?php

namespace App\Jobs;

use App\Models\Ulasan;
use App\Services\SentimentAnalysisService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnalyzeSentimentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 60;

    public function __construct(public Ulasan $ulasan)
    {
        $this->onQueue('sentiment');
    }

    public function handle(SentimentAnalysisService $service): void
    {
        if (empty($this->ulasan->teks_ulasan)) return;

        $service->analyzeAndSave($this->ulasan->id, $this->ulasan->teks_ulasan);

        // Update restoran avg_rating
        $restoran = $this->ulasan->restoran;
        if ($restoran) {
            $avg = $restoran->ulasanVisible()->avg('rating') ?? 0;
            $restoran->update([
                'avg_rating'   => round($avg, 2),
                'total_ulasan' => $restoran->ulasanVisible()->count(),
            ]);
        }
    }
}
