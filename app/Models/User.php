<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Objective;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'birth_date',
    ];

    public function badges()
    {
        return $this->hasMany(UserBadge::class);
    }

    /**
     * Get the partners favorited by this user
     */
    public function favoritedPartners()
    {
        return $this->belongsToMany(Partner::class, 'partner_favorites')->withTimestamps();
    }

    /**
     * Objectives assigned to the user (many-to-many through user_objectives pivot).
     */
    public function objectives(): BelongsToMany
    {
        return $this->belongsToMany(Objective::class, 'user_objectives')
            ->withPivot(['status'])
            ->withTimestamps();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }
}
