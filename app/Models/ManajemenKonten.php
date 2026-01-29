<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManajemenKonten extends Model
{
    use HasFactory;

    protected $table = 'manajemen_konten';

    protected $casts = [
        'content' => 'array', 
        'is_published' => 'boolean',
    ];

    // Relasi ke Parent (Contoh: Visi Misi punya parent Profil)
    public function parent()
    {
        return $this->belongsTo(ManajemenKonten::class, 'id_parent');
    }

    // Relasi ke Child (Contoh: Profil punya child Visi Misi, Sejarah)
    public function children()
    {
        return $this->hasMany(ManajemenKonten::class, 'id_parent');
    }
}
