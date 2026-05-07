<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restoran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();
            $table->string('nama_usaha', 200);
            $table->string('slug', 220)->unique();
            $table->text('alamat')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('website', 255)->nullable();
            $table->json('jam_buka')->nullable();
            $table->decimal('harga_min', 10, 2)->nullable();
            $table->decimal('harga_max', 10, 2)->nullable();
            $table->string('foto_utama', 255)->nullable();
            $table->json('foto_galeri')->nullable();
            $table->text('deskripsi')->nullable();
            $table->json('fasilitas')->nullable();
            $table->enum('status', ['aktif', 'tutup', 'review'])->default('aktif');
            $table->enum('sumber', ['manual', 'gmaps', 'dispar'])->default('manual');
            $table->string('kode_gmaps', 100)->unique()->nullable();
            $table->string('gmaps_url', 500)->nullable();
            $table->string('kode_dispar', 20)->unique()->nullable();
            $table->date('tgl_daftar_dispar')->nullable();
            $table->decimal('avg_rating', 3, 2)->default(0);
            $table->integer('total_ulasan')->default(0);
            $table->integer('total_views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restoran');
    }
};
