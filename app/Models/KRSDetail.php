<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KRSDetail extends Model
{
    use HasFactory;
    protected $table = 'krs_detail';

    protected $fillable = [
        'id_krs',
        'id_jadwal_kuliah',
    ];
    public function krs()
    {
        return $this->belongsTo(KRS::class, 'id_krs');
    }

    public function jadwalKuliah()
    {
        return $this->belongsTo(JadwalKuliah::class, 'id_jadwal_kuliah');
    }
}
