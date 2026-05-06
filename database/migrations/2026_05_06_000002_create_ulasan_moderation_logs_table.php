<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan_moderation_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ulasan_id')->nullable();
            $table->uuid('tempat_id')->nullable();
            $table->uuid('admin_id')->nullable();
            $table->string('action')->default('updated'); // updated, deleted
            $table->decimal('old_rating', 3, 1)->nullable();
            $table->decimal('new_rating', 3, 1)->nullable();
            $table->text('old_text')->nullable();
            $table->text('new_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan_moderation_logs');
    }
};
