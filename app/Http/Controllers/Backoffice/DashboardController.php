<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Objective;
use App\Models\Progress;
use App\Models\Maladie;
use App\Models\Aliment;
use App\Models\Repas;
use App\Models\Goal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalActivities = Activity::count();
        $totalCategories = Category::count();
        $totalPartners = Partner::count();
        $totalObjectives = Objective::count();
        $totalMaladies = Maladie::count();
        $totalAliments = Aliment::count();
        $totalRepas = Repas::count();
        $totalGoals = Goal::count();

        // Utilisateurs récents (7 derniers jours)
        $newUsersThisWeek = User::where('role', '!=', 'admin')
            ->where('created_at', '>=', Carbon::now()->subWeek())
            ->count();

        // Utilisateurs actifs (connectés dans les 30 derniers jours)
        $activeUsers = User::where('role', '!=', 'admin')
            ->where('updated_at', '>=', Carbon::now()->subMonth())
            ->count();

        // Statistiques mensuelles des utilisateurs
        $monthlyUsers = User::where('role', '!=', 'admin')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top 5 activités populaires
        $popularActivities = Activity::withCount('playlists')
            ->orderBy('playlists_count', 'desc')
            ->take(5)
            ->get();

        // Distribution des utilisateurs par rôle
        $usersByRole = User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get();

        // Statistiques de progress
        $totalProgress = Progress::count();
        $progressThisMonth = Progress::where('created_at', '>=', Carbon::now()->startOfMonth())->count();

        // Partenaires par type
        $partnersByType = Partner::select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();

        // Dernières activités
        $recentActivities = Activity::with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Utilisateurs récents
        $recentUsers = User::where('role', '!=', 'admin')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Objectifs assignés vs non assignés
        $assignedObjectives = DB::table('user_objectives')->distinct('objective_id')->count();
        $unassignedObjectives = $totalObjectives - $assignedObjectives;

        // Évolution des inscriptions (6 derniers mois)
        $userGrowth = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = User::where('role', '!=', 'admin')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $userGrowth->push([
                'month' => $date->format('M Y'),
                'count' => $count
            ]);
        }

        return view('backoffice.dashboard', compact(
            'totalUsers',
            'totalActivities',
            'totalCategories',
            'totalPartners',
            'totalObjectives',
            'totalMaladies',
            'totalAliments',
            'totalRepas',
            'totalGoals',
            'newUsersThisWeek',
            'activeUsers',
            'monthlyUsers',
            'popularActivities',
            'usersByRole',
            'totalProgress',
            'progressThisMonth',
            'partnersByType',
            'recentActivities',
            'recentUsers',
            'assignedObjectives',
            'unassignedObjectives',
            'userGrowth'
        ));
    }
}


