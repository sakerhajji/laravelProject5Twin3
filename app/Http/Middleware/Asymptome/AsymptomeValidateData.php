<?php

namespace App\Http\Middleware\Asymptome;

use App\Models\Asymptome;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AsymptomeValidateData
{
    /**
     * Handle an incoming request.
     * Vérifie les données de l'asymptome avant traitement
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Appliquer la validation uniquement pour les méthodes POST, PUT, PATCH
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            // Préprocesser les données si nécessaire
            $this->preprocessRequestData($request);

            $rules = $this->getValidationRules($request);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Données invalides.',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        return $next($request);
    }

    /**
     * Préprocesser les données de la requête
     */
    private function preprocessRequestData(Request $request): void
    {
        try {
            // Exemple : nettoyer le champ 'nom'
            if ($request->has('nom')) {
                $request->merge(['nom' => trim($request->input('nom'))]);
            }

            // Ajouter ici d'autres prétraitements si nécessaire
        } catch (\Exception $e) {
            Log::error('Error in AsymptomeValidateData middleware preprocessing', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
        }
    }

    /**
     * Obtenir les règles de validation
     */
    private function getValidationRules(Request $request): array
    {
        $rules = [
            'nom' => 'required|string|max:255|unique:asymptomes,nom' . ($request->route('asymptome') ? ',' . $request->route('asymptome') : ''),
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ];

        return $rules;
    }
}
