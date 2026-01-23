<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kelompok_keahlian', function (Blueprint $table) {
            // Menambahkan kolom singkatan setelah ID
            $table->string('singkatan', 20)->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('kelompok_keahlian', function (Blueprint $table) {
            $table->dropColumn('singkatan');
        });
    }
};
