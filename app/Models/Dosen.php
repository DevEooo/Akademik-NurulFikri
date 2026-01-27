<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
    use HasFactory;
    protected $table = 'dosen';
    protected $fillable = ['nidn', 'nama_lengkap', 'gelar_depan', 'gelar_belakang', 'email', 'nomor_telepon', 'alamat'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
