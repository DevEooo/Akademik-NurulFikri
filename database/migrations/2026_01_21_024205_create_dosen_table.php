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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->string('nidn')->unique()->comment('Nomor Induk Dosen Nasional');
            $table->string('nama_lengkap');
            $table->string('gelar_depan');
            $table->string('gelar_belakang')->nullable();
            $table->string('email');
            $table->string('nomor_telepon');
            $table->text('alamat');
            // $table->string('foto_profil'); || Saya komen saja kalau mau pakai foto_profil
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
