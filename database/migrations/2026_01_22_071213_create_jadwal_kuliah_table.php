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
        Schema::create('jadwal_kuliah', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_tahun_ajaran')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('id_mata_kuliah')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->foreignId('id_dosen')->constrained('dosen')->cascadeOnDelete();
            $table->foreignId('id_ruangan')->constrained('ruangan')->cascadeOnDelete();

            $table->string('kelas'); // Contoh: TI-01, SI-Pagi
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('kuota_kelas')->default(40);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_kuliah');
    }
};
