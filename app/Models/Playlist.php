<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'subtitle', 'description', 'image'];

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'playlist_activity')
                    ->withPivot('order')
                    ->withTimestamps();
    }
}

