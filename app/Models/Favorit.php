<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    use HasUuids;

    protected $table = 'favorit';

    protected $fillable = [
        'user_id',
        'restoran_id',
    ];

    public function restoran()
    {
        return $this->belongsTo(Restoran::class);
    }
}
