<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminFromFrontend
{
    /**
     * Handle an incoming request.
     * Empêche les administrateurs d'accéder aux pages frontend
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté et s'il est admin/superadmin
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            // Rediriger vers le dashboard admin avec un message
            return redirect()->route('home')->with('warning', 'Accès aux pages frontend non autorisé pour les administrateurs.');
        }
        
        return $next($request);
    }
}
