<?php

namespace App\Http\Middleware\Activity;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActivityManagement
{
    /**
     * Handle an incoming request.
     * Vérifie si l'utilisateur a les droits pour gérer les activités (admin/superadmin)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Vérifier le rôle de l'utilisateur - seuls admin et superadmin peuvent gérer les activités
        if (!in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Accès non autorisé! Seuls les administrateurs peuvent gérer les activités.');
        }

        return $next($request);
    }
}
