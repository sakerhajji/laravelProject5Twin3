<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PartnerRecommendationService
{
    /**
     * Obtenir des recommandations intelligentes pour un utilisateur
     * Basé sur plusieurs critères : favoris, historique, localisation, notes
     */
    public function getRecommendationsForUser(?User $user = null, int $limit = 6): Collection
    {
        if (!$user) {
            // Pour les utilisateurs non connectés, retourner les partenaires les mieux notés
            return $this->getTopRatedPartners($limit);
        }

        // Récupérer les partenaires déjà dans les favoris pour les exclure
        $favoritePartnerIds = $user->favoritedPartners()->pluck('partners.id')->toArray();
        
        // Récupérer les partenaires déjà notés
        $ratedPartnerIds = $user->partnerRatings()->pluck('partner_id')->toArray();

        // Obtenir les types de partenaires préférés de l'utilisateur
        $preferredTypes = $this->getUserPreferredTypes($user);
        
        // Obtenir la ville de l'utilisateur
        $userCity = $user->city;

        // Construire la requête de recommandation avec un système de scoring
        $query = Partner::query()
            ->where('status', Partner::STATUS_ACTIVE)
            ->whereNotIn('id', $favoritePartnerIds);

        // Calculer un score de recommandation
        $recommendations = $query->get()->map(function ($partner) use ($preferredTypes, $userCity, $ratedPartnerIds) {
            $score = 0;

            // +30 points si le type correspond aux préférences de l'utilisateur
            if (in_array($partner->type, $preferredTypes)) {
                $score += 30;
            }

            // +20 points si dans la même ville
            if ($userCity && $partner->city && strtolower($partner->city) === strtolower($userCity)) {
                $score += 20;
            }

            // +10 points par étoile de notation (max 50 points)
            $score += ($partner->rating * 10);

            // +15 points si le partenaire a beaucoup d'avis (indicateur de popularité)
            $ratingsCount = $partner->ratings()->count();
            if ($ratingsCount > 20) {
                $score += 15;
            } elseif ($ratingsCount > 10) {
                $score += 10;
            } elseif ($ratingsCount > 5) {
                $score += 5;
            }

            // +5 points si l'utilisateur n'a pas encore noté ce partenaire (découverte)
            if (!in_array($partner->id, $ratedPartnerIds)) {
                $score += 5;
            }

            // +10 points si le partenaire a des services variés
            if (is_array($partner->services) && count($partner->services) > 3) {
                $score += 10;
            }

            $partner->recommendation_score = $score;
            $partner->recommendation_reason = $this->getRecommendationReason($partner, $preferredTypes, $userCity);

            return $partner;
        });

        // Trier par score et retourner les meilleurs
        return $recommendations
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();
    }

    /**
     * Obtenir les types de partenaires préférés basés sur l'historique de l'utilisateur
     */
    private function getUserPreferredTypes(User $user): array
    {
        // Analyser les favoris - Récupérer tous les partenaires puis grouper en PHP
        $favoritePartners = $user->favoritedPartners()->get();
        $favoriteTypes = $favoritePartners
            ->groupBy('type')
            ->sortByDesc(function ($group) {
                return $group->count();
            })
            ->keys()
            ->toArray();

        // Analyser les notations - Seulement les bonnes notes (4-5 étoiles)
        $ratedPartners = Partner::whereHas('ratings', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('rating', '>=', 4);
        })->get();
        
        $ratedTypes = $ratedPartners
            ->groupBy('type')
            ->sortByDesc(function ($group) {
                return $group->count();
            })
            ->keys()
            ->toArray();

        // Combiner et dédupliquer
        $preferredTypes = array_unique(array_merge($favoriteTypes, $ratedTypes));

        // Si aucune préférence détectée, analyser selon le profil santé
        if (empty($preferredTypes)) {
            $preferredTypes = $this->inferTypesFromUserProfile($user);
        }

        return $preferredTypes;
    }

    /**
     * Inférer les types de partenaires pertinents selon le profil utilisateur
     */
    private function inferTypesFromUserProfile(User $user): array
    {
        $types = [];

        // Si l'utilisateur a des maladies, recommander des médecins et laboratoires
        if ($user->maladies()->count() > 0) {
            $types[] = Partner::TYPE_DOCTOR;
            $types[] = Partner::TYPE_LABORATORY;
            $types[] = Partner::TYPE_PHARMACY;
        }

        // Si l'utilisateur a des objectifs de fitness
        $fitnessObjectives = $user->objectives()
            ->where('title', 'like', '%sport%')
            ->orWhere('title', 'like', '%fitness%')
            ->orWhere('title', 'like', '%exercice%')
            ->count();

        if ($fitnessObjectives > 0) {
            $types[] = Partner::TYPE_GYM;
        }

        // Si l'utilisateur a des objectifs nutritionnels
        $nutritionObjectives = $user->objectives()
            ->where('title', 'like', '%nutrition%')
            ->orWhere('title', 'like', '%poids%')
            ->orWhere('title', 'like', '%régime%')
            ->count();

        if ($nutritionObjectives > 0) {
            $types[] = Partner::TYPE_NUTRITIONIST;
        }

        // Par défaut, si aucun type détecté, retourner tous les types
        if (empty($types)) {
            $types = array_keys(Partner::getTypes());
        }

        return array_unique($types);
    }

    /**
     * Obtenir les partenaires les mieux notés (pour utilisateurs non connectés)
     */
    private function getTopRatedPartners(int $limit): Collection
    {
        return Partner::query()
            ->where('status', Partner::STATUS_ACTIVE)
            ->where('rating', '>', 0)
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($partner) {
                $partner->recommendation_reason = "Partenaire très bien noté ({$partner->rating}/5)";
                return $partner;
            });
    }

    /**
     * Générer une raison de recommandation personnalisée
     */
    private function getRecommendationReason(Partner $partner, array $preferredTypes, ?string $userCity): string
    {
        $reasons = [];

        if (in_array($partner->type, $preferredTypes)) {
            $reasons[] = "Correspond à vos préférences";
        }

        if ($userCity && strtolower($partner->city) === strtolower($userCity)) {
            $reasons[] = "Proche de chez vous";
        }

        if ($partner->rating >= 4.5) {
            $reasons[] = "Excellentes évaluations ({$partner->rating}/5)";
        } elseif ($partner->rating >= 4.0) {
            $reasons[] = "Très bien noté ({$partner->rating}/5)";
        }

        $ratingsCount = $partner->ratings()->count();
        if ($ratingsCount > 20) {
            $reasons[] = "Très populaire ({$ratingsCount} avis)";
        }

        if (is_array($partner->services) && count($partner->services) > 3) {
            $reasons[] = "Services variés";
        }

        return !empty($reasons) ? implode(' • ', $reasons) : "Recommandé pour vous";
    }

    /**
     * Obtenir des partenaires similaires à un partenaire donné
     */
    public function getSimilarPartners(Partner $partner, int $limit = 4): Collection
    {
        return Partner::query()
            ->where('id', '!=', $partner->id)
            ->where('status', Partner::STATUS_ACTIVE)
            ->where(function ($query) use ($partner) {
                // Même type
                $query->where('type', $partner->type)
                    // Ou même ville
                    ->orWhere('city', $partner->city);
            })
            ->orderByDesc('rating')
            ->limit($limit)
            ->get();
    }

    /**
     * Recherche intelligente de partenaires avec critères multiples
     */
    public function intelligentSearch(array $criteria): Collection
    {
        $query = Partner::query()->where('status', Partner::STATUS_ACTIVE);

        // Type de partenaire
        if (!empty($criteria['type'])) {
            $query->where('type', $criteria['type']);
        }

        // Ville
        if (!empty($criteria['city'])) {
            $query->where('city', 'like', '%' . $criteria['city'] . '%');
        }

        // Note minimum
        if (!empty($criteria['min_rating'])) {
            $query->where('rating', '>=', $criteria['min_rating']);
        }

        // Services spécifiques
        if (!empty($criteria['services'])) {
            foreach ($criteria['services'] as $service) {
                $query->whereJsonContains('services', $service);
            }
        }

        // Recherche par mots-clés (nom, description, spécialisation)
        if (!empty($criteria['keyword'])) {
            $keyword = $criteria['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhere('specialization', 'like', '%' . $keyword . '%');
            });
        }

        // Tri
        $sortBy = $criteria['sort_by'] ?? 'rating';
        $sortOrder = $criteria['sort_order'] ?? 'desc';

        switch ($sortBy) {
            case 'rating':
                $query->orderBy('rating', $sortOrder);
                break;
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'recent':
                $query->orderBy('created_at', $sortOrder);
                break;
            default:
                $query->orderBy('rating', 'desc');
        }

        return $query->get();
    }

    /**
     * Obtenir des statistiques de recommandation pour analyse
     */
    public function getRecommendationStats(User $user): array
    {
        // Utiliser une requête directe pour éviter les problèmes avec les colonnes pivot
        $favoriteTypes = DB::table('partners')
            ->join('partner_favorites', 'partners.id', '=', 'partner_favorites.partner_id')
            ->where('partner_favorites.user_id', $user->id)
            ->whereNull('partners.deleted_at')
            ->select('partners.type', DB::raw('count(*) as count'))
            ->groupBy('partners.type')
            ->get()
            ->mapWithKeys(function ($item) {
                $types = Partner::getTypes();
                return [$types[$item->type] ?? $item->type => $item->count];
            });

        $ratingStats = $user->partnerRatings()
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total_ratings')
            ->first();

        return [
            'favorite_types' => $favoriteTypes,
            'total_favorites' => $user->favoritedPartners()->count(),
            'total_ratings' => $ratingStats->total_ratings ?? 0,
            'average_rating_given' => round($ratingStats->avg_rating ?? 0, 1),
            'preferred_city' => $user->city,
        ];
    }
}
