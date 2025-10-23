<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerRating extends Model
{
    protected $fillable = [
        'user_id',
        'partner_id',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Get the user that owns the rating
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the partner that owns the rating
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
