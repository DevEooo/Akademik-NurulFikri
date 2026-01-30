<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManajemenKonten extends Model
{
    use HasFactory;

    protected $table = 'manajemen_konten';
    protected $guarded = [];
    protected $casts = [
        'konten' => 'array', 
        'is_published' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(ManajemenKonten::class, 'id_parent');
    }

    public function children()
    {
        return $this->hasMany(ManajemenKonten::class, 'id_parent')->orderBy('sort_order');
    }
}
