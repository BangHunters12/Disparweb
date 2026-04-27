<?php

namespace Database\Factories;

use App\Models\Ulasan;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalisisSentimenFactory extends Factory
{
    public function definition(): array
    {
        $label = fake()->randomElement(['positif', 'netral', 'negatif']);
        return [
            'ulasan_id' => Ulasan::factory(),
            'label_sentimen' => $label,
            'skor_positif' => $label === 'positif' ? fake()->randomFloat(4, 0.5, 0.9) : fake()->randomFloat(4, 0.1, 0.3),
            'skor_netral' => fake()->randomFloat(4, 0.1, 0.3),
            'skor_negatif' => $label === 'negatif' ? fake()->randomFloat(4, 0.5, 0.9) : fake()->randomFloat(4, 0.1, 0.3),
            'metode' => 'Naive Bayes',
            'kata_kunci' => ['test'],
            'diproses_at' => now(),
        ];
    }
}
