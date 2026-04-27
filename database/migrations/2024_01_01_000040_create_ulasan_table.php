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
            $table->foreignUuid('tempat_id')->constrained('tempat')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('rating', 2, 1);
            $table->text('teks_ulasan')->nullable();
            $table->enum('platform_sumber', ['app', 'gmaps', 'tripadvisor'])->default('app');
            $table->date('tgl_kunjungan')->nullable();
            $table->json('foto_ulasan')->nullable();
            $table->integer('helpful_count')->default(0);
            $table->timestamps();

            $table->index(['tempat_id', 'rating']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
