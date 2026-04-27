<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KecamatanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->city(),
            'kode_pos' => fake()->postcode(),
            'lat_center' => fake()->latitude(-8.1, -7.8),
            'lng_center' => fake()->longitude(113.7, 114.0),
        ];
    }
}
