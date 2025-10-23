<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'user_id',
    ];

    public function aliments()
    {
        return $this->belongsToMany(Aliment::class, 'aliment_repas')->withPivot('quantite')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}