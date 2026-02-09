<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calon_dosen', function (Blueprint $table) {
            $table->string('front_title')->nullable()->after('prodi_id');
            $table->string('back_title')->nullable()->after('nama');
        });
    }

    public function down(): void
    {
        Schema::table('calon_dosen', function (Blueprint $table) {
            $table->dropColumn(['front_title', 'back_title']);
        });
    }
};
