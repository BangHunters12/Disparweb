<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategories = [
            // Restoran
            ['nama' => 'Restoran & Rumah Makan', 'jenis' => 'restoran', 'icon' => 'utensils',    'warna' => '#f59e0b', 'deskripsi' => 'Rumah makan, restoran, dan warung kuliner lokal Bondowoso.'],
            ['nama' => 'Kafe & Kedai Kopi',       'jenis' => 'restoran', 'icon' => 'coffee',      'warna' => '#d97706', 'deskripsi' => 'Kafe, kedai kopi, dan tempat nongkrong modern.'],
            ['nama' => 'Bakeri & Jajanan',        'jenis' => 'restoran', 'icon' => 'cake',        'warna' => '#f97316', 'deskripsi' => 'Bakeri, kue, jajanan pasar, dan oleh-oleh khas Bondowoso.'],

            // Hotel
            ['nama' => 'Hotel & Resort',          'jenis' => 'hotel',    'icon' => 'hotel',       'warna' => '#3b82f6', 'deskripsi' => 'Hotel berbintang dan resort premium Bondowoso.'],
            ['nama' => 'Penginapan & Homestay',   'jenis' => 'hotel',    'icon' => 'home',        'warna' => '#6366f1', 'deskripsi' => 'Penginapan ekonomis, homestay, dan guest house.'],
            ['nama' => 'Villa & Cottages',        'jenis' => 'hotel',    'icon' => 'house',       'warna' => '#8b5cf6', 'deskripsi' => 'Villa dan cottage dengan pemandangan alam Bondowoso.'],

            // Ekraf
            ['nama' => 'Kerajinan Tangan',        'jenis' => 'ekraf',    'icon' => 'hand-crafts', 'warna' => '#10b981', 'deskripsi' => 'Produk kerajinan tangan dan cendera mata lokal.'],
            ['nama' => 'Batik & Tenun',           'jenis' => 'ekraf',    'icon' => 'shirt',       'warna' => '#059669', 'deskripsi' => 'Batik Bondowoso, kain tenun, dan busana tradisional.'],
            ['nama' => 'Kuliner Oleh-Oleh',       'jenis' => 'ekraf',    'icon' => 'gift',        'warna' => '#0d9488', 'deskripsi' => 'Produksi tape, kopi arabika, dan oleh-oleh khas.'],
            ['nama' => 'Seni & Pertunjukan',      'jenis' => 'ekraf',    'icon' => 'music',       'warna' => '#0891b2', 'deskripsi' => 'Sanggar seni, pertunjukan budaya, dan galeri lokal.'],
        ];

        foreach ($kategories as $data) {
            Kategori::create(array_merge($data, ['id' => (string) Str::uuid()]));
        }
    }
}
