<?php

namespace App\Http\Controllers;

use App\Models\Asymptome;
use App\Models\Maladie;
use Illuminate\Http\Request;

class AsymptomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asymptomes = Asymptome::with('maladies')->paginate(10);
        return view('asymptomes.index', compact('asymptomes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $maladies = Maladie::all();
        return view('asymptomes.create', compact('maladies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $asymptome = Asymptome::create($data);

        // Attach maladies if selected
        if ($request->has('maladie_ids')) {
            $asymptome->maladies()->attach($request->maladie_ids);
        }

        return redirect()->route('asymptomes.index')->with('success', 'Asymptôme créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asymptome $asymptome)
    {
        $asymptome->load('maladies');
        return view('asymptomes.show', compact('asymptome'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asymptome $asymptome)
    {
        $maladies = Maladie::all();
        $asymptome->load('maladies');
        return view('asymptomes.edit', compact('asymptome', 'maladies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asymptome $asymptome)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $asymptome->update($data);

        // Sync maladies
        if ($request->has('maladie_ids')) {
            $asymptome->maladies()->sync($request->maladie_ids);
        } else {
            $asymptome->maladies()->detach();
        }

        return redirect()->route('asymptomes.index')->with('success', 'Asymptôme mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asymptome $asymptome)
    {
        $asymptome->delete();
        return redirect()->route('asymptomes.index')->with('success', 'Asymptôme supprimé.');
    }
}
