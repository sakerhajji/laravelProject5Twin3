<?php

namespace App\Http\Controllers;

use App\Models\Maladie;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Asymptome;

class MaladieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all maladies with users and asymptomes, paginate 10 per page
        $maladies = Maladie::with(['users', 'asymptomes'])->paginate(10);
        return view('maladies.index', compact('maladies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $asymptomes = Asymptome::active()->get(); // Only active symptoms
        return view('maladies.create', compact('asymptomes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie' => 'nullable|string|max:100',
            'traitement' => 'nullable|string',
            'prevention' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'asymptome_ids' => 'array',
            'asymptome_ids.*' => 'exists:asymptomes,id',
        ]);

        $maladie = Maladie::create($data);

        // Attach users if selected
        if ($request->has('user_ids')) {
            $maladie->users()->attach($request->user_ids);
        }

        // Attach asymptomes if selected
        if ($request->has('asymptome_ids')) {
            $maladie->asymptomes()->attach($request->asymptome_ids);
        }

        return redirect()->route('maladies.index')->with('success', 'Maladie créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Maladie $maladie)
    {
        $maladie->load(['users', 'asymptomes']); // eager load users and asymptomes
        return view('maladies.show', compact('maladie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maladie $maladie)
    {
        $asymptomes = Asymptome::active()->get();
        $maladie->load(['users', 'asymptomes']); // Load existing symptoms and users
        return view('maladies.edit', compact('maladie', 'asymptomes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maladie $maladie)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'traitement' => 'nullable|string',
            'prevention' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'asymptome_ids' => 'array',
            'asymptome_ids.*' => 'exists:asymptomes,id',
        ]);

        $maladie->update($data);

        // Sync users (update pivot table)
        if ($request->has('user_ids')) {
            $maladie->users()->sync($request->user_ids);
        } else {
            $maladie->users()->detach();
        }

        // Sync asymptomes (update pivot table)
        if ($request->has('asymptome_ids')) {
            $maladie->asymptomes()->sync($request->asymptome_ids);
        } else {
            $maladie->asymptomes()->detach();
        }

        return redirect()->route('maladies.index')->with('success', 'Maladie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maladie $maladie)
    {
        // Detach related records before deletion
        $maladie->users()->detach();
        $maladie->asymptomes()->detach();

        $maladie->delete();
        return redirect()->route('maladies.index')->with('success', 'Maladie supprimée.');
    }
}
