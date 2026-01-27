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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nim')->unique();
            $table->string('nama_lengkap');
            $table->string('angkatan'); 
            $table->enum('status', ['aktif', 'lulus', 'dropout'])->default('aktif');

            $table->foreignId('program_studi_id')->constrained('program_studi');
            $table->foreignId('dosen_wali_id')->nullable()->constrained('dosen'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
