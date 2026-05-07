<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    use HasUuids;

    protected $table = 'import_logs';
    public $updatedAt = false;

    protected $fillable = [
        'admin_id',
        'jenis',
        'jumlah_berhasil',
        'jumlah_gagal',
        'detail',
    ];

    protected function casts(): array
    {
        return [
            'detail' => 'array',
        ];
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
