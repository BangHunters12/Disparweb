<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UlasanModerationLog extends Model
{
    use HasUuids;

    protected $table = 'ulasan_moderation_logs';

    protected $fillable = [
        'ulasan_id',
        'tempat_id',
        'admin_id',
        'action',
        'old_rating',
        'new_rating',
        'old_text',
        'new_text',
    ];

    public function ulasan()
    {
        return $this->belongsTo(Ulasan::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
