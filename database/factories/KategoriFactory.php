<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->word().' Category',
            'jenis' => fake()->randomElement(['restoran', 'hotel', 'ekraf']),
            'icon' => 'star',
            'warna' => '#f59e0b',
            'deskripsi' => fake()->sentence(),
        ];
    }
}
