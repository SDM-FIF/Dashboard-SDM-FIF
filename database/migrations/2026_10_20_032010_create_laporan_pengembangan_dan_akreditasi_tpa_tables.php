
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
        Schema::create('kegiatan_pengembangan_tpa', function (Blueprint $table) {
            $table->id();
            $table->string('kegiatan');
            $table->integer('tahun');
            $table->integer('jumlah')->default(0);
            $table->timestamps();

            $table->unique(['kegiatan', 'tahun']);
        });

        Schema::create('laporan_akreditasi_tpa', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_akreditasi_tpa');
        Schema::dropIfExists('kegiatan_pengembangan_tpa');
    }
};
