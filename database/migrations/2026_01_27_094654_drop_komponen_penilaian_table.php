<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop komponen_penilaian table
        Schema::dropIfExists('komponen_penilaian');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate komponen_penilaian table if rollback needed
        Schema::create('komponen_penilaian', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komponen');
            $table->integer('bobot');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }
};
