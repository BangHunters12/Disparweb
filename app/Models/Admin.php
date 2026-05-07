<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasUuids, Notifiable;

    protected $table = 'admins';
    protected $guard = 'admin';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'foto_profil',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function sawConfigs()
    {
        return $this->hasMany(SawConfig::class, 'updated_by');
    }

    public function importLogs()
    {
        return $this->hasMany(ImportLog::class);
    }
}
