<?php

namespace App\Services;

use App\Models\Objective;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Support\Collection;

class SmartRecommendationService
{
    /**
     * Génère des recommandations intelligentes pour un utilisateur
     */
    public function getRecommendationsForUser(User $user, int $limit = 5): Collection
    {
        $recommendations = collect();
        
        // 1. Recommandations basées sur l'historique
        $historyBased = $this->getHistoryBasedRecommendations($user);
        $recommendations = $recommendations->merge($historyBased);
        
        // 2. Recommandations basées sur les objectifs similaires
        $similarBased = $this->getSimilarUserRecommendations($user);
        $recommendations = $recommendations->merge($similarBased);
        
        // 3. Recommandations basées sur les tendances
        $trendBased = $this->getTrendBasedRecommendations($user);
        $recommendations = $recommendations->merge($trendBased);
        
        // 4. Recommandations basées sur le profil utilisateur
        $profileBased = $this->getProfileBasedRecommendations($user);
        $recommendations = $recommendations->merge($profileBased);
        
        return $recommendations
            ->unique('id')
            ->sortByDesc('score')
            ->take($limit);
    }
    
    /**
     * Recommandations basées sur l'historique de l'utilisateur
     */
    private function getHistoryBasedRecommendations(User $user): Collection
    {
        $userObjectives = $user->objectives()->pluck('objective_id');
        $userProgress = Progress::where('user_id', $user->id)
            ->whereIn('objective_id', $userObjectives)
            ->get();
        
        if ($userProgress->isEmpty()) {
            return collect();
        }
        
        // Analyser les catégories préférées
        $categoryStats = $userProgress->groupBy('objective.category')
            ->map(function ($progresses) {
                return [
                    'count' => $progresses->count(),
                    'avg_value' => $progresses->avg('value'),
                    'consistency' => $this->calculateConsistency($progresses)
                ];
            });
        
        $preferredCategories = $categoryStats->sortByDesc('consistency')->keys()->take(2);
        
        return Objective::whereNotIn('id', $userObjectives)
            ->whereIn('category', $preferredCategories)
            ->get()
            ->map(function ($objective) use ($categoryStats) {
                $category = $objective->category;
                $score = $categoryStats->get($category)['consistency'] ?? 0;
                
                return (object) [
                    'id' => $objective->id,
                    'objective' => $objective,
                    'score' => $score,
                    'reason' => "Basé sur votre historique en {$category}",
                    'type' => 'history'
                ];
            });
    }
    
    /**
     * Recommandations basées sur des utilisateurs similaires
     */
    private function getSimilarUserRecommendations(User $user): Collection
    {
        $userObjectives = $user->objectives()->pluck('objective_id');
        
        // Trouver des utilisateurs avec des objectifs similaires
        $similarUsers = User::whereHas('objectives', function ($query) use ($userObjectives) {
            $query->whereIn('objective_id', $userObjectives);
        })
        ->where('id', '!=', $user->id)
        ->with('objectives')
        ->get();
        
        if ($similarUsers->isEmpty()) {
            return collect();
        }
        
        // Trouver des objectifs populaires parmi les utilisateurs similaires
        $popularObjectives = collect();
        foreach ($similarUsers as $similarUser) {
            $similarUserObjectives = $similarUser->objectives->pluck('objective_id');
            $newObjectives = $similarUserObjectives->diff($userObjectives);
            $popularObjectives = $popularObjectives->merge($newObjectives);
        }
        
        $objectiveCounts = $popularObjectives->countBy();
        $topObjectives = $objectiveCounts->sortDesc()->take(3)->keys();
        
        return Objective::whereIn('id', $topObjectives)
            ->get()
            ->map(function ($objective) use ($objectiveCounts) {
                $count = $objectiveCounts->get($objective->id, 0);
                $score = $count * 0.3; // Score basé sur la popularité
                
                return (object) [
                    'id' => $objective->id,
                    'objective' => $objective,
                    'score' => $score,
                    'reason' => "Populaire parmi les utilisateurs similaires ({$count} utilisateurs)",
                    'type' => 'similar_users'
                ];
            });
    }
    
    /**
     * Recommandations basées sur les tendances globales
     */
    private function getTrendBasedRecommendations(User $user): Collection
    {
        $userObjectives = $user->objectives()->pluck('objective_id');
        
        // Analyser les tendances des 30 derniers jours
        $recentProgress = Progress::where('entry_date', '>=', now()->subDays(30))
            ->whereNotIn('objective_id', $userObjectives)
            ->with('objective')
            ->get();
        
        $trendingObjectives = $recentProgress->groupBy('objective_id')
            ->map(function ($progresses) {
                return [
                    'count' => $progresses->count(),
                    'growth' => $this->calculateGrowth($progresses),
                    'avg_value' => $progresses->avg('value')
                ];
            })
            ->sortByDesc('growth')
            ->take(3);
        
        return Objective::whereIn('id', $trendingObjectives->keys())
            ->get()
            ->map(function ($objective) use ($trendingObjectives) {
                $trend = $trendingObjectives->get($objective->id);
                $score = $trend['growth'] * 0.4;
                
                return (object) [
                    'id' => $objective->id,
                    'objective' => $objective,
                    'score' => $score,
                    'reason' => "Tendance croissante (+{$trend['growth']}% cette semaine)",
                    'type' => 'trending'
                ];
            });
    }
    
