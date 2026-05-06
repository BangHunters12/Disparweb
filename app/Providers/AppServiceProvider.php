<?php

namespace App\Providers;

use App\Models\Ulasan;
use App\Observers\UlasanObserver;
use App\Services\SawRecommendationService;
use App\Services\SentimentAnalysisService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SentimentAnalysisService::class);
        $this->app->singleton(SawRecommendationService::class);
    }

    public function boot(): void
    {
        Ulasan::observe(UlasanObserver::class);
    }
}
