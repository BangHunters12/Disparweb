<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RekomendasiSaw extends Model
{
    use HasUuids;

    protected $table = 'rekomendasi_saw';
    public $timestamps = false;

    protected $fillable = [
        'restoran_id',
        'skor_rating',
        'skor_sentimen',
        'skor_harga',
        'skor_popularitas',
        'skor_kebaruan',
        'skor_saw_final',
        'peringkat',
        'dihitung_at',
    ];

    protected function casts(): array
    {
        return [
            'skor_rating'      => 'decimal:4',
            'skor_sentimen'    => 'decimal:4',
            'skor_harga'       => 'decimal:4',
            'skor_popularitas' => 'decimal:4',
            'skor_kebaruan'    => 'decimal:4',
            'skor_saw_final'   => 'decimal:4',
            'dihitung_at'      => 'datetime',
        ];
    }

    public function restoran()
    {
        return $this->belongsTo(Restoran::class);
    }
}
