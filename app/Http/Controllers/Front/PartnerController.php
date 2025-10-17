<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\User;
use App\Services\PartnerRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerController extends Controller
{
    protected $recommendationService;

    public function __construct(PartnerRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }
    public function index(Request $request)
    {
        $query = Partner::active();
        
        // Filter by type
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }
        
        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Sort by rating or distance (if location provided)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'rating':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $partners = $query->paginate(12);
        
        // Get unique cities for filter
        $cities = Partner::active()
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        // Get user's favorite partner IDs if authenticated
        $userFavorites = [];
        if (Auth::check()) {
            $userFavorites = Auth::user()->favoritedPartners()->pluck('partners.id')->toArray();
        }
        
        // Si c'est une requête AJAX, retourner seulement la vue partielle
        if ($request->ajax()) {
            return response()->json([
                'html' => view('front.partners.partials.partners-grid', compact('partners', 'userFavorites'))->render(),
                'pagination' => $partners->hasPages() ? $partners->appends($request->all())->links()->toHtml() : '',
                'count' => $partners->total()
            ]);
        }
        
        return view('front.partners.index', compact('partners', 'cities', 'userFavorites'));
    }

    /**
     * Recherche AJAX optimisée pour les partenaires
     */
    public function search(Request $request)
    {
        $query = Partner::active();
        
        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->ofType($request->type);
        }
        
        // Filter by city
        if ($request->filled('city') && $request->city !== '') {
            $query->where('city', 'like', '%' . $request->city . '%');
        }
        
        // Search
        if ($request->filled('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        // Sort by rating or distance (if location provided)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'rating':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $partners = $query->paginate(12);
        
        // Get user's favorite partner IDs if authenticated
        $userFavorites = [];
        if (Auth::check()) {
            $userFavorites = Auth::user()->favoritedPartners()->pluck('partners.id')->toArray();
        }

        return response()->json([
            'html' => view('front.partners.partials.partners-grid', compact('partners', 'userFavorites'))->render(),
            'pagination' => $partners->hasPages() ? $partners->appends($request->all())->links()->toHtml() : '',
            'count' => $partners->total(),
            'filters' => [
                'search' => $request->search ?? '',
                'type' => $request->type ?? 'all',
                'city' => $request->city ?? '',
                'sort' => $request->sort ?? 'latest'
            ]
        ]);
    }

    public function show(Partner $partner)
    {
        // Check if partner is active
        if ($partner->status !== Partner::STATUS_ACTIVE) {
            abort(404);
        }
        
        $isFavorite = Auth::check() 
            ? Auth::user()->favoritedPartners()->where('partner_id', $partner->id)->exists()
            : false;
            
        // Get similar partners (same type, different partner)
        $similarPartners = Partner::active()
            ->where('type', $partner->type)
            ->where('id', '!=', $partner->id)
            ->limit(4)
            ->get();
        
        return view('front.partners.show', compact('partner', 'isFavorite', 'similarPartners'));
    }

    public function toggleFavorite(Request $request, Partner $partner = null)
    {
        // Debug: Vérifier les paramètres reçus
        if (!Auth::check()) {
            return response()->json([
                'success' => false, 
                'message' => 'Vous devez être connecté.'
            ], 401);
        }

        try {
            // Utiliser le partenaire du middleware s'il existe
            $validatedPartner = $request->get('validatedPartner');
            if ($validatedPartner) {
                $partner = $validatedPartner;
            }
            
            if (!$partner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Partenaire non trouvé.'
                ], 404);
            }
            
            $user = Auth::user();
            
            // Vérifier si le partenaire est déjà en favori
            $isFavorited = $user->favoritedPartners()->where('partner_id', $partner->id)->exists();

            if ($isFavorited) {
                // Remove from favorites
                $user->favoritedPartners()->detach($partner->id);
                $action = 'removed';
                $message = 'Partenaire retiré des favoris.';
                $isFavorite = false;
            } else {
                // Add to favorites
                $user->favoritedPartners()->attach($partner->id);
                $action = 'added';
                $message = 'Partenaire ajouté aux favoris.';
                $isFavorite = true;
            }

            return response()->json([
                'success' => true,
                'action' => $action,
                'message' => $message,
                'is_favorite' => $isFavorite
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue: ' . $e->getMessage()
            ], 500);
        }
    }

    public function favorites()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $favoritePartners = Auth::user()->favoritedPartners()->paginate(12);

        return view('front.partners.favorites', compact('favoritePartners'));
    }

    /**
     * Submit or update a rating for a partner by authenticated user.
     */
    public function rate(Request $request, Partner $partner)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vous devez être connecté.'], 401);
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $user = Auth::user();

        // Update or create rating
        $rating = \App\Models\PartnerRating::updateOrCreate(
            ['user_id' => $user->id, 'partner_id' => $partner->id],
            ['rating' => $data['rating']]
        );

        // Recalculate average and save to partner
        $avg = $partner->recalcAndSaveAverageRating();

        return response()->json([
            'success' => true,
            'message' => 'Merci pour votre note.',
            'average' => $avg,
            'user_rating' => (int) $data['rating']
        ]);
    }

    public function byType($type)
    {
        $types = Partner::getTypes();
        
        if (!array_key_exists($type, $types)) {
            abort(404);
        }

        $partners = Partner::active()
            ->ofType($type)
            ->orderBy('rating', 'desc')
            ->paginate(12);

        $typeName = $types[$type];

        return view('front.partners.by-type', compact('partners', 'type', 'typeName'));
    }

    /**
     * Afficher les recommandations personnalisées pour l'utilisateur
     */
    public function recommendations()
    {
        $user = Auth::user();
        $recommendations = $this->recommendationService->getRecommendationsForUser($user, 12);
        
        // Get user's favorite partner IDs
        $userFavorites = [];
        if ($user) {
            $userFavorites = $user->favoritedPartners()->pluck('partners.id')->toArray();
        }

        // Statistiques de recommandation
        $stats = $user ? $this->recommendationService->getRecommendationStats($user) : null;

        return view('front.partners.recommendations', compact('recommendations', 'userFavorites', 'stats'));
    }

    /**
     * Recherche intelligente avec API AJAX
     */
    public function intelligentSearch(Request $request)
    {
        $criteria = [
            'type' => $request->input('type'),
            'city' => $request->input('city'),
            'min_rating' => $request->input('min_rating'),
            'services' => $request->input('services', []),
            'keyword' => $request->input('keyword'),
            'sort_by' => $request->input('sort_by', 'rating'),
            'sort_order' => $request->input('sort_order', 'desc'),
        ];

        $partners = $this->recommendationService->intelligentSearch($criteria);

        // Get user's favorite partner IDs if authenticated
        $userFavorites = [];
        if (Auth::check()) {
            $userFavorites = Auth::user()->favoritedPartners()->pluck('partners.id')->toArray();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'partners' => $partners,
                'count' => $partners->count(),
            ]);
        }

        return view('front.partners.search-results', compact('partners', 'userFavorites', 'criteria'));
    }
}
