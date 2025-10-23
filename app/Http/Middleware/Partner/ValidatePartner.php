<?php

namespace App\Http\Middleware\Partner;

use App\Models\Partner;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatePartner
{
    /**
     * Handle an incoming request.
     * Vérifie si le partenaire existe dans la base de données
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Récupérer l'ID du partenaire depuis la route
        $partnerId = $request->route('partner');
        
        if ($partnerId) {
            // Si l'ID est déjà un objet Partner (model binding), l'utiliser directement
            if ($partnerId instanceof Partner) {
                $partner = $partnerId;
            } else {
                // Sinon, chercher le partenaire par ID
                $partner = Partner::find($partnerId);
                
                if (!$partner) {
                    if ($request->expectsJson() || $request->wantsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Partenaire non trouvé.'
                        ], 404);
                    }
                    
                    abort(404, 'Partenaire non trouvé.');
                }
            }
            
            // Ajouter le partenaire à la requête pour éviter une nouvelle requête
            $request->merge(['validatedPartner' => $partner]);
        }

        return $next($request);
    }
}