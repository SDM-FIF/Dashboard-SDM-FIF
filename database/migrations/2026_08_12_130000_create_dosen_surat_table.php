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
        Schema::create('dosen_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_dosen_id')->constrained('surat_dosen')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->timestamps();
        });

        // Migrate existing dosen_id values from surat_dosen into dosen_surat pivot table
        $existingSurat = DB::table('surat_dosen')->whereNotNull('dosen_id')->get();
        foreach ($existingSurat as $s) {
            DB::table('dosen_surat')->insert([
                'surat_dosen_id' => $s->id,
                'dosen_id' => $s->dosen_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Make dosen_id nullable on surat_dosen for backward compatibility
        Schema::table('surat_dosen', function (Blueprint $table) {
            $table->unsignedBigInteger('dosen_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_surat');
    }
};
