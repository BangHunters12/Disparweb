<?php

namespace Tests\Unit\Services;

use App\Models\AnalisisSentimen;
use App\Models\Tempat;
use App\Models\Ulasan;
use App\Models\User;
use App\Services\SawRecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SawRecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SawRecommendationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(SawRecommendationService::class);
    }

    public function test_get_weights_returns_array_summing_to_one(): void
    {
        $weights = $this->service->getWeights();
        $sum = array_sum($weights);
        $this->assertEqualsWithDelta(1.0, $sum, 0.001);
    }

    public function test_get_weights_has_required_criteria(): void
    {
        $weights = $this->service->getWeights();
        $this->assertArrayHasKey('rating', $weights);
        $this->assertArrayHasKey('sentimen', $weights);
        $this->assertArrayHasKey('harga', $weights);
        $this->assertArrayHasKey('popularitas', $weights);
        $this->assertArrayHasKey('kebaruan', $weights);
    }

    public function test_update_weights_changes_values(): void
    {
        $newWeights = [
            'rating' => 0.5,
            'sentimen' => 0.2,
            'harga' => 0.1,
            'popularitas' => 0.1,
            'kebaruan' => 0.1,
        ];
        $this->service->updateWeights($newWeights);
        $this->assertEquals(0.5, $this->service->getWeights()['rating']);
    }

    public function test_recalculate_all_returns_zero_when_no_tempat(): void
    {
        $count = $this->service->recalculateAll();
        $this->assertEquals(0, $count);
    }

    public function test_calculate_saw_score_returns_float_between_zero_and_one(): void
    {
        $tempat = Tempat::factory()->create([
            'harga_min' => 10000,
            'harga_max' => 50000,
            'status' => 'aktif',
        ]);

        $score = $this->service->calculateSawScore($tempat);

        $this->assertIsFloat($score);
        $this->assertGreaterThanOrEqual(0, $score);
        $this->assertLessThanOrEqual(1, $score);
    }

    public function test_saw_score_higher_for_better_place(): void
    {
        $goodTempat = Tempat::factory()->create(['harga_min' => 10000, 'harga_max' => 30000, 'status' => 'aktif']);
        $badTempat = Tempat::factory()->create(['harga_min' => 500000, 'harga_max' => 1000000, 'status' => 'aktif']);

        // Add good reviews to goodTempat
        $user = User::factory()->create();
        for ($i = 0; $i < 5; $i++) {
            $ulasan = Ulasan::withoutEvents(fn () => Ulasan::factory()->create([
                'tempat_id' => $goodTempat->id,
                'user_id' => $user->id,
                'rating' => 5.0,
            ]));

            AnalisisSentimen::factory()->create(['ulasan_id' => $ulasan->id, 'label_sentimen' => 'positif']);
        }

        $goodScore = $this->service->calculateSawScore($goodTempat->fresh());
        $badScore = $this->service->calculateSawScore($badTempat->fresh());

        $this->assertGreaterThan($badScore, $goodScore);
    }
}
