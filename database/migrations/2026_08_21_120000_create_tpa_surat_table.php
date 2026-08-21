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
        Schema::create('tpa_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tpa_id')->constrained('tenaga_pendukung_akademik')->onDelete('cascade');
            $table->foreignId('surat_dosen_id')->constrained('surat_dosen')->onDelete('cascade');
            $table->string('jabatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tpa_surat');
    }
};
