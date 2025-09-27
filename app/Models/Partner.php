<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_DOCTOR = 'doctor';
    const TYPE_GYM = 'gym';
    const TYPE_LABORATORY = 'laboratory';
    const TYPE_PHARMACY = 'pharmacy';
    const TYPE_NUTRITIONIST = 'nutritionist';
    const TYPE_PSYCHOLOGIST = 'psychologist';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_PENDING = 'pending';

    protected $fillable = [
        'name',
        'type',
        'description',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'website',
        'license_number',
        'specialization',
        'status',
        'contact_person',
        'logo',
        'rating',
        'opening_hours',
        'services',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'services' => 'array',
        'rating' => 'decimal:1',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Get the partner types
     */
    public static function getTypes()
    {
        return [
            self::TYPE_DOCTOR => 'Médecin',
            self::TYPE_GYM => 'Salle de sport',
            self::TYPE_LABORATORY => "Laboratoire d'analyse",
            self::TYPE_PHARMACY => 'Pharmacie',
            self::TYPE_NUTRITIONIST => 'Nutritionniste',
            self::TYPE_PSYCHOLOGIST => 'Psychologue',
        ];
    }

    /**
     * Get the partner statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Actif',
            self::STATUS_INACTIVE => 'Inactif',
            self::STATUS_PENDING => 'En attente',
        ];
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute()
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Scope for active partners
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get users who favorited this partner
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'partner_favorites')->withTimestamps();
    }
}