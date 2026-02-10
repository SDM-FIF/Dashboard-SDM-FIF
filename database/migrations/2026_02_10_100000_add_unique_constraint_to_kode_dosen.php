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
        // First, find and fix any duplicate kode_dosen values
        $duplicates = DB::table('dosen')
            ->select('kode_dosen', DB::raw('COUNT(*) as count'))
            ->groupBy('kode_dosen')
            ->having('count', '>', 1)
            ->get();

        // If there are duplicates, update them to make them unique
        foreach ($duplicates as $duplicate) {
            $dosens = DB::table('dosen')
                ->where('kode_dosen', $duplicate->kode_dosen)
                ->orderBy('id')
                ->get();

            // Keep the first one, update the others
            $counter = 1;
            foreach ($dosens as $index => $dosen) {
                if ($index > 0) { // Skip the first one
                    $newKodeDosen = $duplicate->kode_dosen . '_' . $counter;
                    
                    // Make sure the new code is also unique
                    while (DB::table('dosen')->where('kode_dosen', $newKodeDosen)->exists()) {
                        $counter++;
                        $newKodeDosen = $duplicate->kode_dosen . '_' . $counter;
                    }
                    
                    DB::table('dosen')
                        ->where('id', $dosen->id)
                        ->update(['kode_dosen' => $newKodeDosen]);
                    
                    $counter++;
                }
            }
        }

        // Now add the unique constraint
        Schema::table('dosen', function (Blueprint $table) {
            $table->unique('kode_dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropUnique(['kode_dosen']);
        });
    }
};
