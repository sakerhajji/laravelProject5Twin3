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
use App\Services\ChatbotService;


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
        $progress = Progress::where('user_id', $user->id)
            ->where('entry_date', '>=', now()->subDays(30))
            ->get()
            ->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->entry_date)->format('Y-m-d');
            })
            ->map(function ($day) {
                return $day->sum('value');
            });

        return $progress;
    }

    public function chatbotMessage(Request $request, ChatbotService $chatbotService)
{
    $userMessage = $request->input('message');

    if (!$userMessage) {
        return response()->json(['error' => 'Message vide.'], 400);
    }

    $botReply = $chatbotService->sendMessage($userMessage);

    return response()->json([
        'reply' => $botReply
    ]);
}
public function saveSchedule(Request $request)
{
    try {
        $schedule = $request->input('schedule', []);
        $userId = auth()->id();
        
        // Sauvegarder dans la session
        session(['objective_schedule_' . $userId => $schedule]);
        
        // OU sauvegarder en base de données si vous avez une table
        // DB::table('objective_schedules')->updateOrInsert(
        //     ['user_id' => $userId],
        //     ['schedule' => json_encode($schedule), 'updated_at' => now()]
        // );
        
        return response()->json([
            'success' => true,
            'message' => 'Planning enregistré avec succès'
        ]);
    } catch (\Exception $e) {
        \Log::error('Erreur saveSchedule: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function getSchedule()
{
    try {
        $userId = auth()->id();
        
        // Récupérer depuis la session
        $schedule = session('objective_schedule_' . $userId, []);
        
        // OU récupérer depuis la base de données
        // $scheduleData = DB::table('objective_schedules')
        //     ->where('user_id', $userId)
        //     ->first();
        // $schedule = $scheduleData ? json_decode($scheduleData->schedule, true) : [];
        
        return response()->json([
            'success' => true,
            'schedule' => $schedule
        ]);
    } catch (\Exception $e) {
        \Log::error('Erreur getSchedule: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}