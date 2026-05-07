<?php

namespace App\Providers;

use App\Models\Ulasan;
use App\Observers\UlasanObserver;
use App\Services\SawRecommendationService;
use App\Services\SentimentAnalysisService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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

        // Default pagination view
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');

        // Str tersedia di Blade
        Blade::directive('str', fn($expression) => "<?php echo Str::$expression; ?>");
    }
}
