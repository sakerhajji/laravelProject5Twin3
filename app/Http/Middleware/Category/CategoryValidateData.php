<?php

namespace App\Http\Middleware\Category;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CategoryValidateData
{
    /**
     * Handle an incoming request.
     * Vérifie les données de catégorie avant traitement
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
            $messages = $this->getValidationMessages();
            
            $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Données de catégorie invalides.',
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
                $title = ucwords(strtolower($title)); // Première lettre de chaque mot en majuscule
                $request->merge(['title' => $title]);
            }

            // Nettoyer la description
            if ($request->has('description')) {
                $description = trim($request->input('description'));
                $description = strip_tags($description); // Supprimer les balises HTML
                $description = preg_replace('/\s+/', ' ', $description); // Normaliser les espaces
                $request->merge(['description' => $description]);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors du préprocessing des données de catégorie', [
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
        
        // Règles de base
        $rules = [
            'title' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ0-9\s\-_.&()]+$/' // Lettres, chiffres, espaces et caractères spéciaux autorisés
            ],
            'description' => [
                'required',
                'string',
                'min:5',
                'max:500'
            ],
            'image' => [
                $isUpdate ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,gif,webp,svg',
                'max:2048', // 2MB max
                //'dimensions:min_width=50,min_height=50,max_width=1500,max_height=1500'
            ]
        ];

        // Ajouter la règle d'unicité pour le titre
        if ($isUpdate) {
            $categoryId = $request->route('category') ? $request->route('category')->id : null;
            $rules['title'][] = 'unique:categories,title,' . $categoryId;
        } else {
            $rules['title'][] = 'unique:categories,title';
        }

        return $rules;
    }

    /**
     * Messages d'erreur personnalisés
     */
    private function getValidationMessages(): array
    {
        return [
            'title.required' => 'Le nom de la catégorie est obligatoire.',
            'title.min' => 'Le nom doit contenir au moins 2 caractères.',
            'title.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'title.unique' => 'Cette catégorie existe déjà.',
            'title.regex' => 'Le nom contient des caractères non autorisés.',
            
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au moins 5 caractères.',
            'description.max' => 'La description ne peut pas dépasser 500 caractères.',
            
            'image.required' => 'Une image est obligatoire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format JPG, JPEG, PNG, GIF, WebP ou SVG.',
            'image.max' => 'L\'image ne peut pas dépasser 2MB.',
            //'image.dimensions' => 'L\'image doit avoir une taille comprise entre 50x50 et 1500x1500 pixels.'
        ];
    }
}
