<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aliment extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'calories',
        'proteines',
        'glucides',
        'lipides',
        'image_path',
    ];

    public function repas()
    {
        return $this->belongsToMany(Repas::class, 'aliment_repas')->withPivot('quantite')->withTimestamps();
    }
}
