<?php

namespace App\Http\Middleware\Activity;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ActivityValidateData
{
    /**
     * Handle an incoming request.
     * Vérifie les données d'activité avant traitement
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Appliquer la validation uniquement pour les méthodes POST, PUT, PATCH
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            // Préprocesser les données avant validation
            $this->preprocessRequestData($request);
            
            $rules = $this->getValidationRules($request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Données d\'activité invalides.',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Veuillez corriger les erreurs dans le formulaire.');
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
            // Nettoyer et formater le titre
            if ($request->has('title')) {
                $title = trim($request->input('title'));
                $title = ucfirst(strtolower($title)); // Première lettre en majuscule
                $request->merge(['title' => $title]);
            }

            // Nettoyer la description
            if ($request->has('description')) {
                $description = trim($request->input('description'));
                $description = strip_tags($description); // Supprimer les balises HTML
                $request->merge(['description' => $description]);
            }

            // Nettoyer et formater le temps
            if ($request->has('time')) {
                $time = trim($request->input('time'));
                $request->merge(['time' => $time]);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors du préprocessing des données d\'activité', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
        }
    }

    /**
     * Obtenir les règles de validation selon le contexte
     */
    private function getValidationRules(Request $request): array
    {
        $isUpdate = $request->isMethod('PUT') || $request->isMethod('PATCH');
        
        return [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ0-9\s\-_.()]+$/' // Lettres, chiffres, espaces et caractères spéciaux de base
            ],
            'description' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ],
            'time' => [
                'required',
                'string',
                'max:50',
                'regex:/^[0-9\s\-:hmin]+$/' // Format horaire flexible
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id'
            ],
            'media' => [
                $isUpdate ? 'nullable' : 'required',
                'file', // Use 'file' instead of 'image' to allow videos too
                'mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi',
                'max:20480', // 20MB
            ]

        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    private function getValidationMessages(): array
    {
        return [
            'title.required' => 'Le titre de l\'activité est obligatoire.',
            'title.min' => 'Le titre doit contenir au moins 3 caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'title.regex' => 'Le titre contient des caractères non autorisés.',
            
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au moins 10 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            
            'time.required' => 'La durée est obligatoire.',
            'time.max' => 'La durée ne peut pas dépasser 50 caractères.',
            'time.regex' => 'Format de durée invalide.',
            
            'category_id.required' => 'Vous devez sélectionner une catégorie.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            
            'media.required' => 'Le fichier média est obligatoire.',
            'media.file' => 'Le fichier doit être valide.',
            'media.mimes' => 'Le fichier doit être une image ou une vidéo valide (jpg, png, gif, mp4, mov, avi).',
            'media.max' => 'Le fichier ne peut pas dépasser 20MB.',

        ];
    }
}
