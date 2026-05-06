<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, HasUuids, Notifiable;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'foto_profil',
        'role',
        'preferensi',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferensi' => 'array',
        ];
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function favorit()
    {
        return $this->hasMany(Favorit::class);
    }

    public function tempatFavorit()
    {
        return $this->belongsToMany(Tempat::class, 'favorit')->withTimestamps();
    }

    public function rekomendasiSaw()
    {
        return $this->hasMany(RekomendasiSaw::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
