<?php

namespace App\Services;

use App\Models\Objective;
use App\Models\Progress;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SmartAnalyticsService
{
    /**
     * Génère des insights intelligents pour un utilisateur
     */
    public function getUserInsights(User $user): array
    {
        $summary = $this->getPerformanceSummary($user);
        $progressByCategory = $this->getProgressByCategory($user, 30);
        $timeline = $this->getTimelineByCategory($user, 30);

        return [
            'performance_summary' => $summary,
            'trends' => $this->getUserTrends($user),
            'strengths' => $this->getUserStrengths($user),
            'improvements' => $this->getImprovementSuggestions($user),
            'predictions' => $this->getPredictions($user),
            'achievements' => $this->getRecentAchievements($user),
            'recommendations' => $this->getActionableRecommendations($user),
            // Nouveaux jeux de données pour les charts
            'progress_by_category' => $progressByCategory,
            'timeline_categories_30d' => $timeline,
        ];
    }
    
    /**
     * Résumé de performance de l'utilisateur
     */
    private function getPerformanceSummary(User $user): array
    {
        $objectives = $user->objectives;
        $totalObjectives = $objectives->count();
        $completedObjectives = $objectives->filter(function ($obj) use ($user) {
            return $obj->computeProgressPercent($user->id) >= 100;
        })->count();

        $avgProgress = $objectives->avg(function ($obj) use ($user) {
            return $obj->computeProgressPercent($user->id);
        }) ?? 0;

        $totalProgressEntries = Progress::where('user_id', $user->id)->count();
        $streakDays = $this->calculateCurrentStreak($user);

        // Ajout des métriques de catégories
        $categoryMetrics = $this->getCategoryMetrics($user);

        return [
            'total_objectives' => $totalObjectives,
            'completed_objectives' => $completedObjectives,
            'completion_rate' => $totalObjectives > 0 ? round(($completedObjectives / $totalObjectives) * 100, 1) : 0,
            'average_progress' => round($avgProgress, 1),
            'total_entries' => $totalProgressEntries,
            'current_streak' => $streakDays,
            'performance_score' => $this->calculatePerformanceScore($user),
            // Données pour les charts
            'category_breakdown' => $categoryMetrics['breakdown'],
            'sport_completion' => $categoryMetrics['completion_rates']['sport'] ?? 0,
            'education_completion' => $categoryMetrics['completion_rates']['education'] ?? 0,
            'health_completion' => $categoryMetrics['completion_rates']['health'] ?? 0,
            'other_completion' => $categoryMetrics['completion_rates']['other'] ?? 0,
        ];
    }
    
    /**
     * Tendances de l'utilisateur
     */
    private function getUserTrends(User $user): array
    {
        $last30Days = Progress::where('user_id', $user->id)
            ->where('entry_date', '>=', now()->subDays(30))
            ->get();
        
        $last7Days = $last30Days->where('entry_date', '>=', now()->subDays(7));
        $previous7Days = Progress::where('user_id', $user->id)
            ->whereBetween('entry_date', [now()->subDays(14), now()->subDays(7)])
            ->get();
        
        $weeklyGrowth = $this->calculateGrowth($last7Days, $previous7Days);
        $consistency = $this->calculateConsistency($last30Days);
        
        return [
            'weekly_growth' => $weeklyGrowth,
            'consistency_score' => $consistency,
            'activity_trend' => $this->getActivityTrend($user),
            'best_performing_category' => $this->getBestPerformingCategory($user),
            'improving_objectives' => $this->getImprovingObjectives($user)
        ];
    }
    
    /**
     * Points forts de l'utilisateur
     */
    private function getUserStrengths(User $user): array
    {
        $objectives = $user->objectives;
        $strengths = [];
        
        // Catégorie la plus performante
        $categoryPerformance = $objectives->groupBy('category')->map(function ($objs) use ($user) {
            return $objs->avg(function ($obj) use ($user) {
                return $obj->computeProgressPercent($user->id);
            });
        });
        
        $bestCategory = $categoryPerformance->sortDesc()->first();
        if ($bestCategory > 70) {
            $strengths[] = [
                'type' => 'category',
                'value' => $categoryPerformance->sortDesc()->keys()->first(),
                'score' => $bestCategory,
                'message' => "Excellente performance en {$categoryPerformance->sortDesc()->keys()->first()}"
            ];
        }
        
        // Consistance
        $consistency = $this->calculateConsistency(
            Progress::where('user_id', $user->id)->get()
        );
        
        if ($consistency > 0.7) {
            $strengths[] = [
                'type' => 'consistency',
                'value' => round($consistency * 100),
                'score' => $consistency,
                'message' => "Très régulier dans vos progrès"
            ];
        }
        
        // Streak
        $streak = $this->calculateCurrentStreak($user);
        if ($streak >= 7) {
            $strengths[] = [
                'type' => 'streak',
                'value' => $streak,
                'score' => min($streak / 30, 1),
                'message' => "Streak impressionnant de {$streak} jours"
            ];
        }
        
        return $strengths;
    }
    
    /**
     * Suggestions d'amélioration
     */
    private function getImprovementSuggestions(User $user): array
    {
        $suggestions = [];
        $objectives = $user->objectives;
        
        // Objectifs en retard
        $laggingObjectives = $objectives->filter(function ($obj) use ($user) {
            $progress = $obj->computeProgressPercent($user->id);
            $daysSinceStart = $obj->created_at->diffInDays(now());
            $expectedProgress = min(($daysSinceStart / 30) * 100, 100); // 30 jours pour atteindre l'objectif
            
            return $progress < $expectedProgress * 0.5; // 50% de l'attendu
        });
        
        foreach ($laggingObjectives as $objective) {
            $suggestions[] = [
                'type' => 'lagging_objective',
                'objective' => $objective,
                'message' => "L'objectif '{$objective->title}' nécessite plus d'attention",
                'priority' => 'high'
            ];
        }
        
        // Catégories négligées
        $categoryActivity = $objectives->groupBy('category')->map(function ($objs) use ($user) {
            $lastActivity = Progress::where('user_id', $user->id)
                ->whereIn('objective_id', $objs->pluck('id'))
                ->latest('entry_date')
                ->first();
            
            return $lastActivity ? $lastActivity->entry_date->diffInDays(now()) : 999;
        });
        
        $neglectedCategories = $categoryActivity->filter(function ($days) {
            return $days > 7;
        });
        
        foreach ($neglectedCategories as $category => $days) {
            $suggestions[] = [
                'type' => 'neglected_category',
                'category' => $category,
                'days' => $days,
                'message' => "Pas d'activité en {$category} depuis {$days} jours",
                'priority' => 'medium'
            ];
        }
        
        return $suggestions;
    }
    
    /**
     * Prédictions pour l'utilisateur
     */
    private function getPredictions(User $user): array
    {
        $objectives = $user->objectives;
        $predictions = [];
        
        foreach ($objectives as $objective) {
            $progress = $objective->computeProgressPercent($user->id);
            $recentProgress = Progress::where('user_id', $user->id)
                ->where('objective_id', $objective->id)
                ->where('entry_date', '>=', now()->subDays(7))
                ->get();
            
            if ($recentProgress->count() >= 3) {
                $avgDailyProgress = $recentProgress->avg('value');
                $remaining = $objective->target_value - $recentProgress->sum('value');
                
                if ($avgDailyProgress > 0) {
                    $daysToComplete = ceil($remaining / $avgDailyProgress);
                    
                    $predictions[] = [
                        'objective' => $objective,
                        'days_to_complete' => $daysToComplete,
                        'confidence' => $this->calculatePredictionConfidence($recentProgress),
                        'message' => "Objectif '{$objective->title}' atteint dans {$daysToComplete} jours"
                    ];
                }
            }
        }
        
        return $predictions;
    }
    
    /**
     * Réalisations récentes
     */
    private function getRecentAchievements(User $user): array
    {
        $recentBadges = UserBadge::where('user_id', $user->id)
            ->where('earned_at', '>=', now()->subDays(7))
            ->get();
        
        $achievements = [];
        
        foreach ($recentBadges as $badge) {
            $achievements[] = [
                'type' => 'badge',
                'title' => $badge->title,
                'description' => $badge->description,
                'icon' => $badge->icon,
                'color' => $badge->color,
                'earned_at' => $badge->earned_at
            ];
        }
        
        // Objectifs récemment complétés
        $recentlyCompleted = $user->objectives->filter(function ($obj) use ($user) {
            $progress = $obj->computeProgressPercent($user->id);
            $lastProgress = Progress::where('user_id', $user->id)
                ->where('objective_id', $obj->id)
                ->latest('entry_date')
                ->first();
            
            return $progress >= 100 && $lastProgress && $lastProgress->entry_date->isAfter(now()->subDays(7));
        });
        
        foreach ($recentlyCompleted as $objective) {
            $achievements[] = [
                'type' => 'objective_completed',
                'title' => "Objectif atteint: {$objective->title}",
                'description' => "Félicitations ! Vous avez atteint votre objectif de {$objective->target_value} {$objective->unit}",
                'icon' => 'fas fa-trophy',
                'color' => 'gold',
                'earned_at' => now()
            ];
        }
        
        return $achievements;
    }
    
    /**
     * Recommandations actionables
     */
    private function getActionableRecommendations(User $user): array
    {
        $recommendations = [];
        
        // Recommandation de consistance
        $consistency = $this->calculateConsistency(
            Progress::where('user_id', $user->id)->get()
        );
        
        if ($consistency < 0.5) {
            $recommendations[] = [
                'type' => 'consistency',
                'title' => 'Améliorer la régularité',
                'description' => 'Essayez de saisir vos progrès quotidiennement pour de meilleurs résultats',
                'action' => 'Ajouter un rappel quotidien',
                'priority' => 'high'
            ];
        }
        
        // Recommandation de diversité
        $categories = $user->objectives->pluck('category')->unique();
        if ($categories->count() < 3) {
            $recommendations[] = [
                'type' => 'diversity',
                'title' => 'Diversifier vos objectifs',
                'description' => 'Ajoutez des objectifs dans différentes catégories pour un bien-être complet',
                'action' => 'Explorer de nouveaux objectifs',
                'priority' => 'medium'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Calcule le streak actuel
     */
    private function calculateCurrentStreak(User $user): int
    {
        $progresses = Progress::where('user_id', $user->id)
            ->orderBy('entry_date', 'desc')
            ->get();
        
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
        
        return $streak;
    }
    
    /**
     * Calcule le score de performance global
     */
    private function calculatePerformanceScore(User $user): int
    {
        $objectives = $user->objectives;
        if ($objectives->isEmpty()) {
            return 0;
        }
        
        $avgProgress = $objectives->avg(function ($obj) use ($user) {
            return $obj->computeProgressPercent($user->id);
        });
        
        $consistency = $this->calculateConsistency(
            Progress::where('user_id', $user->id)->get()
        );
        
        $streak = $this->calculateCurrentStreak($user);
        $streakScore = min($streak / 30, 1) * 100; // Max 100 pour 30 jours
        
        return round(($avgProgress * 0.4) + ($consistency * 100 * 0.4) + ($streakScore * 0.2));
    }
    
    /**
     * Calcule la croissance entre deux périodes
     */
    private function calculateGrowth(Collection $current, Collection $previous): float
    {
        if ($current->isEmpty() || $previous->isEmpty()) {
            return 0;
        }
        
        $currentAvg = $current->avg('value');
        $previousAvg = $previous->avg('value');
        
        if ($previousAvg == 0) {
            return $currentAvg > 0 ? 100 : 0;
        }
        
        return (($currentAvg - $previousAvg) / $previousAvg) * 100;
    }
    
    /**
     * Calcule la consistance
     */
    private function calculateConsistency(Collection $progresses): float
    {
        if ($progresses->count() < 2) {
            return 0;
        }
        
        $dates = $progresses->pluck('entry_date')->sort();
        $intervals = [];
        
        for ($i = 1; $i < $dates->count(); $i++) {
            $intervals[] = $dates[$i]->diffInDays($dates[$i-1]);
        }
        
        if (empty($intervals)) {
            return 0;
        }
        
        $avgInterval = array_sum($intervals) / count($intervals);
        $variance = array_sum(array_map(function ($interval) use ($avgInterval) {
            return pow($interval - $avgInterval, 2);
        }, $intervals)) / count($intervals);
        
        return max(0, 1 - ($variance / 100));
    }
    
    /**
     * Obtient la tendance d'activité
     */
    private function getActivityTrend(User $user): string
    {
        $last7Days = Progress::where('user_id', $user->id)
            ->where('entry_date', '>=', now()->subDays(7))
            ->count();
        
        $previous7Days = Progress::where('user_id', $user->id)
            ->whereBetween('entry_date', [now()->subDays(14), now()->subDays(7)])
            ->count();
        
        if ($last7Days > $previous7Days * 1.1) {
            return 'increasing';
        } elseif ($last7Days < $previous7Days * 0.9) {
            return 'decreasing';
        } else {
            return 'stable';
        }
    }
    
    /**
     * Obtient la catégorie la plus performante
     */
    private function getBestPerformingCategory(User $user): ?string
    {
        $categoryPerformance = $user->objectives->groupBy('category')->map(function ($objs) use ($user) {
            return $objs->avg(function ($obj) use ($user) {
                return $obj->computeProgressPercent($user->id);
            });
        });
        
        return $categoryPerformance->sortDesc()->keys()->first();
    }
    
    /**
     * Obtient les objectifs en amélioration
     */
    private function getImprovingObjectives(User $user): Collection
    {
        return $user->objectives->filter(function ($obj) use ($user) {
            $recentProgress = Progress::where('user_id', $user->id)
                ->where('objective_id', $obj->id)
                ->where('entry_date', '>=', now()->subDays(7))
                ->get();
            
            $previousProgress = Progress::where('user_id', $user->id)
                ->where('objective_id', $obj->id)
                ->whereBetween('entry_date', [now()->subDays(14), now()->subDays(7)])
                ->get();
            
            if ($recentProgress->isEmpty() || $previousProgress->isEmpty()) {
                return false;
            }
            
            $recentAvg = $recentProgress->avg('value');
            $previousAvg = $previousProgress->avg('value');
            
            return $recentAvg > $previousAvg * 1.1; // 10% d'amélioration
        });
    }
    
    /**
     * Calcule la confiance d'une prédiction
     */
    private function calculatePredictionConfidence(Collection $recentProgress): float
    {
        if ($recentProgress->count() < 3) {
            return 0.3;
        }
        $values = $recentProgress->pluck('value')->map(function ($v) {
            return (float) $v;
        });

        $count = $values->count();
        $mean = $values->avg() ?: 0.0;

        // Manual population variance (safe fallback if Collection::variance is unavailable)
        $variance = 0.0;
        if ($count > 0) {
            $variance = $values->map(function ($v) use ($mean) {
                return pow($v - $mean, 2);
            })->sum() / $count;
        }
        
        if ($mean == 0) {
            return 0.3;
        }
        
        $coefficient = sqrt($variance) / $mean;
        
        // Plus le coefficient est bas, plus la confiance est élevée
        return max(0.3, min(0.9, 1 - $coefficient));
    }

// Normalise les libellés de catégories vers des clés canoniques
private function normalizeCategory(?string $raw): string
{
    $s = mb_strtolower(trim($raw ?? ''));
    $s = strtr($s, [
        'é' => 'e', 'è' => 'e', 'ê' => 'e',
        'à' => 'a', 'â' => 'a',
        'û' => 'u', 'ù' => 'u',
        'î' => 'i', 'ï' => 'i',
        'ô' => 'o', 'ö' => 'o',
    ]);

    if ($s === 'sport' || in_array($s, ['activite','activity'])) return 'sport';
    if (in_array($s, ['education', 'educ', 'etude', 'formation'])) return 'education';
    if (in_array($s, ['sante', 'health', 'sommeil', 'sleep'])) return 'health';
    if (in_array($s, ['autre', 'other', 'others', 'divers'])) return 'other';
    if (in_array($s, ['nutrition','alimentation','diet'])) return 'health';
    return 'other';
}

// Calcule la répartition par catégorie et les taux de complétion moyens par catégorie
private function getCategoryMetrics(User $user): array
{
    $objectives = $user->objectives;

    $categories = ['sport', 'education', 'health', 'other'];
    $counts = [
        'sport' => 0,
        'education' => 0,
        'health' => 0,
        'other' => 0,
    ];
    $progressBuckets = [
        'sport' => [],
        'education' => [],
        'health' => [],
        'other' => [],
    ];

    foreach ($objectives as $obj) {
        $cat = $this->normalizeCategory($obj->category ?? null);
        $counts[$cat]++;
        $progressBuckets[$cat][] = (float) ($obj->computeProgressPercent($user->id) ?? 0);
    }

    $total = max(1, array_sum($counts));

    $breakdown = [];
    $completionRates = [];

    foreach ($categories as $cat) {
        $breakdown[$cat] = round(($counts[$cat] / $total) * 100);
        $values = $progressBuckets[$cat];
        $avg = !empty($values) ? array_sum($values) / count($values) : 0;
        $completionRates[$cat] = round($avg);
    }

    return [
        'breakdown' => $breakdown,
        'completion_rates' => $completionRates,
    ];
}

// Somme des progrès par catégorie sur les N derniers jours
private function getProgressByCategory(User $user, int $days = 30): array
{
    $end = now();
    $start = now()->subDays($days - 1)->startOfDay();

    $progresses = Progress::where('user_id', $user->id)
        ->whereBetween('entry_date', [$start, $end])
        ->with('objective')
        ->get();

    $totals = [
        'sport' => 0,
        'education' => 0,
        'health' => 0,
        'other' => 0,
    ];

    foreach ($progresses as $p) {
        $cat = $this->normalizeCategory($p->objective->category ?? null);
        if (!isset($totals[$cat])) $cat = 'other';
        $totals[$cat] += (float) $p->value;
    }

    return $totals;
}

// Timeline des progrès par catégorie sur les N derniers jours
private function getTimelineByCategory(User $user, int $days = 30): array
{
    $end = now()->endOfDay();
    $start = now()->subDays($days - 1)->startOfDay();

    $progresses = Progress::where('user_id', $user->id)
        ->whereBetween('entry_date', [$start, $end])
        ->with('objective')
        ->get();

    // Préparer structure dates
    $dates = [];
    $series = [
        'sport' => [],
        'education' => [],
        'health' => [],
        'other' => [],
    ];

    $dayMap = [];
    for ($i = 0; $i < $days; $i++) {
        $d = $start->copy()->addDays($i);
        $key = $d->toDateString();
        $dates[] = $key;
        $dayMap[$key] = [
            'sport' => 0,
            'education' => 0,
            'health' => 0,
            'other' => 0,
        ];
    }

    foreach ($progresses as $p) {
        $dateKey = $p->entry_date->toDateString();
        $cat = $this->normalizeCategory($p->objective->category ?? null);
        if (!isset($dayMap[$dateKey])) continue;
        if (!isset($dayMap[$dateKey][$cat])) $cat = 'other';
        $dayMap[$dateKey][$cat] += (float) $p->value;
    }

    foreach ($dates as $key) {
        $series['sport'][] = $dayMap[$key]['sport'];
        $series['education'][] = $dayMap[$key]['education'];
        $series['health'][] = $dayMap[$key]['health'];
        $series['other'][] = $dayMap[$key]['other'];
    }

    return [
        'dates' => $dates,
        'series' => $series,
    ];
}
}
