<?php

namespace Database\Seeders;

use App\Models\SawConfig;
use Illuminate\Database\Seeder;

class SawConfigSeeder extends Seeder
{
    public function run(): void
    {
        SawConfig::create([
            'bobot_rating'      => 40.00,
            'bobot_sentimen'    => 25.00,
            'bobot_harga'       => 15.00,
            'bobot_popularitas' => 10.00,
            'bobot_kebaruan'    => 10.00,
        ]);
    }
}
