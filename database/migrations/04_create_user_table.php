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
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fakultas_id')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->unsignedBigInteger('role_id');
            $table->string('nama_lengkap');
            $table->string('username');
            $table->string('password');
            $table->timestamps();

            $table->foreign('fakultas_id')->references('id')->on('fakultas')->onDelete('set null');
            $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('set null');
            // Tambahkan onDelete cascade untuk role
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};