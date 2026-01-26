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
        Schema::table('calon_dosen', function (Blueprint $table) {
            // Make optional personal data fields nullable
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->string('nomor_telepon', 20)->nullable()->change();
            $table->text('alamat')->nullable()->change();
            $table->string('jabatan_fungsional_akademik')->nullable()->change();
            $table->string('bidang_keahlian')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_dosen', function (Blueprint $table) {
            // Revert changes - make fields NOT NULL again
            $table->string('tempat_lahir')->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->string('nomor_telepon', 20)->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
            $table->string('jabatan_fungsional_akademik')->nullable(false)->change();
            $table->string('bidang_keahlian')->nullable(false)->change();
        });
    }
};
