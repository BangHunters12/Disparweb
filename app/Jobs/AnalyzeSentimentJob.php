<?php

namespace App\Jobs;

use App\Models\AnalisisSentimen;
use App\Models\Ulasan;
use App\Services\SentimentAnalysisService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnalyzeSentimentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Ulasan $ulasan
    ) {}

    public function handle(SentimentAnalysisService $service): void
    {
        if (empty($this->ulasan->teks_ulasan)) {
            return;
        }

        $result = $service->analyze($this->ulasan->teks_ulasan);

        AnalisisSentimen::updateOrCreate(
            ['ulasan_id' => $this->ulasan->id],
            [
                'label_sentimen' => $result['label_sentimen'],
                'skor_positif' => $result['skor_positif'],
                'skor_netral' => $result['skor_netral'],
                'skor_negatif' => $result['skor_negatif'],
                'metode' => $result['metode'],
                'kata_kunci' => $result['kata_kunci'],
                'diproses_at' => Carbon::now(),
            ]
        );
    }

    public int $tries = 3;

    public int $backoff = 30;
}
