<?php

namespace Database\Seeders;

use App\Jobs\RecalculateSawJob;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            KecamatanSeeder::class,
            SawConfigSeeder::class,
            RestoranSeeder::class,
            UlasanSeeder::class,
        ]);

        // Hitung SAW setelah semua data tersedia
        $this->call([]);
        $sawService = app(\App\Services\SawRecommendationService::class);
        $count = $sawService->calculateAll();
        $this->command->info("SAW calculated for {$count} restaurants.");
    }
}
