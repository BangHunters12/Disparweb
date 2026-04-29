<?php

namespace App\Observers;

use App\Jobs\AnalyzeSentimentJob;
use App\Models\Ulasan;

class UlasanObserver
{
    public function created(Ulasan $ulasan): void
    {
        if (! empty($ulasan->teks_ulasan)) {
            AnalyzeSentimentJob::dispatch($ulasan);
        }
    }

    public function updated(Ulasan $ulasan): void
    {
        if ($ulasan->isDirty('teks_ulasan') && ! empty($ulasan->teks_ulasan)) {
            AnalyzeSentimentJob::dispatch($ulasan);
        }
    }
}
