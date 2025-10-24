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

        // --- Objectifs de l’utilisateur avec progression
        $myObjectives = $user->objectives()
            ->with(['progresses' => function ($q) use ($user) {
                $q->where('user_id', $user->id)->latest('entry_date');
            }])
            ->get();

        // --- Données intelligentes : vraies statistiques utilisateur
        $insights = $this->analyticsService->getUserInsights($user);
        $recommendations = $this->recommendationService->getRecommendationsForUser($user, 3);

        // --- Progression quotidienne réelle (pour la timeline)
        $progressByCategory = $insights['progress_by_category'] ?? [];

        // --- Préparer les données de charts pour la vue
        $chartData = [
            'timeline' => $insights['timeline_categories_30d'] ?? ['dates' => [], 'series' => []],
            'progressByCategory' => $insights['progress_by_category'] ?? [],
            'categoryBreakdown' => $insights['performance_summary']['category_breakdown'] ?? [],
            'completionRates' => [
                'sport' => $insights['performance_summary']['sport_completion'] ?? 0,
                'education' => $insights['performance_summary']['education_completion'] ?? 0,
                'health' => $insights['performance_summary']['health_completion'] ?? 0,
                'other' => $insights['performance_summary']['other_completion'] ?? 0,
            ],
        ];

        // --- Objectifs récents
        $recentProgress = Progress::where('user_id', $user->id)
            ->with('objective')
            ->latest('entry_date')
            ->limit(5)
            ->get();

        // --- Badges récents
        $recentBadges = UserBadge::where('user_id', $user->id)
            ->latest('earned_at')
            ->limit(3)
            ->get();

        return view('front.smart-dashboard.index', compact(
            'myObjectives',
            'insights',
            'recommendations',
            'progressByCategory',
            'chartData',
            'recentProgress',
            'recentBadges',
            'user'
        ));
    }

    /** API - Recommandations */
    public function getRecommendations(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 5);

        $recommendations = $this->recommendationService->getRecommendationsForUser($user, $limit);

        return response()->json([
            'success' => true,
            'recommendations' => $recommendations->map(function ($rec) use ($user) {
                return [
                    'id' => $rec->objective->id,
                    'title' => $rec->objective->title,
                    'description' => $rec->objective->description,
                    'category' => $rec->objective->category,
                    'cover_url' => $rec->objective->cover_url,
                    'score' => round($rec->score, 2),
                    'reason' => $rec->reason,
                    'type' => $rec->type,
                    'success_probability' =>
                        $this->recommendationService->predictSuccessProbability($user, $rec->objective),
                ];
            }),
        ]);
    }

    /** API - Insights en temps réel */
    public function getInsights(Request $request)
    {
        $user = Auth::user();
        $insights = $this->analyticsService->getUserInsights($user);

        return response()->json([
            'success' => true,
            'insights' => $insights,
        ]);
    }

    /** API - Prédictions */
    public function getPredictions(Request $request)
    {
        $user = Auth::user();
        $insights = $this->analyticsService->getUserInsights($user);

        return response()->json([
            'success' => true,
            'predictions' => $insights['predictions'],
        ]);
    }

    /** Chatbot */
    public function chatbotMessage(Request $request, ChatbotService $chatbotService)
    {
        $userMessage = $request->input('message');

        if (!$userMessage) {
            return response()->json(['error' => 'Message vide.'], 400);
        }

        $botReply = $chatbotService->sendMessage($userMessage);

        return response()->json(['reply' => $botReply]);
    }

    /** Sauvegarder le planning utilisateur */
    public function saveSchedule(Request $request)
    {
        try {
            $schedule = $request->input('schedule', []);
            $userId = auth()->id();

            session(['objective_schedule_' . $userId => $schedule]);

            return response()->json([
                'success' => true,
                'message' => 'Planning enregistré avec succès',
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur saveSchedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /** Récupérer le planning utilisateur */
    public function getSchedule()
    {
        try {
            $userId = auth()->id();
            $schedule = session('objective_schedule_' . $userId, []);

            return response()->json([
                'success' => true,
                'schedule' => $schedule,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur getSchedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
