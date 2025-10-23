<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Partner::query();
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }
        
        $partners = $query->latest()->paginate(15);
        
        return view('backoffice.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('backoffice.partners.create');
    }

    public function store(Request $request)
    {
        // La validation est déjà effectuée par le middleware partner.data
        // On récupère seulement les données nécessaires
        $data = $request->only([
            'name', 'type', 'description', 'email', 'phone', 'address', 
            'city', 'postal_code', 'website', 'license_number', 'specialization', 
            'status', 'contact_person', 'services', 'opening_hours', 
            'latitude', 'longitude', 'rating'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('partners/logos', 'public');
        }

        // Convert services array to clean format
        if (isset($data['services'])) {
            $data['services'] = array_filter($data['services']);
        }

        $partner = Partner::create($data);

        return redirect()->route('admin.partners.index')
            ->with('status', 'Partenaire créé avec succès.');
    }

    public function show(Request $request, Partner $partner = null)
    {
        // Récupérer le partenaire du middleware si disponible
        $partner = $request->get('partnerModel') ?? $partner;
        
        if (!$partner) {
            abort(404, 'Partenaire non trouvé.');
        }
        
        return view('backoffice.partners.show', compact('partner'));
    }

    public function edit(Request $request, Partner $partner = null)
    {
        // Récupérer le partenaire du middleware si disponible
        $partner = $request->get('partnerModel') ?? $partner;
        
        if (!$partner) {
            abort(404, 'Partenaire non trouvé.');
        }
        
        return view('backoffice.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner = null)
    {
        // Récupérer le partenaire du middleware si disponible
        $partner = $request->get('partnerModel') ?? $partner;
        
        if (!$partner) {
            abort(404, 'Partenaire non trouvé.');
        }

        // La validation est déjà effectuée par le middleware partner.data
        $data = $request->only([
            'name', 'type', 'description', 'email', 'phone', 'address', 
            'city', 'postal_code', 'website', 'license_number', 'specialization', 
            'status', 'contact_person', 'services', 'opening_hours', 
            'latitude', 'longitude', 'rating'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $data['logo'] = $request->file('logo')->store('partners/logos', 'public');
        }

        // Convert services array to clean format
        if (isset($data['services'])) {
            $data['services'] = array_filter($data['services']);
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')
            ->with('status', 'Partenaire modifié avec succès.');
    }

    public function destroy(Partner $partner)
    {
        // Delete logo if exists
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('status', 'Partenaire supprimé avec succès.');
    }

    public function toggleStatus(Partner $partner)
    {
        $newStatus = $partner->status === Partner::STATUS_ACTIVE 
            ? Partner::STATUS_INACTIVE 
            : Partner::STATUS_ACTIVE;
            
        $partner->update(['status' => $newStatus]);

        return response()->json([
            'status' => 'success',
            'new_status' => $newStatus,
            'message' => 'Statut modifié avec succès.'
        ]);
    }
}