<?php

namespace App\Http\Middleware\Maladie;

use App\Models\Maladie;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaladieValidate
{
    /**
     * Handle an incoming request.
     * Vérifie si la maladie existe dans la base de données
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Récupérer l'ID de la maladie depuis la route
        $maladieId = $request->route('maladie');

        if ($maladieId) {
            // Si l'ID est déjà un objet Maladie (model binding), l'utiliser directement
            if ($maladieId instanceof Maladie) {
                $maladie = $maladieId;
            } else {
                // Sinon, chercher la maladie par ID
                $maladie = Maladie::find($maladieId);

                if (!$maladie) {
                    if ($request->expectsJson() || $request->wantsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Maladie non trouvée.'
                        ], 404);
                    }

                    abort(404, 'Maladie non trouvée.');
                }
            }

            // Ajouter la maladie validée à la requête pour éviter une nouvelle requête
            $request->merge(['validatedMaladie' => $maladie]);
        }

        return $next($request);
    }
}
