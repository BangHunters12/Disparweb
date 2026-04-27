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
        'tempat_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tempat()
    {
        return $this->belongsTo(Tempat::class);
    }
}
