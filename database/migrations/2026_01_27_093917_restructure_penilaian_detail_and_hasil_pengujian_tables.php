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
        // Drop existing penilaian_detail table first (karena ada foreign key ke hasil_pengujian)
        Schema::dropIfExists('penilaian_detail');
        
        // Restructure hasil_pengujian table
        Schema::table('hasil_pengujian', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn(['total_nilai', 'berita_acara']);
            
            // Add new foreign keys
            $table->unsignedBigInteger('calon_dosen_id')->after('jadwal_pengujian_id');
            $table->unsignedBigInteger('dosen_id')->after('calon_dosen_id');
            $table->unsignedBigInteger('penilaian_detail_id')->nullable()->after('dosen_id');
            
            // Add new column
            $table->enum('rekomendasi_akhir', ['Direkomendasikan', 'Tidak Direkomendasikan'])->after('penilaian_detail_id');
            
            // Add foreign key constraints
            $table->foreign('calon_dosen_id')->references('id')->on('calon_dosen')->onDelete('cascade');
            $table->foreign('dosen_id')->references('id')->on('dosen')->onDelete('cascade');
        });
        
        // Recreate penilaian_detail table with new structure
        Schema::create('penilaian_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dosen_id');
            $table->unsignedBigInteger('calon_dosen_id');
            $table->unsignedBigInteger('jadwal_pengujian_id');
            
            // Jalur Lamaran & H-Index
            $table->string('jalur_lamaran')->nullable();
            $table->decimal('h_index', 8, 2)->nullable();
            
            // Nilai-nilai
            $table->decimal('nilai_jalur_lamaran', 8, 2)->nullable();
            $table->decimal('nilai_h_index', 8, 2)->nullable();
            $table->decimal('nilai_jfa', 8, 2)->nullable();
            $table->decimal('nilai_pma', 8, 2)->nullable();
            $table->decimal('nilai_sistematika', 8, 2)->nullable();
            $table->decimal('nilai_kst', 8, 2)->nullable();
            $table->decimal('nilai_motivasi', 8, 2)->nullable();
            
            // Nilai Kompetensi (kmp)
            $table->decimal('nilai_kmp_mengajar', 8, 2)->nullable();
            $table->decimal('nilai_kmp_mkp', 8, 2)->nullable();
            $table->decimal('nilai_kmp_pp', 8, 2)->nullable();
            $table->decimal('nilai_kmp_abdimas', 8, 2)->nullable();
            $table->decimal('nilai_kmp_bdt', 8, 2)->nullable();
            
            // Nilai Lainnya
            $table->decimal('nilai_keahlian_lainnya', 8, 2)->nullable();
            $table->decimal('nilai_kmt_wkm', 8, 2)->nullable();
            
            // Rata-rata
            $table->decimal('rata_a', 8, 2)->nullable();
            $table->decimal('rata_b', 8, 2)->nullable();
            $table->decimal('rata_c', 8, 2)->nullable();
            $table->decimal('rata_nilai', 8, 2)->nullable();
            $table->decimal('rata_akhir', 8, 2)->nullable();
            
            // Keterangan dan Status
            $table->string('keterangan_berbobot')->nullable();
            $table->boolean('kesiapan')->default(false);
            $table->boolean('kesediaan')->default(false);
            $table->text('catatan_penilai')->nullable();
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('dosen_id')->references('id')->on('dosen')->onDelete('cascade');
            $table->foreign('calon_dosen_id')->references('id')->on('calon_dosen')->onDelete('cascade');
            $table->foreign('jadwal_pengujian_id')->references('id')->on('jadwal_pengujian')->onDelete('cascade');
        });
        
        // Add foreign key constraint to hasil_pengujian after penilaian_detail is created
        Schema::table('hasil_pengujian', function (Blueprint $table) {
            $table->foreign('penilaian_detail_id')->references('id')->on('penilaian_detail')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop penilaian_detail table first
        Schema::dropIfExists('penilaian_detail');
        
        // Revert hasil_pengujian table to original structure
        Schema::table('hasil_pengujian', function (Blueprint $table) {
            // Drop new foreign keys first
            $table->dropForeign(['calon_dosen_id']);
            $table->dropForeign(['dosen_id']);
            $table->dropForeign(['penilaian_detail_id']);
            
            // Drop new columns
            $table->dropColumn(['calon_dosen_id', 'dosen_id', 'penilaian_detail_id', 'rekomendasi_akhir']);
            
            // Add back original columns
            $table->unsignedBigInteger('total_nilai')->after('jadwal_pengujian_id');
            $table->text('berita_acara')->after('total_nilai');
        });
    }
};
