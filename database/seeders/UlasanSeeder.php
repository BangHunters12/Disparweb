<?php

namespace Database\Seeders;

use App\Models\Restoran;
use App\Models\Ulasan;
use App\Services\SentimentAnalysisService;
use Illuminate\Database\Seeder;

class UlasanSeeder extends Seeder
{
    protected array $ulasanTemplates = [
        ['teks' => 'Makanannya enak banget! Bumbu khas dan tidak terlalu asin. Pelayanan juga ramah dan cepat. Pasti balik lagi!', 'rating' => 5, 'platform' => 'app'],
        ['teks' => 'Porsi besar, harga sangat terjangkau. Tempatnya bersih dan nyaman. Recommended banget buat keluarga!', 'rating' => 5, 'platform' => 'gmaps'],
        ['teks' => 'Rasanya mantap, tapi agak lama nunggu karena ramai. Overall masih oke dan worth it.', 'rating' => 4, 'platform' => 'app'],
        ['teks' => 'Standar saja, tidak ada yang istimewa. Harga sesuai dengan kualitas yang diberikan.', 'rating' => 3, 'platform' => 'gmaps'],
        ['teks' => 'Pelayanan kurang memuaskan, makanan lama datang padahal tidak terlalu ramai. Rasa lumayan enak.', 'rating' => 3, 'platform' => 'dispar'],
        ['teks' => 'Kopi-nya enak banget! Suasana cafe juga nyaman, cocok buat nongkrong sambil kerja.', 'rating' => 5, 'platform' => 'app'],
        ['teks' => 'Agak mengecewakan, makanan yang dipesan tidak sesuai dengan foto di menu. Rasanya juga biasa saja.', 'rating' => 2, 'platform' => 'gmaps'],
        ['teks' => 'Lezat dan halal, cocok untuk makan siang keluarga. Tempatnya lumayan bersih dan ramah.', 'rating' => 4, 'platform' => 'app'],
        ['teks' => 'Tempat yang bagus tapi harga terlalu mahal untuk kualitas yang disajikan. Lebih baik cari tempat lain.', 'rating' => 2, 'platform' => 'gmaps'],
        ['teks' => 'Favorit saya di Bondowoso! Sudah makan disini berkali-kali dan selalu puas. Bumbu tidak berubah.', 'rating' => 5, 'platform' => 'app'],
    ];

    public function run(): void
    {
        $service  = app(SentimentAnalysisService::class);
        $restoran = Restoran::all();
        $names    = ['Budi', 'Siti', 'Ahmad', 'Dewi', 'Rizky', 'Nurul', 'Andi', 'Ratna', 'Fajar', 'Indah', 'Yoga', 'Putri', 'Dimas', 'Lina', 'Hendra'];

        foreach ($restoran as $r) {
            for ($i = 0; $i < 5; $i++) {
                $template  = $this->ulasanTemplates[($r->id[0] ?? 0) + $i % count($this->ulasanTemplates)];
                $ulasan    = Ulasan::create([
                    'restoran_id'     => $r->id,
                    'nama_reviewer'   => $names[($i + array_sum(array_map('ord', str_split(substr($r->id, 0, 4))))) % count($names)],
                    'rating'          => $template['rating'],
                    'teks_ulasan'     => $template['teks'],
                    'platform_sumber' => $template['platform'],
                    'tgl_kunjungan'   => now()->subDays(rand(1, 180)),
                    'is_visible'      => true,
                ]);
                $service->analyzeAndSave($ulasan->id, $ulasan->teks_ulasan);
            }
        }
    }
}
