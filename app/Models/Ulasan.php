<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasUuids;

    protected $table = 'ulasan';

    protected $fillable = [
        'tempat_id',
        'user_id',
        'rating',
        'teks_ulasan',
        'platform_sumber',
        'tgl_kunjungan',
        'foto_ulasan',
        'helpful_count',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:1',
            'tgl_kunjungan' => 'date',
            'foto_ulasan' => 'array',
            'helpful_count' => 'integer',
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

    public function analisisSentimen()
    {
        return $this->hasOne(AnalisisSentimen::class);
    }
}
