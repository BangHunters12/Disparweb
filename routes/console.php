<?php

use App\Services\SawRecommendationService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    app(SawRecommendationService::class)->recalculateAll();
})->daily()->name('saw-recalculate')->withoutOverlapping();
