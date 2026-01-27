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
       Schema::create('krs', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('id_mahasiswa')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('id_tahun_ajaran')->constrained('tahun_ajaran')->cascadeOnDelete();
            
            $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak'])->default('draft');
            $table->text('catatan_pa')->nullable();
            $table->timestamps();

            $table->unique(['id_mahasiswa', 'id_tahun_ajaran']);
        });

        Schema::create('krs_detail', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('id_krs')->constrained('krs')->cascadeOnDelete();
            $table->foreignId('id_jadwal_kuliah')->constrained('jadwal_kuliah')->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs_detail');
        Schema::dropIfExists('krs');
    }
};
