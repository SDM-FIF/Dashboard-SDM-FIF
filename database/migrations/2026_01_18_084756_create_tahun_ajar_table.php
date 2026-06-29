<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun_ajar', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun'); // Format: 2024, 2025, dll
            $table->enum('semester', ['1', '2']); // 1 = Ganjil, 2 = Genap
            $table->timestamps();
            
            // Unique constraint: kombinasi tahun dan semester harus unik
            $table->unique(['tahun', 'semester']);
            
            // Index untuk performa
            $table->index('tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_ajar');
    }
};