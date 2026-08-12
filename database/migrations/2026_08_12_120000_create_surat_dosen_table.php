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
        Schema::create('surat_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->enum('jenis_surat', ['Surat Tugas', 'Surat Keputusan']);
            $table->string('nomor_surat');
            $table->string('judul_surat');
            $table->date('tanggal_surat');
            $table->date('berlaku_mulai')->nullable();
            $table->date('berlaku_selesai')->nullable();
            $table->string('kategori')->default('Lainnya');
            $table->string('file_surat');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_dosen');
    }
};
