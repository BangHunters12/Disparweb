<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasUuids;

    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'jenis',
        'icon',
        'warna',
        'deskripsi',
    ];

    public function tempat()
    {
        return $this->hasMany(Tempat::class);
    }
}
