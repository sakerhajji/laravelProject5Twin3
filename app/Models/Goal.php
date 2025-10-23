<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'type', 'target_value', 'unit', 'start_date', 'end_date', 'status'
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(GoalEntry::class);
    }

    public function getCurrentProgressAttribute(): float
    {
        $sum = (float) $this->entries()->sum('value');
        if ($this->target_value <= 0) {
            return 0.0;
        }
        $pct = ($sum / (float) $this->target_value) * 100.0;
        return round(min(100.0, $pct), 2);
    }
}


