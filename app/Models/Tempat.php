<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tempat extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tempat';

    protected $fillable = [
        'kategori_id',
        'kecamatan_id',
        'nama_usaha',
        'alamat',
        'latitude',
        'longitude',
        'no_telepon',
        'jam_buka',
        'harga_min',
        'harga_max',
        'foto_utama',
        'foto_galeri',
        'deskripsi',
        'fasilitas',
        'status',
        'sumber_dispar',
        'kode_dispar',
        'tgl_daftar_dispar',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'harga_min' => 'decimal:2',
            'harga_max' => 'decimal:2',
            'jam_buka' => 'array',
            'foto_galeri' => 'array',
            'fasilitas' => 'array',
            'sumber_dispar' => 'boolean',
            'tgl_daftar_dispar' => 'date',
        ];
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function latestUlasan()
    {
        return $this->hasOne(Ulasan::class)->latestOfMany('created_at');
    }

    public function favorit()
    {
        return $this->hasMany(Favorit::class);
    }

    public function rekomendasiSaw()
    {
        return $this->hasMany(RekomendasiSaw::class);
    }

    public function analisisSentimen()
    {
        return $this->hasManyThrough(
            AnalisisSentimen::class,
            Ulasan::class,
        );
    }

    // Scopes
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByKategori(Builder $query, string $jenis): Builder
    {
        return $query->whereHas('kategori', fn ($q) => $q->where('jenis', $jenis));
    }

    public function scopeByKecamatan(Builder $query, string $kecamatanId): Builder
    {
        return $query->where('kecamatan_id', $kecamatanId);
    }

    public function scopeHargaRange(Builder $query, ?float $min, ?float $max): Builder
    {
        if ($min !== null) {
            $query->where('harga_min', '>=', $min);
        }
        if ($max !== null) {
            $query->where('harga_max', '<=', $max);
        }

        return $query;
    }

    public function scopeMinRating(Builder $query, float $rating): Builder
    {
        return $query->whereHas('ulasan', function ($q) use ($rating) {
            $q->selectRaw('AVG(rating) as avg_rating')
                ->havingRaw('AVG(rating) >= ?', [$rating]);
        });
    }

    // Accessors
    public function getRataRatingAttribute(): float
    {
        return round($this->ulasan()->avg('rating') ?? 0, 1);
    }

    public function getJumlahUlasanAttribute(): int
    {
        return $this->ulasan()->count();
    }

    public function getSkorSawAttribute(): ?float
    {
        $saw = $this->rekomendasiSaw()->whereNull('user_id')->first();

        return $saw?->skor_saw_final;
    }
}
