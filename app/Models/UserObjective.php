<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserObjective extends Model
{
    use HasFactory;

    protected $table = 'user_objectives';
    protected $fillable = ['user_id','objective_id','status'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function objective(): BelongsTo { return $this->belongsTo(Objective::class); }
}


