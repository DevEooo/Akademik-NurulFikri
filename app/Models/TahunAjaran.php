<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunAjaran extends Model
{
    use HasFactory;
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun',
        'semester',
        'is_active',
    ];

    public function getNamaAttribute(): string
    {
        return $this->tahun . ' ' . $this->semester;
    }
}
