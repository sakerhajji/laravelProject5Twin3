<?php

namespace App\Http\Middleware\Asymptome;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AsymptomeCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $user = auth()->user();

        // Only admin or superadmin can access
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à cette ressource.');
        }

        return $next($request);
    }
}
