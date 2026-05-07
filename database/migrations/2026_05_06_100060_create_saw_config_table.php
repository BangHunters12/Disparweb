<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saw_config', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('bobot_rating', 5, 2)->default(40.00);
            $table->decimal('bobot_sentimen', 5, 2)->default(25.00);
            $table->decimal('bobot_harga', 5, 2)->default(15.00);
            $table->decimal('bobot_popularitas', 5, 2)->default(10.00);
            $table->decimal('bobot_kebaruan', 5, 2)->default(10.00);
            $table->foreignUuid('updated_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saw_config');
    }
};
