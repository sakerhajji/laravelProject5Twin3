<?php

namespace App\Http\Middleware\Partner;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidatePartnerData
{
    /**
     * Handle an incoming request.
     * Vérifie les données du partenaire avant traitement
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
            // Nettoyer les services s'ils sont présents
            if ($request->has('services') && is_array($request->input('services'))) {
                $services = array_filter($request->input('services'), function($service) {
                    return !empty(trim($service ?? ''));
                });
                $request->merge(['services' => array_values($services)]);
            }

            // Nettoyer les horaires d'ouverture s'ils sont présents
            if ($request->has('opening_hours') && is_array($request->input('opening_hours'))) {
                $openingHours = [];
                foreach ($request->input('opening_hours') as $day => $hours) {
                    if (is_array($hours)) {
                        // Nouveau format avec structure complète
                        $cleanHours = [
                            'is_open' => isset($hours['is_open']) && $hours['is_open'] == '1',
                            'open_time' => !empty($hours['open_time']) ? $hours['open_time'] : '09:00',
                            'close_time' => !empty($hours['close_time']) ? $hours['close_time'] : '18:00',
                            'has_break' => isset($hours['has_break']) && $hours['has_break'] == '1',
                            'break_start' => !empty($hours['break_start']) ? $hours['break_start'] : '12:00',
                            'break_end' => !empty($hours['break_end']) ? $hours['break_end'] : '13:00'
                        ];
                        $openingHours[$day] = $cleanHours;
                    } elseif (is_string($hours) && !empty(trim($hours))) {
                        // Ancien format avec chaîne simple
                        $openingHours[$day] = trim($hours);
                    }
                }
                $request->merge(['opening_hours' => $openingHours]);
            }
        } catch (\Exception $e) {
            Log::error('Error in ValidatePartnerData middleware preprocessing', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
        }
    }

    /**
     * Obtenir les règles de validation selon le type de requête
     */
    private function getValidationRules(Request $request): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:doctor,gym,laboratory,pharmacy,nutritionist,psychologist',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'website' => 'nullable|url|max:255',
            'license_number' => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,pending',
            'contact_person' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rating' => 'nullable|numeric|between:0,5',
            'opening_hours' => 'nullable|array|max:7',
            'opening_hours.*' => 'nullable|array',
            'opening_hours.*.is_open' => 'boolean',
            'opening_hours.*.open_time' => 'nullable|string|date_format:H:i',
            'opening_hours.*.close_time' => 'nullable|string|date_format:H:i',
            'opening_hours.*.has_break' => 'boolean',
            'opening_hours.*.break_start' => 'nullable|string|date_format:H:i',
            'opening_hours.*.break_end' => 'nullable|string|date_format:H:i',
            'services' => 'nullable|array|max:20',
            'services.*' => 'nullable|string|max:200',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];

        // Règles spécifiques pour la mise à jour
        if ($request->method() === 'PUT' || $request->method() === 'PATCH') {
            $partner = $request->route('partner');
            
            // Obtenir l'ID du partenaire
            $partnerId = null;
            if ($partner) {
                if ($partner instanceof \App\Models\Partner) {
                    $partnerId = $partner->id;
                } elseif (is_numeric($partner)) {
                    $partnerId = $partner;
                } elseif (is_string($partner)) {
                    $partnerId = $partner;
                }
            }
            
            // Rendre l'email unique sauf pour le partenaire actuel
            if ($partnerId) {
                $rules['email'] = 'required|email|max:255|unique:partners,email,' . $partnerId;
            }
        } else {
            // Pour la création, l'email doit être unique
            $rules['email'] = 'required|email|max:255|unique:partners,email';
        }

        return $rules;
    }
}