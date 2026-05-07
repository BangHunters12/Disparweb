<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SawConfig extends Model
{
    use HasUuids;

    protected $table = 'saw_config';

    protected $fillable = [
        'bobot_rating',
        'bobot_sentimen',
        'bobot_harga',
        'bobot_popularitas',
        'bobot_kebaruan',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'bobot_rating'      => 'decimal:2',
            'bobot_sentimen'    => 'decimal:2',
            'bobot_harga'       => 'decimal:2',
            'bobot_popularitas' => 'decimal:2',
            'bobot_kebaruan'    => 'decimal:2',
        ];
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    /**
     * Get the current (latest) config or create default.
     */
    public static function current(): self
    {
        return static::latest()->firstOrCreate([], [
            'bobot_rating'      => 40.00,
            'bobot_sentimen'    => 25.00,
            'bobot_harga'       => 15.00,
            'bobot_popularitas' => 10.00,
            'bobot_kebaruan'    => 10.00,
        ]);
    }

    /** Get normalized weights (0-1 scale) */
    public function normalizedWeights(): array
    {
        $total = $this->bobot_rating + $this->bobot_sentimen +
            $this->bobot_harga + $this->bobot_popularitas + $this->bobot_kebaruan;

        if ($total <= 0) {
            return ['rating' => 0.4, 'sentimen' => 0.25, 'harga' => 0.15, 'popularitas' => 0.1, 'kebaruan' => 0.1];
        }

        return [
            'rating'      => (float) $this->bobot_rating / $total,
            'sentimen'    => (float) $this->bobot_sentimen / $total,
            'harga'       => (float) $this->bobot_harga / $total,
            'popularitas' => (float) $this->bobot_popularitas / $total,
            'kebaruan'    => (float) $this->bobot_kebaruan / $total,
        ];
    }
}
