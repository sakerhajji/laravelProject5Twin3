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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(array_keys(Partner::getTypes()))],
            'description' => 'nullable|string|max:2000',
            'email' => 'required|email|unique:partners,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'website' => 'nullable|url|max:255',
            'license_number' => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(array_keys(Partner::getStatuses()))],
            'contact_person' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services' => 'nullable|array',
            'services.*' => 'string|max:255',
            'opening_hours' => 'nullable|array',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('partners/logos', 'public');
        }

        // Convert services array to clean format
        if (isset($data['services'])) {
            $data['services'] = array_filter($data['services']);
        }

        Partner::create($data);

        return redirect()->route('admin.partners.index')
            ->with('status', 'Partenaire créé avec succès.');
    }

    public function show(Partner $partner)
    {
        return view('backoffice.partners.show', compact('partner'));
    }

    public function edit(Partner $partner)
    {
        return view('backoffice.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(array_keys(Partner::getTypes()))],
            'description' => 'nullable|string|max:2000',
            'email' => ['required', 'email', Rule::unique('partners', 'email')->ignore($partner->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'website' => 'nullable|url|max:255',
            'license_number' => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(array_keys(Partner::getStatuses()))],
            'contact_person' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services' => 'nullable|array',
            'services.*' => 'string|max:255',
            'opening_hours' => 'nullable|array',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
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