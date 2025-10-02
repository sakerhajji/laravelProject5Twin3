<?php

namespace App\Http\Middleware\Asymptome;

use App\Models\Asymptome;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AsymptomeValidate
{
    /**
     * Handle an incoming request.
     * Vérifie si l'asymptome existe dans la base de données
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Récupérer l'ID de l'asymptome depuis la route
        $asymptomeId = $request->route('asymptome');

        if ($asymptomeId) {
            // Si l'ID est déjà un objet Asymptome (model binding), l'utiliser directement
            if ($asymptomeId instanceof Asymptome) {
                $asymptome = $asymptomeId;
            } else {
                // Sinon, chercher l'asymptome par ID
                $asymptome = Asymptome::find($asymptomeId);

                if (!$asymptome) {
                    if ($request->expectsJson() || $request->wantsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Asymptome non trouvé.'
                        ], 404);
                    }

                    abort(404, 'Asymptome non trouvé.');
                }
            }

            // Ajouter l'asymptome validé à la requête pour éviter une nouvelle requête
            $request->merge(['validatedAsymptome' => $asymptome]);
        }

        return $next($request);
    }
}
