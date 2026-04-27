<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorit', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('tempat_id')->constrained('tempat')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'tempat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorit');
    }
};
