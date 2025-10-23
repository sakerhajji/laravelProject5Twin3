<?php

namespace App\Http\Middleware\Maladie;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MaladieValidateData
{
    /**
     * Handle an incoming request.
     * Vérifie les données de la maladie avant traitement
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
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
     * Obtenir les règles de validation pour une maladie
     */
    private function getValidationRules(Request $request): array
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'traitement' => 'nullable|string',
            'prevention' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];

        // Pour la mise à jour, ignorer le nom actuel si nécessaire
        if (in_array($request->method(), ['PUT', 'PATCH'])) {
            $maladie = $request->route('maladie');
            if ($maladie) {
                if ($maladie instanceof \App\Models\Maladie) {
                    $maladieId = $maladie->id;
                } else {
                    $maladieId = $maladie;
                }
                $rules['nom'] = 'required|string|max:255|unique:maladies,nom,' . $maladieId;
            }
        } else {
            // Création, nom unique
            $rules['nom'] = 'required|string|max:255|unique:maladies,nom';
        }

        return $rules;
    }
}
