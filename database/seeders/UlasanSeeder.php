<?php

namespace Database\Seeders;

use App\Models\Ulasan;
use App\Models\AnalisisSentimen;
use App\Models\Tempat;
use App\Models\User;
use App\Services\SentimentAnalysisService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UlasanSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(SentimentAnalysisService::class);
        $tempat  = Tempat::all();
        $users   = User::where('role', 'user')->get();

        if ($tempat->isEmpty() || $users->isEmpty()) return;

        $ulasanData = [
            // Positif
            ['rating' => 5.0, 'teks' => 'Enak banget! Tape-nya segar dan rasanya mantap. Wajib beli kalau ke Bondowoso.'],
            ['rating' => 5.0, 'teks' => 'Pelayanan sangat ramah, tempatnya bersih dan nyaman. Pasti balik lagi!'],
            ['rating' => 4.5, 'teks' => 'Kopi arabika Ijen terbaik yang pernah saya coba. Aroma dan rasanya luar biasa.'],
            ['rating' => 4.5, 'teks' => 'Hotelnya bagus, kamarnya bersih dan AC dingin. Sangat recommended!'],
            ['rating' => 4.0, 'teks' => 'Makanannya enak dan porsinya banyak. Harganya juga terjangkau banget.'],
            ['rating' => 5.0, 'teks' => 'Pemandangannya indah sekali! Tempatnya strategis dan unik. Top markotop!'],
            ['rating' => 4.5, 'teks' => 'Batiknya cantik-cantik dengan motif yang khas Bondowoso. Kualitas bagus.'],
            ['rating' => 4.0, 'teks' => 'Senang sekali bisa belajar membuat kerajinan di sini. Pengalaman seru!'],
            ['rating' => 4.5, 'teks' => 'Homestay bersih, pemiliknya ramah, dekat akses ke Kawah Ijen. Mantap!'],
            ['rating' => 5.0, 'teks' => 'Kopi robustanya enak banget, packaging-nya juga rapih. Cocok buat oleh-oleh.'],
            // Positif lanjutan
            ['rating' => 4.0, 'teks' => 'Satenya gurih dan empuk, bumbunya meresap. Recommended buat keluarga!'],
            ['rating' => 4.5, 'teks' => 'Kamar nyaman dan bersih. Sarapan enak. Lokasi dekat pusat kota. Oke!'],
            ['rating' => 5.0, 'teks' => 'Tape uli-nya nagih! Dibuat dari singkong pilihan, rasanya manis sempurna.'],
            ['rating' => 4.0, 'teks' => 'Anyamannya rapi dan kuat. Harganya bersaing. Pengiriman cepat juga!'],
            ['rating' => 4.5, 'teks' => 'Tempat yang instagramable! Kopi dan snack-nya enak. Wifi kencang juga.'],
            // Netral
            ['rating' => 3.0, 'teks' => 'Tempatnya cukup oke. Harganya standar. Pelayanan biasa saja.'],
            ['rating' => 3.5, 'teks' => 'Makanannya lumayan. Lokasinya agak susah dicari tapi perlu dicoba.'],
            ['rating' => 3.0, 'teks' => 'Produknya bagus tapi harganya kurang terjangkau untuk sebagian orang.'],
            ['rating' => 3.5, 'teks' => 'Penginapannya standar. Tidak ada yang istimewa tapi juga tidak buruk.'],
            ['rating' => 3.0, 'teks' => 'Kopinya biasa saja menurut saya. Mungkin selera berbeda-beda.'],
            // Negatif
            ['rating' => 2.0, 'teks' => 'Pelayanannya lambat sekali. Sudah menunggu lama makanan tidak kunjung datang.'],
            ['rating' => 1.5, 'teks' => 'Kamar kotor dan berbau. Sangat mengecewakan, tidak sesuai foto promosi.'],
            ['rating' => 2.5, 'teks' => 'Harganya mahal tapi kualitasnya biasa. Rugi rasanya kesini.'],
            ['rating' => 2.0, 'teks' => 'Makanannya kurang enak dan asin banget. Tidak recommended sama sekali.'],
            ['rating' => 1.0, 'teks' => 'Sangat buruk! Produknya rusak saat sampai. Customer service tidak responsif.'],
        ];

        $used = [];
        $count = 0;

        foreach ($tempat as $t) {
            $numUlasan = rand(3, 8);
            for ($i = 0; $i < $numUlasan && $count < 100; $i++) {
                $idx  = array_rand($ulasanData);
                $ud   = $ulasanData[$idx];
                $user = $users->random();

                $key = $t->id . '_' . $user->id;
                if (in_array($key, $used)) continue;
                $used[] = $key;

                $ulasan = Ulasan::create([
                    'id'              => (string) Str::uuid(),
                    'tempat_id'       => $t->id,
                    'user_id'         => $user->id,
                    'rating'          => $ud['rating'],
                    'teks_ulasan'     => $ud['teks'],
                    'platform_sumber' => 'app',
                    'tgl_kunjungan'   => Carbon::now()->subDays(rand(1, 180))->toDateString(),
                    'helpful_count'   => rand(0, 25),
                ]);

                // Immediately analyze sentiment (no queue for seeder)
                $result = $service->analyze($ulasan->teks_ulasan);
                AnalisisSentimen::create([
                    'id'             => (string) Str::uuid(),
                    'ulasan_id'      => $ulasan->id,
                    'label_sentimen' => $result['label_sentimen'],
                    'skor_positif'   => $result['skor_positif'],
                    'skor_netral'    => $result['skor_netral'],
                    'skor_negatif'   => $result['skor_negatif'],
                    'metode'         => $result['metode'],
                    'kata_kunci'     => $result['kata_kunci'],
                    'diproses_at'    => now(),
                ]);

                $count++;
            }
        }
    }
}
