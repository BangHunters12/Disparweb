<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasUuids;

    protected $table = 'ulasan';

    protected $fillable = [
        'restoran_id',
        'user_id',
        'nama_reviewer',
        'foto_reviewer',
        'rating',
        'teks_ulasan',
        'platform_sumber',
        'tgl_kunjungan',
        'foto_ulasan',
        'helpful_count',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'rating'         => 'decimal:1',
            'tgl_kunjungan'  => 'date',
            'foto_ulasan'    => 'array',
            'is_visible'     => 'boolean',
        ];
    }

    public function restoran()
    {
        return $this->belongsTo(Restoran::class);
    }

    public function analisisSentimen()
    {
        return $this->hasOne(AnalisisSentimen::class);
    }

    public function getPlatformBadgeAttribute(): string
    {
        return match ($this->platform_sumber) {
            'gmaps'  => 'Google Maps',
            'dispar' => 'Dinas Pariwisata',
            default  => 'Aplikasi',
        };
    }

    public function getPlatformColorAttribute(): string
    {
        return match ($this->platform_sumber) {
            'gmaps'  => 'blue',
            'dispar' => 'green',
            default  => 'amber',
        };
    }
}
