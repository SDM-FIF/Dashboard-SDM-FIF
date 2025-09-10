<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Fakultas
        if (!Schema::hasTable('fakultas')) {
            Schema::create('fakultas', function (Blueprint $table) {
                $table->id();
                $table->string('nama_fakultas');
                $table->timestamps();
            });
        }

        // 2) Prodi (depends on fakultas)
        if (!Schema::hasTable('prodi')) {
            Schema::create('prodi', function (Blueprint $table) {
                $table->id();
                $table->string('nama_prodi');
                $table->unsignedBigInteger('fakultas_id')->nullable();
                $table->timestamps();

                $table->foreign('fakultas_id')->references('id')->on('fakultas')->onDelete('set null');
            });
        }

        // 3) Kelompok keahlian
        if (!Schema::hasTable('kelompok_keahlian')) {
            Schema::create('kelompok_keahlian', function (Blueprint $table) {
                $table->id();
                $table->text('nama_kelompok_keahlian')->nullable();
                $table->timestamps();
            });
        }

        // 4) Pastikan kolom profil pada users ada (nama_lengkap, username, fakultas_id, prodi_id)
        if (Schema::hasTable('users')) {
            // drop roles_id if exists (we will use Spatie's model_has_roles)
            $hasRolesId = Schema::hasColumn('users', 'roles_id');
            if ($hasRolesId) {
                // DROP COLUMN via raw statement to avoid Doctrine requirement
                DB::statement('ALTER TABLE `users` DROP COLUMN `roles_id`');
            }

            // add nama_lengkap
            if (! Schema::hasColumn('users', 'nama_lengkap')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('nama_lengkap')->nullable()->after('name');
                });
            }

            // add username (unique) if not exists
            if (! Schema::hasColumn('users', 'username')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('username')->nullable()->unique()->after('nama_lengkap');
                });
            }

            // add fakultas_id & prodi_id (fks will be added after those tables exist)
            if (! Schema::hasColumn('users', 'fakultas_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->unsignedBigInteger('fakultas_id')->nullable()->after('remember_token');
                });
            }
            if (! Schema::hasColumn('users', 'prodi_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->unsignedBigInteger('prodi_id')->nullable()->after('fakultas_id');
                });
            }
        } else {
            // If users table doesn't exist (unlikely), create minimal one
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('nama_lengkap')->nullable();
                $table->string('username')->nullable()->unique();
                $table->string('password');
                $table->unsignedBigInteger('fakultas_id')->nullable();
                $table->unsignedBigInteger('prodi_id')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // 5) Dosen
        if (!Schema::hasTable('dosen')) {
            Schema::create('dosen', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('front_title')->nullable();
                $table->string('nama_lengkap')->nullable();
                $table->string('back_title')->nullable();
                $table->string('jabatan')->nullable();
                $table->unsignedBigInteger('prodi_id')->nullable();
                $table->string('nip')->nullable();
                $table->string('kode_dosen')->nullable();
                $table->string('lokasi_kerja')->nullable();
                $table->unsignedBigInteger('kelompok_keahlian_id')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('set null');
                $table->foreign('kelompok_keahlian_id')->references('id')->on('kelompok_keahlian')->onDelete('set null');
            });
        }

        // 6) Tenaga pendukung akademik (TPA)
        if (!Schema::hasTable('tenaga_pendukung_akademik')) {
            Schema::create('tenaga_pendukung_akademik', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('nama_lengkap')->nullable();
                $table->string('nip')->nullable();
                $table->string('pangkat_golongan')->nullable();
                $table->string('status_pegawai')->nullable();
                $table->string('lokasi_kerja')->nullable();
                $table->string('pendidikan_terakhir')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // 7) Mahasiswa
        if (!Schema::hasTable('mahasiswa')) {
            Schema::create('mahasiswa', function (Blueprint $table) {
                $table->id();
                $table->string('nama_lengkap')->nullable();
                $table->string('nim')->nullable();
                $table->unsignedBigInteger('prodi_id')->nullable();
                $table->timestamps();

                $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('set null');
            });
        }

        // 8) Kompetisi
        if (!Schema::hasTable('kompetisi')) {
            Schema::create('kompetisi', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kompetisi')->nullable();
                $table->string('nama_penyelenggara')->nullable();
                $table->enum('tingkat_kompetisi', ['Universitas','Kabupaten/Kota','Provinsi','Nasional','Internasional'])->nullable();
                $table->date('tanggal_kompetisi')->nullable();
                $table->timestamps();
            });
        }

        // 9) Mahasiswa_Kompetisi (pivot, with id to allow metadata later)
        if (!Schema::hasTable('mahasiswa_kompetisi')) {
            Schema::create('mahasiswa_kompetisi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('mahasiswa_id')->nullable();
                $table->unsignedBigInteger('kompetisi_id')->nullable();
                $table->timestamps();

                $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->onDelete('cascade');
                $table->foreign('kompetisi_id')->references('id')->on('kompetisi')->onDelete('cascade');
            });
        }

        // 10) Rekrutasi dosen
        if (!Schema::hasTable('rekrutasi_dosen')) {
            Schema::create('rekrutasi_dosen', function (Blueprint $table) {
                $table->id();
                $table->string('nama_calon')->nullable();
                $table->date('tanggal_pengujian')->nullable();
                $table->enum('status', ['Diajukan','Diproses','Diterima','Ditolak'])->default('Diajukan');
                $table->timestamps();
            });
        }

        // 11) Jadwal pengujian
        if (!Schema::hasTable('jadwal_pengujian')) {
            Schema::create('jadwal_pengujian', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('rekrutasi_dosen_id')->nullable();
                $table->unsignedBigInteger('dosen_penguji_id')->nullable();
                $table->dateTime('jadwal_ujian')->nullable();
                $table->timestamps();

                $table->foreign('rekrutasi_dosen_id')->references('id')->on('rekrutasi_dosen')->onDelete('cascade');
                $table->foreign('dosen_penguji_id')->references('id')->on('dosen')->onDelete('set null');
            });
        }

        // 12) Hasil pengujian
        if (!Schema::hasTable('hasil_pengujian')) {
            Schema::create('hasil_pengujian', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('jadwal_pengujian_id')->nullable();
                $table->bigInteger('total_nilai')->nullable();
                $table->text('berita_acara')->nullable();
                $table->date('submitted_at')->nullable();
                $table->timestamps();

                $table->foreign('jadwal_pengujian_id')->references('id')->on('jadwal_pengujian')->onDelete('cascade');
            });
        }

        // 13) Komponen Penilaian
        if (!Schema::hasTable('komponen_penilaian')) {
            Schema::create('komponen_penilaian', function (Blueprint $table) {
                $table->id();
                $table->string('nama_komponen');
                $table->integer('bobot')->default(0);
                $table->timestamps();
            });
        }

        // 14) Penilaian Detail (pivot hasil_pengujian <-> komponen_penilaian)
        if (!Schema::hasTable('penilaian_detail')) {
            Schema::create('penilaian_detail', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('hasil_pengujian_id')->nullable();
                $table->unsignedBigInteger('komponen_penilaian_id')->nullable();
                $table->integer('skor')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();

                $table->foreign('hasil_pengujian_id')->references('id')->on('hasil_pengujian')->onDelete('cascade');
                $table->foreign('komponen_penilaian_id')->references('id')->on('komponen_penilaian')->onDelete('cascade');
            });
        }

        // 15) Finally add FK on users for fakultas_id & prodi_id (now that those tables exist)
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // only add FKs if columns exist and FK not already present
                if (Schema::hasColumn('users', 'fakultas_id')) {
                    try {
                        $table->foreign('fakultas_id')->references('id')->on('fakultas')->onDelete('set null');
                    } catch (\Throwable $e) { /* ignore if FK exists */ }
                }
                if (Schema::hasColumn('users', 'prodi_id')) {
                    try {
                        $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('set null');
                    } catch (\Throwable $e) { /* ignore if FK exists */ }
                }
            });
        }
    }

    public function down(): void
    {
        // drop in reverse order
        Schema::dropIfExists('penilaian_detail');
        Schema::dropIfExists('komponen_penilaian');
        Schema::dropIfExists('hasil_pengujian');
        Schema::dropIfExists('jadwal_pengujian');
        Schema::dropIfExists('rekrutasi_dosen');
        Schema::dropIfExists('mahasiswa_kompetisi');
        Schema::dropIfExists('kompetisi');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('tenaga_pendukung_akademik');
        Schema::dropIfExists('dosen');
        Schema::dropIfExists('kelompok_keahlian');
        Schema::dropIfExists('prodi');
        Schema::dropIfExists('fakultas');

        // attempt to drop added columns in users (safe)
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'nama_lengkap')) {
                    $table->dropColumn('nama_lengkap');
                }
                if (Schema::hasColumn('users', 'username')) {
                    $table->dropColumn('username');
                }
                if (Schema::hasColumn('users', 'fakultas_id')) {
                    // drop FK then column
                    try { $table->dropForeign(['fakultas_id']); } catch (\Throwable $e) {}
                    $table->dropColumn('fakultas_id');
                }
                if (Schema::hasColumn('users', 'prodi_id')) {
                    try { $table->dropForeign(['prodi_id']); } catch (\Throwable $e) {}
                    $table->dropColumn('prodi_id');
                }
            });
        }
    }
};
