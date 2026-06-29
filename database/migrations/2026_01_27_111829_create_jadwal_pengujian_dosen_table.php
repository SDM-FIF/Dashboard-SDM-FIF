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
        Schema::create('jadwal_pengujian_dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_pengujian_id');
            $table->unsignedBigInteger('dosen_id');
            $table->integer('urutan')->default(1); // For numbering (1, 2, 3)
            $table->timestamps();

            // Foreign keys
            $table->foreign('jadwal_pengujian_id')
                  ->references('id')
                  ->on('jadwal_pengujian')
                  ->onDelete('cascade');
            
            $table->foreign('dosen_id')
                  ->references('id')
                  ->on('dosen')
                  ->onDelete('cascade');

            // Prevent duplicate entries
            $table->unique(['jadwal_pengujian_id', 'dosen_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pengujian_dosen');

        // Restore old column
        Schema::table('jadwal_pengujian', function (Blueprint $table) {
            $table->unsignedBigInteger('dosen_penguji_id')->nullable()->after('calon_dosen_id');
            $table->foreign('dosen_penguji_id')->references('id')->on('dosen')->onDelete('set null');
        });
    }
};
