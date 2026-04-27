<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SAW (Simple Additive Weighting) Configuration
    |--------------------------------------------------------------------------
    |
    | Bobot kriteria untuk perhitungan rekomendasi SAW.
    | Total semua bobot harus = 1.0 (100%)
    |
    */

    'weights' => [
        'rating'      => 0.40, // 40% - Rata-rata rating ulasan
        'sentimen'    => 0.25, // 25% - Skor sentimen positif
        'harga'       => 0.15, // 15% - Keterjangkauan harga (cost benefit)
        'popularitas' => 0.10, // 10% - Jumlah ulasan/kunjungan
        'kebaruan'    => 0.10, // 10% - Kebaruan data/ulasan
    ],

    /*
    |--------------------------------------------------------------------------
    | Tipe Kriteria
    |--------------------------------------------------------------------------
    | 'benefit' = semakin tinggi semakin baik
    | 'cost'    = semakin rendah semakin baik
    */

    'criteria_types' => [
        'rating'      => 'benefit',
        'sentimen'    => 'benefit',
        'harga'       => 'cost',
        'popularitas' => 'benefit',
        'kebaruan'    => 'benefit',
    ],

    /*
    |--------------------------------------------------------------------------
    | Jadwal Perhitungan Ulang
    |--------------------------------------------------------------------------
    */
    'recalculate_schedule' => 'daily',

    /*
    |--------------------------------------------------------------------------
    | Minimum Ulasan untuk Masuk Rekomendasi
    |--------------------------------------------------------------------------
    */
    'min_reviews' => 1,
];
