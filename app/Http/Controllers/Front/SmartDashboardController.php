<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\SmartRecommendationService;
use App\Services\SmartAnalyticsService;
use App\Models\Objective;
use App\Models\Progress;
use App\Models\UserBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmartDashboardController extends Controller
{
    protected $recommendationService;
    protected $analyticsService;
    
    public function __construct(
        SmartRecommendationService $recommendationService,
        SmartAnalyticsService $analyticsService
    ) {
        $this->recommendationService = $recommendationService;
        $this->analyticsService = $analyticsService;
    }
    
    /**
     * Dashboard intelligent avec insights et recommandations
     */
    public function index()
    {
        $user = Auth::user();
        
        // Données de base
        $myObjectives = $user->objectives()->with(['progresses' => function($q) use ($user) {
            $q->where('user_id', $user->id)->latest('entry_date');
        }])->get();
        
        // Services intelligents
        $insights = $this->analyticsService->getUserInsights($user);
        $recommendations = $this->recommendationService->getRecommendationsForUser($user, 3);
        
        // Données pour les graphiques
        $chartData = $this->getChartData($user);
        
        // Objectifs récents
        $recentProgress = Progress::where('user_id', $user->id)
            ->with('objective')
            ->latest('entry_date')
            ->limit(5)
            ->get();
        
        // Badges récents
        $recentBadges = UserBadge::where('user_id', $user->id)
            ->latest('earned_at')
            ->limit(3)
            ->get();
        
        return view('front.smart-dashboard.index', compact(
            'myObjectives',
            'insights',
            'recommendations',
            'chartData',
            'recentProgress',
            'recentBadges'
        ));
    }
    
    /**
     * API pour obtenir des recommandations en temps réel
     */
    public function getRecommendations(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 5);
        
        $recommendations = $this->recommendationService->getRecommendationsForUser($user, $limit);
        
        return response()->json([
            'success' => true,
            'recommendations' => $recommendations->map(function ($rec) {
                return [
                    'id' => $rec->objective->id,
                    'title' => $rec->objective->title,
                    'description' => $rec->objective->description,
                    'category' => $rec->objective->category,
                    'cover_url' => $rec->objective->cover_url,
                    'score' => round($rec->score, 2),
                    'reason' => $rec->reason,
                    'type' => $rec->type,
                    'success_probability' => $this->recommendationService->predictSuccessProbability(Auth::user(), $rec->objective)
                ];
            })
        ]);
    }
    
    /**
     * API pour obtenir des insights en temps réel
     */
    public function getInsights(Request $request)
    {
        $user = Auth::user();
        $insights = $this->analyticsService->getUserInsights($user);
        
        return response()->json([
            'success' => true,
            'insights' => $insights
        ]);
    }
    
    /**
     * API pour obtenir des prédictions
     */
    public function getPredictions(Request $request)
    {
        $user = Auth::user();
        $insights = $this->analyticsService->getUserInsights($user);
        
        return response()->json([
            'success' => true,
            'predictions' => $insights['predictions']
        ]);
    }
    
    /**
     * Génère les données pour les graphiques
     */
    private function getChartData($user)
    {
        // Données des 30 derniers jours
        $last30Days = Progress::where('user_id', $user->id)
            ->where('entry_date', '>=', now()->subDays(30))
            ->with('objective')
            ->get()
            ->groupBy('entry_date');
        
        $labels = [];
        $dailyValues = [];
        $cumulativeValues = [];
        $cumulative = 0;
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M j');
            
            $dayValue = $last30Days->get($date, collect())->sum('value');
            $dailyValues[] = $dayValue;
            
            $cumulative += $dayValue;
            $cumulativeValues[] = $cumulative;
        }
        
        // Données par catégorie
        $categoryData = $user->objectives->groupBy('category')->map(function ($objectives) use ($user) {
            return [
                'name' => $objectives->first()->category,
                'progress' => $objectives->avg(function ($obj) use ($user) {
                    return $obj->computeProgressPercent($user->id);
                }),
                'count' => $objectives->count()
            ];
        })->values();
        
        // Données de performance hebdomadaire
        $weeklyData = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            
            $weekProgress = Progress::where('user_id', $user->id)
                ->whereBetween('entry_date', [$weekStart, $weekEnd])
                ->sum('value');
            
            $weeklyData[] = [
                'week' => $weekStart->format('M j'),
                'value' => $weekProgress
            ];
        }
        
        return [
            'daily' => [
                'labels' => $labels,
                'daily' => $dailyValues,
                'cumulative' => $cumulativeValues
            ],
            'categories' => $categoryData,
            'weekly' => $weeklyData
        ];
    }
}