<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progress extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'progresses';

    protected $fillable = ['user_id','objective_id','entry_date','value','note'];

    protected $casts = [
        'entry_date' => 'date',
        'value' => 'decimal:2',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function objective(): BelongsTo { return $this->belongsTo(Objective::class); }
}


