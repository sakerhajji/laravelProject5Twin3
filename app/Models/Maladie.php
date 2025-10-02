<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maladie extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'nom',
        'description',
        'traitement',       // description du traitement
        'prevention',       // mesures de prévention
        'status',
    ];

    protected $casts = [
        'traitement' => 'string',
        'prevention' => 'string',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Relations
     * A maladie can have many asymptomes
     */
    public function asymptomes()
    {
        return $this->belongsToMany(Asymptome::class, 'asymptome_maladie')->withTimestamps();
    }

    /**
     * Get available statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Actif',
            self::STATUS_INACTIVE => 'Inactif',
        ];
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Scope for active maladies
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Check if maladie is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if maladie is inactive
     */
    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'maladie_user')->withTimestamps();
    }
    public function asymptote()
    {
        return $this->belongsToMany(Asymptome::class, 'asymptome_maladie')->withTimestamps();
    }

}
