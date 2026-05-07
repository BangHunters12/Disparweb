<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Restoran extends Model
{
    use HasUuids;

    protected $table = 'restoran';

    protected $fillable = [
        'kecamatan_id',
        'nama_usaha',
        'slug',
        'alamat',
        'latitude',
        'longitude',
        'no_telepon',
        'website',
        'jam_buka',
        'harga_min',
        'harga_max',
        'foto_utama',
        'foto_galeri',
        'deskripsi',
        'fasilitas',
        'status',
        'sumber',
        'kode_gmaps',
        'gmaps_url',
        'kode_dispar',
        'tgl_daftar_dispar',
        'avg_rating',
        'total_ulasan',
        'total_views',
    ];

    protected function casts(): array
    {
        return [
            'latitude'         => 'decimal:8',
            'longitude'        => 'decimal:8',
            'harga_min'        => 'decimal:2',
            'harga_max'        => 'decimal:2',
            'avg_rating'       => 'decimal:2',
            'jam_buka'         => 'array',
            'foto_galeri'      => 'array',
            'fasilitas'        => 'array',
            'tgl_daftar_dispar' => 'date',
        ];
    }

    // ── Boot ──────────────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Restoran $r) {
            if (empty($r->slug)) {
                $r->slug = static::generateUniqueSlug($r->nama_usaha);
            }
        });

        static::updating(function (Restoran $r) {
            if ($r->isDirty('nama_usaha') && empty($r->slug)) {
                $r->slug = static::generateUniqueSlug($r->nama_usaha);
            }
        });
    }

    public static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }
        return $slug;
    }

    // ── Relations ─────────────────────────────────────────────────────
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function ulasanVisible()
    {
        return $this->hasMany(Ulasan::class)->where('is_visible', true);
    }

    public function latestUlasan()
    {
        return $this->hasOne(Ulasan::class)->latestOfMany('created_at');
    }

    public function rekomendasiSaw()
    {
        return $this->hasOne(RekomendasiSaw::class);
    }

    public function favorit()
    {
        return $this->hasMany(Favorit::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────
    public function scopeAktif(Builder $q): Builder
    {
        return $q->where('restoran.status', 'aktif');
    }

    public function scopeByKecamatan(Builder $q, string $id): Builder
    {
        return $q->where('restoran.kecamatan_id', $id);
    }

    public function scopeHargaRange(Builder $q, ?float $min, ?float $max): Builder
    {
        if ($min !== null) {
            $q->where('harga_min', '>=', $min);
        }
        if ($max !== null) {
            $q->where('harga_max', '<=', $max);
        }
        return $q;
    }

    public function scopeMinRating(Builder $q, float $rating): Builder
    {
        return $q->where('avg_rating', '>=', $rating);
    }

    public function scopeBukaSaatIni(Builder $q): Builder
    {
        $hari = strtolower(now()->locale('id')->dayName);
        $jam  = now()->format('H:i');

        return $q->whereJsonContains('jam_buka', ['hari' => $hari])
            ->where(function ($sub) use ($jam) {
                $sub->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(jam_buka, '$[*].buka')) <= ?", [$jam])
                    ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(jam_buka, '$[*].tutup')) >= ?", [$jam]);
            });
    }

    // ── Accessors ─────────────────────────────────────────────────────
    public function getFotoUtamaUrlAttribute(): string
    {
        if ($this->foto_utama) {
            return asset('storage/' . $this->foto_utama);
        }
        return 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&q=80';
    }

    public function getSkorSawAttribute(): ?float
    {
        return $this->rekomendasiSaw?->skor_saw_final;
    }

    public function getHargaRangeTextAttribute(): string
    {
        if ($this->harga_min && $this->harga_max) {
            return 'Rp ' . number_format($this->harga_min, 0, ',', '.') .
                ' – Rp ' . number_format($this->harga_max, 0, ',', '.');
        }
        if ($this->harga_min) {
            return 'Rp ' . number_format($this->harga_min, 0, ',', '.') . '+';
        }
        return 'Harga tidak tersedia';
    }

    public function getIsBukaAttribute(): bool
    {
        if (empty($this->jam_buka)) return false;
        $hari = strtolower(now()->locale('id')->isoFormat('dddd'));
        $jam  = now()->format('H:i');
        foreach ($this->jam_buka as $j) {
            if (
                isset($j['hari'], $j['buka'], $j['tutup']) &&
                strtolower($j['hari']) === $hari &&
                $jam >= $j['buka'] &&
                $jam <= $j['tutup']
            ) {
                return true;
            }
        }
        return false;
    }
}
