<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, temporarily change to string to allow data update
        Schema::table('dosen', function (Blueprint $table) {
            $table->string('jabatan')->nullable()->change();
        });

        // Update existing data: change 'Profesor' to 'Guru Besar'
        DB::table('dosen')
            ->where('jabatan', 'Profesor')
            ->update(['jabatan' => 'Guru Besar']);

        // Now change back to enum with new values
        Schema::table('dosen', function (Blueprint $table) {
            $table->enum('jabatan', [
                'NJFA',
                'Asisten Ahli',
                'Lektor',
                'Lektor Kepala',
                'Guru Besar'
            ])->default('NJFA')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update existing data: change 'Guru Besar' back to 'Profesor'
        DB::table('dosen')
            ->where('jabatan', 'Guru Besar')
            ->update(['jabatan' => 'Profesor']);

        // Revert column enum: replace 'Guru Besar' with 'Profesor'
        Schema::table('dosen', function (Blueprint $table) {
            $table->enum('jabatan', [
                'NJFA',
                'Asisten Ahli',
                'Lektor',
                'Lektor Kepala',
                'Profesor'
            ])->default('NJFA')->change();
        });
    }
};
