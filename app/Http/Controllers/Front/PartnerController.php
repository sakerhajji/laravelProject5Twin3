<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerController extends Controller
{
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
        
        return view('front.partners.index', compact('partners', 'cities'));
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

    public function toggleFavorite(Request $request, Partner $partner)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vous devez être connecté.'], 401);
        }

        $user = Auth::user();
        $favorite = $user->favoritedPartners()->where('partner_id', $partner->id)->first();

        if ($favorite) {
            // Remove from favorites
            $user->favoritedPartners()->detach($partner->id);
            $action = 'removed';
            $message = 'Partenaire retiré des favoris.';
        } else {
            // Add to favorites
            $user->favoritedPartners()->attach($partner->id);
            $action = 'added';
            $message = 'Partenaire ajouté aux favoris.';
        }

        return response()->json([
            'status' => 'success',
            'action' => $action,
            'message' => $message
        ]);
    }

    public function favorites()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $favoritePartners = Auth::user()->favoritedPartners()->paginate(12);

        return view('front.partners.favorites', compact('favoritePartners'));
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
}