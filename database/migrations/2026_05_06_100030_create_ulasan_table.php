<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('restoran_id')->constrained('restoran')->cascadeOnDelete();
            $table->uuid('user_id')->nullable(); // Flutter app user - no FK constraint
            $table->string('nama_reviewer', 100)->nullable();
            $table->string('foto_reviewer', 255)->nullable();
            $table->decimal('rating', 2, 1);
            $table->text('teks_ulasan');
            $table->enum('platform_sumber', ['app', 'gmaps', 'dispar'])->default('app');
            $table->date('tgl_kunjungan')->nullable();
            $table->json('foto_ulasan')->nullable();
            $table->integer('helpful_count')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
