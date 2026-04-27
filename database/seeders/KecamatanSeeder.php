<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatans = [
            ['nama' => 'Bondowoso Kota', 'kode_pos' => '68211', 'lat_center' => -7.9092, 'lng_center' => 113.8224],
            ['nama' => 'Curahdami',      'kode_pos' => '68261', 'lat_center' => -7.8765, 'lng_center' => 113.8012],
            ['nama' => 'Grujugan',       'kode_pos' => '68281', 'lat_center' => -7.9345, 'lng_center' => 113.8156],
            ['nama' => 'Wonosari',       'kode_pos' => '68213', 'lat_center' => -7.9512, 'lng_center' => 113.8389],
            ['nama' => 'Tenggarang',     'kode_pos' => '68253', 'lat_center' => -7.8634, 'lng_center' => 113.8567],
            ['nama' => 'Tapen',          'kode_pos' => '68254', 'lat_center' => -7.8421, 'lng_center' => 113.8890],
            ['nama' => 'Maesan',         'kode_pos' => '68271', 'lat_center' => -8.0123, 'lng_center' => 113.7812],
            ['nama' => 'Prajekan',       'kode_pos' => '68291', 'lat_center' => -7.8012, 'lng_center' => 113.9023],
            ['nama' => 'Wringin',        'kode_pos' => '68295', 'lat_center' => -7.9678, 'lng_center' => 113.9345],
            ['nama' => 'Sumber Wringin', 'kode_pos' => '68296', 'lat_center' => -7.9890, 'lng_center' => 113.9678],
            ['nama' => 'Botolinggo',     'kode_pos' => '68292', 'lat_center' => -7.8234, 'lng_center' => 113.9456],
            ['nama' => 'Pakem',          'kode_pos' => '68282', 'lat_center' => -7.9123, 'lng_center' => 113.7534],
            ['nama' => 'Binakal',        'kode_pos' => '68252', 'lat_center' => -7.8890, 'lng_center' => 113.8234],
            ['nama' => 'Cerme',          'kode_pos' => '68256', 'lat_center' => -7.8567, 'lng_center' => 113.8678],
            ['nama' => 'Klabang',        'kode_pos' => '68293', 'lat_center' => -7.9012, 'lng_center' => 113.9123],
        ];

        foreach ($kecamatans as $data) {
            Kecamatan::create(array_merge($data, ['id' => (string) Str::uuid()]));
        }
    }
}
