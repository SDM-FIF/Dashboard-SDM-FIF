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
        // Add columns to calon_dosen table
        Schema::table('calon_dosen', function (Blueprint $table) {
            $table->enum('jalur_lamaran', [
                'S3 Prof Full time',
                'S2 Praktisi Part time',
                'Praktisi Part time',
                'Prof Full time',
                'OnGoing'
            ])->nullable()->after('status_penerimaan');
            
            $table->decimal('h_index', 5, 2)->nullable()->after('jalur_lamaran');
        });

        // Remove columns from penilaian_detail table
        Schema::table('penilaian_detail', function (Blueprint $table) {
            $table->dropColumn(['jalur_lamaran', 'h_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore columns to penilaian_detail
        Schema::table('penilaian_detail', function (Blueprint $table) {
            $table->string('jalur_lamaran')->nullable()->after('jadwal_pengujian_id');
            $table->decimal('h_index', 8, 2)->nullable()->after('jalur_lamaran');
        });

        // Remove columns from calon_dosen
        Schema::table('calon_dosen', function (Blueprint $table) {
            $table->dropColumn(['jalur_lamaran', 'h_index']);
        });
    }
};
