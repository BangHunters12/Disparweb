<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analisis_sentimen', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ulasan_id')->unique()->constrained('ulasan')->cascadeOnDelete();
            $table->enum('label_sentimen', ['positif', 'netral', 'negatif']);
            $table->decimal('skor_positif', 5, 4)->default(0);
            $table->decimal('skor_netral', 5, 4)->default(0);
            $table->decimal('skor_negatif', 5, 4)->default(0);
            $table->string('metode', 50)->default('Naive Bayes');
            $table->json('kata_kunci')->nullable();
            $table->timestamp('diproses_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analisis_sentimen');
    }
};
