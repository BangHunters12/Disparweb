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
            $table->foreignUuid('tempat_id')->constrained('tempat')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('skor_rating', 6, 4)->default(0);
            $table->decimal('skor_sentimen', 6, 4)->default(0);
            $table->decimal('skor_harga', 6, 4)->default(0);
            $table->decimal('skor_popularitas', 6, 4)->default(0);
            $table->decimal('skor_kebaruan', 6, 4)->default(0);
            $table->decimal('skor_saw_final', 6, 4)->default(0);
            $table->integer('peringkat')->default(0);
            $table->timestamp('dihitung_at')->nullable();

            $table->index(['peringkat']);
            $table->index(['tempat_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_saw');
    }
};
