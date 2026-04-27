<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tempat', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->foreignUuid('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();
            $table->string('nama_usaha', 200);
            $table->text('alamat')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->json('jam_buka')->nullable();
            $table->decimal('harga_min', 12, 2)->nullable();
            $table->decimal('harga_max', 12, 2)->nullable();
            $table->string('foto_utama', 255)->nullable();
            $table->json('foto_galeri')->nullable();
            $table->text('deskripsi')->nullable();
            $table->json('fasilitas')->nullable();
            $table->enum('status', ['aktif', 'tutup', 'review'])->default('aktif');
            $table->boolean('sumber_dispar')->default(true);
            $table->string('kode_dispar', 20)->nullable()->unique();
            $table->date('tgl_daftar_dispar')->nullable();
            $table->timestamps();

            $table->index(['kategori_id', 'status']);
            $table->index(['kecamatan_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tempat');
    }
};
