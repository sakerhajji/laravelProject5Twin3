<?php

namespace App\Http\Middleware\Partner;

use App\Models\Partner;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckPartnerStatus
{
    /**
     * Handle an incoming request.
     * Vérifie le statut du partenaire (actif, inactif, en attente)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $requiredStatus = 'active'): Response
    {
        // D'abord, essayer de récupérer le partenaire validé par le middleware précédent
        $partner = $request->get('validatedPartner');
        
        // Si pas de partenaire validé, essayer de le récupérer depuis la route
        if (!$partner) {
            $partnerId = $request->route('partner');
            
            if ($partnerId) {
                // Si c'est déjà un objet Partner (model binding)
                if ($partnerId instanceof Partner) {
                    $partner = $partnerId;
                } else {
                    // Si c'est un ID, récupérer le partenaire
                    $partner = Partner::find($partnerId);
                }
            }
        }
        
        // Vérifier si le partenaire existe
        if (!$partner) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Partenaire non trouvé.'
                ], 404);
            }
            abort(404, 'Partenaire non trouvé.');
        }
        
        // S'assurer qu'on a un objet Partner et non une Collection
        if ($partner instanceof \Illuminate\Support\Collection) {
            // Si c'est une collection, prendre le premier élément
            $partner = $partner->first();
            
            if (!$partner) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Partenaire non trouvé dans la collection.'
                    ], 404);
                }
                abort(404, 'Partenaire non trouvé dans la collection.');
            }
        }
        
        // Vérifier si c'est bien un objet Partner
        if (!($partner instanceof Partner)) {
            Log::error('Invalid partner object in CheckPartnerStatus middleware', [
                'partner_type' => get_class($partner),
                'partner_data' => $partner
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Objet partenaire invalide.'
                ], 500);
            }
            abort(500, 'Objet partenaire invalide.');
        }
        
        // Vérifier le statut
        if ($partner->status !== $requiredStatus) {
            $statusMessages = [
                'active' => 'Ce partenaire n\'est pas actif.',
                'inactive' => 'Ce partenaire n\'est pas inactif.',
                'pending' => 'Ce partenaire n\'est pas en attente.'
            ];
            
            $message = $statusMessages[$requiredStatus] ?? 'Statut du partenaire invalide.';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 403);
            }
            
            abort(403, $message);
        }

        return $next($request);
    }
}