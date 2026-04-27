<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\Kecamatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TempatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kategori_id' => Kategori::factory(),
            'kecamatan_id' => Kecamatan::factory(),
            'nama_usaha' => fake()->company(),
            'alamat' => fake()->address(),
            'latitude' => fake()->latitude(-8.1, -7.8),
            'longitude' => fake()->longitude(113.7, 114.0),
            'no_telepon' => fake()->phoneNumber(),
            'harga_min' => fake()->numberBetween(5000, 50000),
            'harga_max' => fake()->numberBetween(50000, 500000),
            'deskripsi' => fake()->paragraph(),
            'status' => 'aktif',
            'sumber_dispar' => true,
        ];
    }
}
