<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Bondowoso',      'kode_pos' => '68211', 'lat_center' => -7.9117, 'lng_center' => 113.8231],
            ['nama' => 'Tegalampel',     'kode_pos' => '68251', 'lat_center' => -7.8890, 'lng_center' => 113.8450],
            ['nama' => 'Wonosari',       'kode_pos' => '68261', 'lat_center' => -7.9250, 'lng_center' => 113.8600],
            ['nama' => 'Curahdami',      'kode_pos' => '68222', 'lat_center' => -7.9400, 'lng_center' => 113.7900],
            ['nama' => 'Grujugan',       'kode_pos' => '68215', 'lat_center' => -7.9600, 'lng_center' => 113.8100],
            ['nama' => 'Maesan',         'kode_pos' => '68282', 'lat_center' => -8.0100, 'lng_center' => 113.8900],
            ['nama' => 'Prajekan',       'kode_pos' => '68271', 'lat_center' => -7.8600, 'lng_center' => 113.9200],
            ['nama' => 'Cerme',          'kode_pos' => '68272', 'lat_center' => -7.8400, 'lng_center' => 113.9100],
            ['nama' => 'Klabang',        'kode_pos' => '68292', 'lat_center' => -7.8700, 'lng_center' => 113.9400],
            ['nama' => 'Taman Krocok',   'kode_pos' => '68293', 'lat_center' => -7.8300, 'lng_center' => 113.9600],
            ['nama' => 'Sempol',         'kode_pos' => '68264', 'lat_center' => -8.0700, 'lng_center' => 114.0200],
            ['nama' => 'Sumberan',       'kode_pos' => '68263', 'lat_center' => -7.9900, 'lng_center' => 113.9800],
            ['nama' => 'Botolinggo',     'kode_pos' => '68265', 'lat_center' => -8.0400, 'lng_center' => 113.9500],
            ['nama' => 'Pakem',          'kode_pos' => '68262', 'lat_center' => -7.9800, 'lng_center' => 113.8700],
            ['nama' => 'Pujer',          'kode_pos' => '68285', 'lat_center' => -7.9950, 'lng_center' => 113.9100],
            ['nama' => 'Tlogosari',      'kode_pos' => '68284', 'lat_center' => -7.9600, 'lng_center' => 113.9300],
            ['nama' => 'Tenggarang',     'kode_pos' => '68252', 'lat_center' => -7.8980, 'lng_center' => 113.8320],
            ['nama' => 'Wringin',        'kode_pos' => '68281', 'lat_center' => -8.0200, 'lng_center' => 113.8500],
            ['nama' => 'Binakal',        'kode_pos' => '68253', 'lat_center' => -7.8800, 'lng_center' => 113.8700],
            ['nama' => 'Cermee',         'kode_pos' => '68295', 'lat_center' => -7.8100, 'lng_center' => 113.9800],
            ['nama' => 'Sukosari',       'kode_pos' => '68283', 'lat_center' => -8.0000, 'lng_center' => 113.9000],
            ['nama' => 'Jambesari Darus Sholah', 'kode_pos' => '68224', 'lat_center' => -7.9500, 'lng_center' => 113.7700],
            ['nama' => 'Sumber Wringin', 'kode_pos' => '68291', 'lat_center' => -7.8500, 'lng_center' => 113.9000],
        ];

        foreach ($data as $item) {
            Kecamatan::updateOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
