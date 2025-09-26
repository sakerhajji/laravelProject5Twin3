<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'objective_id',
        'badge_type',
        'title',
        'description',
        'icon',
        'color',
        'metadata',
        'earned_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'earned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function objective()
    {
        return $this->belongsTo(Objective::class);
    }

    /**
     * Types de badges disponibles
     */
    public static function getBadgeTypes()
    {
        return [
            'streak_7' => [
                'title' => 'Streak 7 jours',
                'description' => '7 jours consécutifs de progrès',
                'icon' => 'fas fa-fire',
                'color' => 'orange',
            ],
            'streak_30' => [
                'title' => 'Streak 30 jours',
                'description' => '30 jours consécutifs de progrès',
                'icon' => 'fas fa-fire',
                'color' => 'red',
            ],
            'goal_achieved' => [
                'title' => 'Objectif atteint',
                'description' => 'Objectif 100% accompli',
                'icon' => 'fas fa-trophy',
                'color' => 'gold',
            ],
            'first_progress' => [
                'title' => 'Premier pas',
                'description' => 'Premier progrès enregistré',
                'icon' => 'fas fa-baby',
                'color' => 'blue',
            ],
            'consistency' => [
                'title' => 'Régularité',
                'description' => 'Progrès réguliers sur 2 semaines',
                'icon' => 'fas fa-calendar-check',
                'color' => 'green',
            ],
        ];
    }

    /**
     * Vérifier et attribuer des badges automatiquement
     */
    public static function checkAndAwardBadges($userId, $objectiveId = null)
    {
        $user = User::find($userId);
        if (!$user) return;

        // Badge premier progrès
        if ($objectiveId) {
            $hasFirstProgress = self::where('user_id', $userId)
                ->where('objective_id', $objectiveId)
                ->where('badge_type', 'first_progress')
                ->exists();

            if (!$hasFirstProgress) {
                $progressCount = Progress::where('user_id', $userId)
                    ->where('objective_id', $objectiveId)
                    ->count();

                if ($progressCount >= 1) {
                    self::create([
                        'user_id' => $userId,
                        'objective_id' => $objectiveId,
                        'badge_type' => 'first_progress',
                        'title' => 'Premier pas',
                        'description' => 'Premier progrès enregistré',
                        'icon' => 'fas fa-baby',
                        'color' => 'blue',
                        'earned_at' => now(),
                    ]);
                }
            }
        }

        // Badge streak 7 jours
        $streak7 = self::calculateStreak($userId, $objectiveId, 7);
        if ($streak7 >= 7) {
            $hasStreak7 = self::where('user_id', $userId)
                ->where('objective_id', $objectiveId)
                ->where('badge_type', 'streak_7')
                ->exists();

            if (!$hasStreak7) {
                self::create([
                    'user_id' => $userId,
                    'objective_id' => $objectiveId,
                    'badge_type' => 'streak_7',
                    'title' => 'Streak 7 jours',
                    'description' => '7 jours consécutifs de progrès',
                    'icon' => 'fas fa-fire',
                    'color' => 'orange',
                    'metadata' => ['streak_days' => $streak7],
                    'earned_at' => now(),
                ]);
            }
        }

        // Badge streak 30 jours
        $streak30 = self::calculateStreak($userId, $objectiveId, 30);
        if ($streak30 >= 30) {
            $hasStreak30 = self::where('user_id', $userId)
                ->where('objective_id', $objectiveId)
                ->where('badge_type', 'streak_30')
                ->exists();

            if (!$hasStreak30) {
                self::create([
                    'user_id' => $userId,
                    'objective_id' => $objectiveId,
                    'badge_type' => 'streak_30',
                    'title' => 'Streak 30 jours',
                    'description' => '30 jours consécutifs de progrès',
                    'icon' => 'fas fa-fire',
                    'color' => 'red',
                    'metadata' => ['streak_days' => $streak30],
                    'earned_at' => now(),
                ]);
            }
        }

        // Badge objectif atteint
        if ($objectiveId) {
            $objective = Objective::find($objectiveId);
            if ($objective) {
                $totalProgress = Progress::where('user_id', $userId)
                    ->where('objective_id', $objectiveId)
                    ->sum('value');

                $percentage = ($totalProgress / $objective->target_value) * 100;

                if ($percentage >= 100) {
                    $hasGoalAchieved = self::where('user_id', $userId)
                        ->where('objective_id', $objectiveId)
                        ->where('badge_type', 'goal_achieved')
                        ->exists();

                    if (!$hasGoalAchieved) {
                        self::create([
                            'user_id' => $userId,
                            'objective_id' => $objectiveId,
                            'badge_type' => 'goal_achieved',
                            'title' => 'Objectif atteint',
                            'description' => 'Objectif 100% accompli',
                            'icon' => 'fas fa-trophy',
                            'color' => 'gold',
                            'metadata' => ['percentage' => $percentage],
                            'earned_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Calculer le streak de jours consécutifs
     */
    public static function calculateStreak($userId, $objectiveId = null, $maxDays = 30)
    {
        $query = Progress::where('user_id', $userId)
            ->orderBy('entry_date', 'desc');

        if ($objectiveId) {
            $query->where('objective_id', $objectiveId);
        }

        $progresses = $query->get();
        $streak = 0;
        $currentDate = now()->toDateString();

        foreach ($progresses as $progress) {
            $progressDate = $progress->entry_date->toDateString();
            
            if ($progressDate === $currentDate) {
                $streak++;
                $currentDate = now()->subDays($streak)->toDateString();
            } else {
                break;
            }
        }

        return min($streak, $maxDays);
    }
}