    /**
     * Recommandations basées sur le profil utilisateur
     */
    private function getProfileBasedRecommendations(User $user): Collection
    {
        $userObjectives = $user->objectives()->pluck('objective_id');
        
        // Recommandations basées sur l'âge, le rôle, etc.
        $recommendations = collect();
        
        // Si l'utilisateur n'a pas d'objectifs de santé, recommander
        if (!$user->objectives()->where('category', 'sante')->exists()) {
            $healthObjectives = Objective::where('category', 'sante')
                ->whereNotIn('id', $userObjectives)
                ->take(2)
                ->get();
            
            $recommendations = $recommendations->merge(
                $healthObjectives->map(function ($objective) {
                    return (object) [
                        'id' => $objective->id,
                        'objective' => $objective,
                        'score' => 0.8,
                        'reason' => "Important pour votre bien-être général",
                        'type' => 'profile'
                    ];
                })
            );
        }
        
        // Si l'utilisateur n'a pas d'objectifs d'activité, recommander
        if (!$user->objectives()->where('category', 'activite')->exists()) {
            $activityObjectives = Objective::where('category', 'activite')
                ->whereNotIn('id', $userObjectives)
                ->take(2)
                ->get();
            
            $recommendations = $recommendations->merge(
                $activityObjectives->map(function ($objective) {
                    return (object) [
                        'id' => $objective->id,
                        'objective' => $objective,
                        'score' => 0.7,
                        'reason' => "Excellent pour maintenir une activité physique",
                        'type' => 'profile'
                    ];
                })
            );
        }
        
        return $recommendations;
    }
    
    /**
     * Calcule la consistance d'un utilisateur sur un objectif
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
        
        // Score de consistance (plus c'est bas, plus c'est consistant)
        return max(0, 1 - ($variance / 100));
    }
    
    /**
     * Calcule la croissance d'un objectif
     */
    private function calculateGrowth(Collection $progresses): float
    {
        if ($progresses->count() < 2) {
            return 0;
        }
        
        $sorted = $progresses->sortBy('entry_date');
        $firstWeek = $sorted->take(ceil($sorted->count() / 2));
        $lastWeek = $sorted->skip(floor($sorted->count() / 2));
        
        $firstAvg = $firstWeek->avg('value');
        $lastAvg = $lastWeek->avg('value');
        
        if ($firstAvg == 0) {
            return $lastAvg > 0 ? 100 : 0;
        }
        
        return (($lastAvg - $firstAvg) / $firstAvg) * 100;
    }
    
    /**
     * Prédit la probabilité de succès d'un objectif pour un utilisateur
     */
    public function predictSuccessProbability(User $user, Objective $objective): float
    {
        $userObjectives = $user->objectives()->pluck('objective_id');
        
        // Si l'utilisateur a déjà cet objectif, analyser ses progrès
        if ($userObjectives->contains($objective->id)) {
            $progresses = Progress::where('user_id', $user->id)
                ->where('objective_id', $objective->id)
                ->get();
            
            if ($progresses->isEmpty()) {
                return 0.5; // Probabilité neutre
            }
            
            $consistency = $this->calculateConsistency($progresses);
            $progress = $objective->computeProgressPercent($user->id);
            
            return ($consistency * 0.6) + (($progress / 100) * 0.4);
        }
        
        // Sinon, prédire basé sur les objectifs similaires
        $similarObjectives = $user->objectives()
            ->where('category', $objective->category)
            ->get();
        
        if ($similarObjectives->isEmpty()) {
            return 0.6; // Probabilité légèrement positive pour nouveaux objectifs
        }
        
        $avgSuccess = $similarObjectives->map(function ($obj) use ($user) {
            $progresses = Progress::where('user_id', $user->id)
                ->where('objective_id', $obj->id)
                ->get();
            
            if ($progresses->isEmpty()) {
                return 0.5;
            }
            
            $consistency = $this->calculateConsistency($progresses);
            $progress = $obj->computeProgressPercent($user->id);
            
            return ($consistency * 0.6) + (($progress / 100) * 0.4);
        })->avg();
        
        return $avgSuccess;
    }
}
