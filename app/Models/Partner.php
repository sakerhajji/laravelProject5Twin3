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

    /**
     * Check if partner is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if partner is inactive
     */
    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    /**
     * Check if partner is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Get services attribute with proper decoding
     */
    public function getServicesAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        // Si c'est déjà un array, le retourner
        if (is_array($value)) {
            return $value;
        }

        // Si c'est une string JSON, la décoder
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            
            // Si le décodage a échoué, essayer de nettoyer et redécoder
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Nettoyer les caractères unicode mal encodés
                $cleaned = preg_replace('/\\\\u([0-9a-fA-F]{4})/', '&#x$1;', $value);
                $cleaned = html_entity_decode($cleaned, ENT_QUOTES, 'UTF-8');
                $decoded = json_decode($cleaned, true);
            }
            
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    /**
     * Get formatted opening hours for display
     */
    public function getFormattedOpeningHoursAttribute()
    {
        if (empty($this->opening_hours)) {
            return [];
        }

        $days = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi', 
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche'
        ];

        $formatted = [];
        $rawHours = $this->opening_hours;

        foreach ($days as $key => $label) {
            $dayData = null;
            $frenchKey = strtolower($label);
            
            // Check both English and French keys
            if (isset($rawHours[$key])) {
                $dayData = $rawHours[$key];
            } elseif (isset($rawHours[$frenchKey])) {
                $dayData = $rawHours[$frenchKey];
            }

            if ($dayData) {
                if (is_array($dayData)) {
                    // New format
                    if (isset($dayData['is_open']) && $dayData['is_open']) {
                        $hours = ($dayData['open_time'] ?? '09:00') . ' - ' . ($dayData['close_time'] ?? '18:00');
                        
                        if (isset($dayData['has_break']) && $dayData['has_break']) {
                            $breakStart = $dayData['break_start'] ?? '12:00';
                            $breakEnd = $dayData['break_end'] ?? '13:00';
                            $hours .= ' (pause: ' . $breakStart . '-' . $breakEnd . ')';
                        }
                        
                        $formatted[$key] = [
                            'label' => $label,
                            'hours' => $hours,
                            'is_open' => true
                        ];
                    } else {
                        $formatted[$key] = [
                            'label' => $label,
                            'hours' => 'Fermé',
                            'is_open' => false
                        ];
                    }
                } else {
                    // Old format - string like "10:00-19:00" or "Fermé"
                    $dayData = trim($dayData);
                    if ($dayData === 'Fermé' || $dayData === 'Ferme' || empty($dayData)) {
                        $formatted[$key] = [
                            'label' => $label,
                            'hours' => 'Fermé',
                            'is_open' => false
                        ];
                    } else {
                        $formatted[$key] = [
                            'label' => $label,
                            'hours' => $dayData,
                            'is_open' => true
                        ];
                    }
                }
            } else {
                $formatted[$key] = [
                    'label' => $label,
                    'hours' => 'Fermé',
                    'is_open' => false
                ];
            }
        }

        return $formatted;
    }

    /**
     * Get current day status (open/closed)
     */
    public function getCurrentDayStatusAttribute()
    {
        $currentDay = strtolower(date('l')); // monday, tuesday, etc.
        $currentTime = date('H:i');
        
        $rawHours = $this->opening_hours;
        if (empty($rawHours)) {
            return ['status' => 'closed', 'message' => 'Fermé'];
        }

        $dayData = null;
        $frenchDays = [
            'monday' => 'lundi',
            'tuesday' => 'mardi',
            'wednesday' => 'mercredi',
            'thursday' => 'jeudi',
            'friday' => 'vendredi',
            'saturday' => 'samedi',
            'sunday' => 'dimanche'
        ];

        // Check both English and French keys
        if (isset($rawHours[$currentDay])) {
            $dayData = $rawHours[$currentDay];
        } elseif (isset($rawHours[$frenchDays[$currentDay]])) {
            $dayData = $rawHours[$frenchDays[$currentDay]];
        }

        if (!$dayData) {
            return ['status' => 'closed', 'message' => 'Fermé'];
        }

        if (is_array($dayData)) {
            // New format
            if (!isset($dayData['is_open']) || !$dayData['is_open']) {
                return ['status' => 'closed', 'message' => 'Fermé'];
            }
            
            $openTime = $dayData['open_time'] ?? '09:00';
            $closeTime = $dayData['close_time'] ?? '18:00';
            
            // Check if currently within opening hours
            if ($currentTime >= $openTime && $currentTime <= $closeTime) {
                // Check if it's break time
                if (isset($dayData['has_break']) && $dayData['has_break']) {
                    $breakStart = $dayData['break_start'] ?? '12:00';
                    $breakEnd = $dayData['break_end'] ?? '13:00';
                    
                    if ($currentTime >= $breakStart && $currentTime <= $breakEnd) {
                        return ['status' => 'break', 'message' => 'En pause (ferme à ' . $breakEnd . ')'];
                    }
                }
                
                return ['status' => 'open', 'message' => 'Ouvert (ferme à ' . $closeTime . ')'];
            }
            
            return ['status' => 'closed', 'message' => 'Fermé (ouvre à ' . $openTime . ')'];
        } else {
            // Old format
            $dayData = trim($dayData);
            if ($dayData === 'Fermé' || $dayData === 'Ferme' || empty($dayData)) {
                return ['status' => 'closed', 'message' => 'Fermé'];
            } elseif (strpos($dayData, '-') !== false) {
                // Format like "10:00-19:00"
                $times = explode('-', $dayData);
                $openTime = trim($times[0]);
                $closeTime = trim($times[1]);
                
                if ($currentTime >= $openTime && $currentTime <= $closeTime) {
                    return ['status' => 'open', 'message' => 'Ouvert (ferme à ' . $closeTime . ')'];
                }
                
                return ['status' => 'closed', 'message' => 'Fermé (ouvre à ' . $openTime . ')'];
            }
        }
        
        return ['status' => 'closed', 'message' => 'Fermé'];
    }
}