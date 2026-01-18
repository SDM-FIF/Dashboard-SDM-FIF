<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calon_dosen', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Prodi
            $table->foreignId('prodi_id')
                ->nullable()
                ->constrained('prodi')
                ->onDelete('set null');
            
            // Data Pribadi
            $table->string('nama');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('nomor_telepon', 20); // Changed to string for better phone number handling
            $table->text('alamat');
            
            // Pendidikan S1
            $table->string('prodi_pendidikan_s1')->nullable();
            $table->string('nama_kampus_pendidikan_s1')->nullable();
            $table->decimal('ipk_s1', 3, 2)->nullable(); // Format: 4.00
            
            // Pendidikan S2
            $table->string('prodi_pendidikan_s2')->nullable();
            $table->string('nama_kampus_pendidikan_s2')->nullable();
            $table->decimal('ipk_s2', 3, 2)->nullable();
            
            // Pendidikan S3
            $table->string('prodi_pendidikan_s3')->nullable();
            $table->string('nama_kampus_pendidikan_s3')->nullable();
            $table->decimal('ipk_s3', 3, 2)->nullable();
            
            // Jabatan dan Prodi Tujuan
            $table->enum('jabatan_fungsional_akademik', [
                'NJFA',
                'Asisten Ahli',
                'Lektor',
                'Lektor Kepala',
                'Guru Besar'
            ])->default('NJFA');
            
            $table->enum('prodi_tujuan', [
                'Informatika',
                'Rekayasa Perangkat Lunak',
                'Data Sains',
                'Teknologi Informasi'
            ]);
            
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index('nama');
            $table->index('prodi_tujuan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calon_dosen');
    }
};