<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AnalisisSentimen extends Model
{
    use HasUuids;

    protected $table = 'analisis_sentimen';
    public $timestamps = false;

    protected $fillable = [
        'ulasan_id',
        'label_sentimen',
        'skor_positif',
        'skor_netral',
        'skor_negatif',
        'metode',
        'kata_kunci',
        'diproses_at',
    ];

    protected function casts(): array
    {
        return [
            'skor_positif' => 'decimal:4',
            'skor_netral'  => 'decimal:4',
            'skor_negatif' => 'decimal:4',
            'kata_kunci'   => 'array',
            'diproses_at'  => 'datetime',
        ];
    }

    public function ulasan()
    {
        return $this->belongsTo(Ulasan::class);
    }

    public function getLabelBadgeColorAttribute(): string
    {
        return match ($this->label_sentimen) {
            'positif' => 'green',
            'negatif' => 'red',
            default   => 'gray',
        };
    }
}
