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
        'tempat_id',
        'user_id',
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
            'skor_rating' => 'decimal:4',
            'skor_sentimen' => 'decimal:4',
            'skor_harga' => 'decimal:4',
            'skor_popularitas' => 'decimal:4',
            'skor_kebaruan' => 'decimal:4',
            'skor_saw_final' => 'decimal:4',
            'peringkat' => 'integer',
            'dihitung_at' => 'datetime',
        ];
    }

    public function tempat()
    {
        return $this->belongsTo(Tempat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
