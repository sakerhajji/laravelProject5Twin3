<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Objective extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','unit','target_value','category'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_objectives')
            ->withPivot(['status'])->withTimestamps();
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(Progress::class);
    }
}


