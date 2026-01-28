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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('id_jadwal_kuliah')->constrained('jadwal_kuliah')->cascadeOnDelete();
            $table->foreignId('id_mahasiswa')->constrained('mahasiswa')->cascadeOnDelete();
            
            $table->float('nilai_tugas')->default(0);
            $table->float('nilai_uts')->default(0);
            $table->float('nilai_uas')->default(0);
            
            $table->float('nilai_akhir')->nullable(); 
            $table->string('grade', 2)->nullable()->comment("Cth. Grade A, B, C, etc.");  
            
            $table->timestamps();

            $table->unique(['id_jadwal_kuliah', 'id_mahasiswa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
