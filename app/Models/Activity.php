<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'time',
        'category_id', 'user_id', 'likes_count', 'saves_count'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_activity')
                    ->withPivot('order')
                    ->withTimestamps();
    }
}

