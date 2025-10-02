<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Aliment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlimentController extends Controller
{
    public function index(Request $request)
    {
        $query = Aliment::query();

        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where('nom', 'like', "%{$search}%");
        }

        $aliments = $query->paginate(10);

        return view('backoffice.aliments.index', compact('aliments'));
    }

    public function create()
    {
        return view('backoffice.aliments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'calories' => 'nullable|numeric|min:0',
            'proteines' => 'nullable|numeric|min:0',
            'glucides' => 'nullable|numeric|min:0',
            'lipides' => 'nullable|numeric|min:0',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('image_path');

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('aliments', 'public');
            $data['image_path'] = $path;
        }

        Aliment::create($data);

        return redirect()->route('admin.aliments.index')->with('success', 'Aliment créé avec succès.');
    }

    public function edit(Aliment $aliment)
    {
        return view('backoffice.aliments.edit', compact('aliment'));
    }

    public function update(Request $request, Aliment $aliment)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'calories' => 'nullable|numeric|min:0',
            'proteines' => 'nullable|numeric|min:0',
            'glucides' => 'nullable|numeric|min:0',
            'lipides' => 'nullable|numeric|min:0',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('image_path');

        if ($request->hasFile('image_path')) {
            // Supprimer l'ancienne image si elle existe
            if ($aliment->image_path) {
                Storage::disk('public')->delete($aliment->image_path);
            }
            $path = $request->file('image_path')->store('aliments', 'public');
            $data['image_path'] = $path;
        }

        $aliment->update($data);

        return redirect()->route('admin.aliments.index')->with('success', 'Aliment mis à jour avec succès.');
    }

    public function destroy(Aliment $aliment)
    {
        // Supprimer l'image associée si elle existe
        if ($aliment->image_path) {
            Storage::disk('public')->delete($aliment->image_path);
        }

        $aliment->delete();

        return redirect()->route('admin.aliments.index')->with('success', 'Aliment supprimé avec succès.');
    }
}
