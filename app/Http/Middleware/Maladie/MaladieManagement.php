<?php

namespace App\Http\Middleware\Maladie;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MaladieManagement
{
    /**
     * Handle an incoming request.
     * Vérifie si l'utilisateur a les droits pour gérer les maladies (admin/superadmin)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        if (!in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Accès non autorisé! Seuls les administrateurs peuvent gérer les maladies.');
        }

        return $next($request);
    }
}
