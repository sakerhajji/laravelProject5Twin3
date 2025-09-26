<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'goal_id', 'entry_date', 'value', 'note'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'value' => 'decimal:2',
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}


