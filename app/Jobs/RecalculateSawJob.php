<?php

namespace App\Jobs;

use App\Services\SawRecommendationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RecalculateSawJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 2;
    public int $timeout = 120;

    public function __construct()
    {
        $this->onQueue('saw');
    }

    public function handle(SawRecommendationService $service): void
    {
        $count = $service->calculateAll();
        Log::info("SAW recalculated for {$count} restaurants.");
    }
}
