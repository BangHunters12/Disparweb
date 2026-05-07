<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->enum('jenis', ['manual', 'gmaps', 'csv']);
            $table->integer('jumlah_berhasil')->default(0);
            $table->integer('jumlah_gagal')->default(0);
            $table->json('detail')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};
