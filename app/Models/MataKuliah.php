<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MataKuliah extends Model
{
    use HasFactory;
    protected $table = 'mata_kuliah';

    protected $fillable = [
        'id_program_studi',
        'kode_matkul',
        'nama_matkul',
        'sks',
        'deskripsi_matkul',
    ];

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'id_program_studi');
    }

    public function getNamaMkAttribute(): string
    {
        return $this->nama_matkul;
    }

    public function getKodeMkAttribute(): string
    {
        return $this->kode_matkul;
    }
}
