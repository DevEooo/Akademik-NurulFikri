<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KRS extends Model
{
    use HasFactory;
    protected $table = 'krs';

    protected $fillable = [
        'id_mahasiswa',
        'id_tahun_ajaran',
        'status',
        'catatan_pa',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    public function details()
    {
        return $this->hasMany(KRSDetail::class, 'id_krs');
    }

    // Helper untuk menghitung Total SKS otomatis
    public function getTotalSksAttribute()
    {
        return $this->details->sum(function ($detail) {
            return $detail->jadwalKuliah->mataKuliah->sks ?? 0;
        });
    }
}
