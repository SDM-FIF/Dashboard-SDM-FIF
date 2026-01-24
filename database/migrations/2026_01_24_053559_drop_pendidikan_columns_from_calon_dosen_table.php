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
            $table->dropColumn([
                'prodi_pendidikan_s1',
                'nama_kampus_pendidikan_s1',
                'ipk_s1',
                'prodi_pendidikan_s2',
                'nama_kampus_pendidikan_s2',
                'ipk_s2',
                'prodi_pendidikan_s3',
                'nama_kampus_pendidikan_s3',
                'ipk_s3',
                'prodi_tujuan',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_dosen', function (Blueprint $table) {
            $table->string('prodi_pendidikan_s1')->nullable()->after('alamat');
            $table->string('nama_kampus_pendidikan_s1')->nullable()->after('prodi_pendidikan_s1');
            $table->decimal('ipk_s1', 3, 2)->nullable()->after('nama_kampus_pendidikan_s1');
            $table->string('prodi_pendidikan_s2')->nullable()->after('ipk_s1');
            $table->string('nama_kampus_pendidikan_s2')->nullable()->after('prodi_pendidikan_s2');
            $table->decimal('ipk_s2', 3, 2)->nullable()->after('nama_kampus_pendidikan_s2');
            $table->string('prodi_pendidikan_s3')->nullable()->after('ipk_s2');
            $table->string('nama_kampus_pendidikan_s3')->nullable()->after('prodi_pendidikan_s3');
            $table->decimal('ipk_s3', 3, 2)->nullable()->after('nama_kampus_pendidikan_s3');
            $table->string('prodi_tujuan')->nullable()->after('jabatan_fungsional_akademik');
        });
    }
};
