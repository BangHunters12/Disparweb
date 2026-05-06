<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user',  'guard_name' => 'web']);

        $admin = User::create([
            'id' => (string) Str::uuid(),
            'nama_lengkap' => 'Admin BondoWisata',
            'email' => 'admin@bondowisata.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        $admin->assignRole($adminRole);

        $users = [
            ['nama_lengkap' => 'Budi Santoso',   'email' => 'budi@example.com'],
            ['nama_lengkap' => 'Siti Rahayu',    'email' => 'siti@example.com'],
            ['nama_lengkap' => 'Ahmad Fauzi',    'email' => 'ahmad@example.com'],
            ['nama_lengkap' => 'Dewi Lestari',   'email' => 'dewi@example.com'],
            ['nama_lengkap' => 'Rizky Pratama',  'email' => 'rizky@example.com'],
        ];

        foreach ($users as $data) {
            $user = User::create([
                'id' => (string) Str::uuid(),
                'nama_lengkap' => $data['nama_lengkap'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'user',
            ]);
            $user->assignRole($userRole);
        }
    }
}
