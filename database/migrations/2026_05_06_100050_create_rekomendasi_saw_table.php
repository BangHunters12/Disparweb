<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekomendasi_saw', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('restoran_id')->unique()->constrained('restoran')->cascadeOnDelete();
            $table->decimal('skor_rating', 6, 4)->default(0);
            $table->decimal('skor_sentimen', 6, 4)->default(0);
            $table->decimal('skor_harga', 6, 4)->default(0);
            $table->decimal('skor_popularitas', 6, 4)->default(0);
            $table->decimal('skor_kebaruan', 6, 4)->default(0);
            $table->decimal('skor_saw_final', 6, 4)->default(0);
            $table->integer('peringkat')->nullable();
            $table->timestamp('dihitung_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_saw');
    }
};
