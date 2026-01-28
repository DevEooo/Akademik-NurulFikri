<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penilaian extends Model
{
    use HasFactory;
    protected $table = 'penilaian';

    protected $fillable = [
        'id_jadwal_kuliah',
        'id_mahasiswa',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
    ];

    public function jadwalKuliah()
    {
        return $this->belongsTo(JadwalKuliah::class, 'id_jadwal_kuliah');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    protected static function booted()
    {
        static::saving(function ($penilaian) {
            // 1. Rumus Bobot (Contoh: Tugas 30%, UTS 30%, UAS 40%)
            $akhir = ($penilaian->nilai_tugas * 0.3) +
                     ($penilaian->nilai_uts * 0.3) +
                     ($penilaian->nilai_uas * 0.4);

            $penilaian->nilai_akhir = $akhir;

            // 2. Konversi ke Huruf
            $penilaian->grade = self::getGradeHuruf($akhir);
        });
    }

    public static function getGradeHuruf($nilai)
    {
        if ($nilai >= 85) return 'A';
        if ($nilai >= 80) return 'A-';
        if ($nilai >= 75) return 'B+';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 65) return 'B-';
        if ($nilai >= 60) return 'C';
        if ($nilai >= 45) return 'D';
        return 'E';
    }
}
