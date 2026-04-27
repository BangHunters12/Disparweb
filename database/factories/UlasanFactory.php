<?php

namespace Database\Factories;

use App\Models\Tempat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UlasanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tempat_id' => Tempat::factory(),
            'user_id' => User::factory(),
            'rating' => fake()->randomFloat(1, 1, 5),
            'teks_ulasan' => fake()->paragraph(),
            'platform_sumber' => 'app',
            'tgl_kunjungan' => fake()->dateTimeBetween('-6 months', 'now'),
            'helpful_count' => fake()->numberBetween(0, 20),
        ];
    }
}
