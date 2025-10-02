<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard pour les utilisateurs normaux (rôle 'user')
     */
    public function index()
    {
        $user = Auth::user();
        
        // Statistiques utilisateur
        $stats = [
            'objectifs_actifs' => $user->userObjectives()->where('is_active', true)->count(),
            'progres_total' => $user->progresses()->count(),
            'partenaires_favoris' => $user->favoritePartners()->count() ?? 0,
        ];
        
        // Derniers progrès
        $recentProgress = $user->progresses()
            ->with('objective')
            ->latest()
            ->limit(5)
            ->get();
            
        // Objectifs actifs
        $activeObjectives = $user->userObjectives()
            ->with('objective')
            ->where('is_active', true)
            ->latest()
            ->limit(3)
            ->get();

        return view('front.dashboard', compact('user', 'stats', 'recentProgress', 'activeObjectives'));
    }
}