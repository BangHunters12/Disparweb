<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@bondowisata.id'],
            [
                'nama'     => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'dispar@bondowisata.id'],
            [
                'nama'     => 'Admin Dispar',
                'password' => Hash::make('password'),
            ]
        );
    }
}
