<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kecamatan';

    protected $fillable = [
        'nama',
        'kode_pos',
        'lat_center',
        'lng_center',
    ];

    protected function casts(): array
    {
        return [
            'lat_center' => 'decimal:8',
            'lng_center' => 'decimal:8',
        ];
    }

    public function tempat()
    {
        return $this->hasMany(Tempat::class);
    }
}
