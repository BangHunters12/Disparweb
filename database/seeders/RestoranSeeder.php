<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Restoran;
use Illuminate\Database\Seeder;

class RestoranSeeder extends Seeder
{
    public function run(): void
    {
        $kecBondowoso = Kecamatan::where('nama', 'Bondowoso')->first();
        $kecTegalampel = Kecamatan::where('nama', 'Tegalampel')->first();
        $kecMaesan = Kecamatan::where('nama', 'Maesan')->first();
        $kecCurahdami = Kecamatan::where('nama', 'Curahdami')->first();

        $restoran = [
            [
                'kecamatan_id' => $kecBondowoso?->id,
                'nama_usaha'   => 'Warung Soto Madura Pak Roji',
                'alamat'       => 'Jl. Diponegoro No. 12, Bondowoso',
                'latitude'     => -7.9105, 'longitude' => 113.8220,
                'no_telepon'   => '081234567890',
                'harga_min'    => 10000, 'harga_max' => 35000,
                'avg_rating'   => 4.7, 'total_ulasan' => 234,
                'deskripsi'    => 'Warung soto madura otentik dengan bumbu rempah pilihan, berdiri sejak 1985.',
                'fasilitas'    => ['parkir', 'wifi', 'mushola'],
                'jam_buka'     => [['hari'=>'senin','buka'=>'06:00','tutup'=>'21:00'],['hari'=>'selasa','buka'=>'06:00','tutup'=>'21:00'],['hari'=>'rabu','buka'=>'06:00','tutup'=>'21:00'],['hari'=>'kamis','buka'=>'06:00','tutup'=>'21:00'],['hari'=>'jumat','buka'=>'06:00','tutup'=>'21:00'],['hari'=>'sabtu','buka'=>'06:00','tutup'=>'21:00'],['hari'=>'minggu','buka'=>'07:00','tutup'=>'20:00']],
                'sumber' => 'manual', 'status' => 'aktif',
            ],
            [
                'kecamatan_id' => $kecBondowoso?->id,
                'nama_usaha'   => 'Rumah Makan Nusantara Barokah',
                'alamat'       => 'Jl. A. Yani No. 45, Bondowoso',
                'latitude'     => -7.9130, 'longitude' => 113.8250,
                'harga_min'    => 15000, 'harga_max' => 60000,
                'avg_rating'   => 4.5, 'total_ulasan' => 178,
                'deskripsi'    => 'Masakan Jawa dan Madura dengan cita rasa otentik, sajian lengkap untuk keluarga.',
                'fasilitas'    => ['parkir', 'ac', 'wifi', 'mushola'],
                'jam_buka'     => [['hari'=>'senin','buka'=>'08:00','tutup'=>'22:00'],['hari'=>'selasa','buka'=>'08:00','tutup'=>'22:00'],['hari'=>'rabu','buka'=>'08:00','tutup'=>'22:00'],['hari'=>'kamis','buka'=>'08:00','tutup'=>'22:00'],['hari'=>'jumat','buka'=>'08:00','tutup'=>'22:00'],['hari'=>'sabtu','buka'=>'08:00','tutup'=>'22:00'],['hari'=>'minggu','buka'=>'08:00','tutup'=>'22:00']],
                'sumber' => 'gmaps', 'status' => 'aktif',
                'kode_gmaps'   => 'fake_gmaps_id_001',
            ],
            [
                'kecamatan_id' => $kecBondowoso?->id,
                'nama_usaha'   => 'Cafe Kopi Ijen',
                'alamat'       => 'Jl. Veteran No. 8, Bondowoso',
                'latitude'     => -7.9090, 'longitude' => 113.8200,
                'harga_min'    => 20000, 'harga_max' => 80000,
                'avg_rating'   => 4.8, 'total_ulasan' => 312,
                'deskripsi'    => 'Cafe modern dengan kopi Bondowoso asli Ijen. Suasana nyaman untuk nongkrong dan kerja.',
                'fasilitas'    => ['parkir', 'wifi', 'ac', 'colokan'],
                'jam_buka'     => [['hari'=>'senin','buka'=>'09:00','tutup'=>'23:00'],['hari'=>'selasa','buka'=>'09:00','tutup'=>'23:00'],['hari'=>'rabu','buka'=>'09:00','tutup'=>'23:00'],['hari'=>'kamis','buka'=>'09:00','tutup'=>'23:00'],['hari'=>'jumat','buka'=>'09:00','tutup'=>'00:00'],['hari'=>'sabtu','buka'=>'09:00','tutup'=>'00:00'],['hari'=>'minggu','buka'=>'10:00','tutup'=>'22:00']],
                'sumber' => 'gmaps', 'status' => 'aktif',
                'kode_gmaps'   => 'fake_gmaps_id_002',
            ],
            [
                'kecamatan_id' => $kecBondowoso?->id,
                'nama_usaha'   => 'RM Bebek Goreng Mas Dono',
                'alamat'       => 'Jl. Soekarno Hatta No. 22, Bondowoso',
                'latitude'     => -7.9150, 'longitude' => 113.8270,
                'harga_min'    => 25000, 'harga_max' => 55000,
                'avg_rating'   => 4.6, 'total_ulasan' => 198,
                'deskripsi'    => 'Bebek goreng renyah dengan sambal korek Bondowoso. Wajib dicoba!',
                'fasilitas'    => ['parkir', 'mushola'],
                'jam_buka'     => [['hari'=>'senin','buka'=>'10:00','tutup'=>'22:00'],['hari'=>'selasa','buka'=>'10:00','tutup'=>'22:00'],['hari'=>'rabu','tutup_total'=>true],['hari'=>'kamis','buka'=>'10:00','tutup'=>'22:00'],['hari'=>'jumat','buka'=>'10:00','tutup'=>'22:00'],['hari'=>'sabtu','buka'=>'10:00','tutup'=>'22:00'],['hari'=>'minggu','buka'=>'10:00','tutup'=>'22:00']],
                'sumber' => 'dispar', 'status' => 'aktif',
                'kode_dispar'  => 'BDW-001',
            ],
            [
                'kecamatan_id' => $kecBondowoso?->id,
                'nama_usaha'   => 'Warung Pecel Bu Yayuk',
                'alamat'       => 'Jl. Hayam Wuruk No. 5, Bondowoso',
                'latitude'     => -7.9120, 'longitude' => 113.8215,
                'harga_min'    => 8000, 'harga_max' => 20000,
                'avg_rating'   => 4.4, 'total_ulasan' => 87,
                'deskripsi'    => 'Pecel sayuran segar dengan bumbu kacang khas Bondowoso, murah meriah.',
                'fasilitas'    => ['parkir'],
                'sumber' => 'manual', 'status' => 'aktif',
            ],
            [
                'kecamatan_id' => $kecBondowoso?->id,
                'nama_usaha'   => 'Seafood Bahari 99',
                'alamat'       => 'Jl. Bung Tomo No. 33, Bondowoso',
                'latitude'     => -7.9080, 'longitude' => 113.8190,
                'harga_min'    => 30000, 'harga_max' => 150000,
                'avg_rating'   => 4.3, 'total_ulasan' => 145,
                'deskripsi'    => 'Seafood segar dengan berbagai pilihan masakan: goreng, bakar, saus tiram.',
                'fasilitas'    => ['parkir', 'ac', 'wifi'],
                'sumber' => 'gmaps', 'status' => 'aktif',
                'kode_gmaps'   => 'fake_gmaps_id_003',
            ],
            [
                'kecamatan_id' => $kecBondowoso?->id,
                'nama_usaha'   => 'Ayam Penyet Ria Bondowoso',
                'alamat'       => 'Jl. PB Sudirman No. 17, Bondowoso',
                'latitude'     => -7.9135, 'longitude' => 113.8235,
                'harga_min'    => 20000, 'harga_max' => 45000,
                'avg_rating'   => 4.2, 'total_ulasan' => 67,
                'deskripsi'    => 'Ayam penyet dengan sambal bawang yang pedas dan gurih, cocok untuk makan siang.',
                'fasilitas'    => ['parkir', 'ac'],
                'sumber' => 'manual', 'status' => 'aktif',
            ],
            [
                'kecamatan_id' => $kecTegalampel?->id,
                'nama_usaha'   => 'Sate Madura Pak Broto Tegalampel',
                'alamat'       => 'Jl. Raya Tegalampel No. 5, Tegalampel',
                'latitude'     => -7.8910, 'longitude' => 113.8460,
                'harga_min'    => 15000, 'harga_max' => 40000,
                'avg_rating'   => 4.5, 'total_ulasan' => 112,
                'deskripsi'    => 'Sate Madura dengan bumbu kacang pilihan, daging empuk dan bersih.',
                'fasilitas'    => ['parkir'],
                'sumber' => 'gmaps', 'status' => 'aktif',
                'kode_gmaps'   => 'fake_gmaps_id_004',
            ],
            [
                'kecamatan_id' => $kecMaesan?->id,
                'nama_usaha'   => 'RM Pindang Bumbu Kuning Maesan',
                'alamat'       => 'Jl. Maesan Raya No. 12, Maesan',
                'latitude'     => -8.0115, 'longitude' => 113.8912,
                'harga_min'    => 15000, 'harga_max' => 50000,
                'avg_rating'   => 4.3, 'total_ulasan' => 89,
                'deskripsi'    => 'Ikan pindang dengan kuah bumbu kuning otentik khas Maesan.',
                'fasilitas'    => ['parkir', 'mushola'],
                'sumber' => 'dispar', 'status' => 'aktif',
                'kode_dispar'  => 'BDW-002',
            ],
            [
                'kecamatan_id' => $kecCurahdami?->id,
                'nama_usaha'   => 'Warung Nasi Rawon Curahdami',
                'alamat'       => 'Jl. Curahdami No. 8, Curahdami',
                'latitude'     => -7.9410, 'longitude' => 113.7910,
                'harga_min'    => 12000, 'harga_max' => 30000,
                'avg_rating'   => 4.6, 'total_ulasan' => 156,
                'deskripsi'    => 'Rawon hitam legit dengan daging sapi empuk dan tahu, tempe kecambah segar.',
                'fasilitas'    => ['parkir'],
                'sumber' => 'manual', 'status' => 'aktif',
            ],
        ];

        foreach ($restoran as $data) {
            if (! $data['kecamatan_id']) continue;
            Restoran::create($data);
        }
    }
}
