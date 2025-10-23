<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Aliment;
use App\Models\Repas;
use Illuminate\Http\Request;
use App\Models\User;
class RepasController extends Controller
{
    public function index(Request $request)
    {
        $query = Repas::with('user');

        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $repas = $query->paginate(9);

        return view('backoffice.repas.index', compact('repas'));
    }

    public function create()
    {
        $aliments = Aliment::orderBy('nom')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('backoffice.repas.create', compact('aliments', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'aliments' => 'required|array|min:1',
            'aliments.*.id' => 'required|exists:aliments,id',
            'aliments.*.quantite' => 'required|numeric|min:0.01',
        ]);

        $repas = Repas::create($request->only('nom', 'description', 'user_id'));

        $alimentsToSync = [];
        foreach ($request->aliments as $alimentData) {
            $alimentsToSync[$alimentData['id']] = ['quantite' => $alimentData['quantite']];
        }

        $repas->aliments()->sync($alimentsToSync);

        return redirect()->route('admin.repas.index')->with('success', 'Repas créé et assigné avec succès.');
    }

    public function show(Repas $repa)
    {
        // Note: Laravel route model binding uses the parameter name, so $repa is correct here.
        return view('backoffice.repas.show', ['repas' => $repa->load('aliments')]);
    }

    public function edit(Repas $repa)
    {
        $aliments = Aliment::orderBy('nom')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('backoffice.repas.edit', [
            'repas' => $repa->load('aliments'),
            'aliments' => $aliments,
            'users' => $users,
        ]);
    }

    public function update(Request $request, Repas $repa)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'aliments' => 'sometimes|array',
            'aliments.*.id' => 'required|exists:aliments,id',
            'aliments.*.quantite' => 'required|numeric|min:0.01',
        ]);

        $repa->update($request->only('nom', 'description', 'user_id'));

        $alimentsToSync = [];
        if ($request->has('aliments')) {
            foreach ($request->aliments as $alimentData) {
                $alimentsToSync[$alimentData['id']] = ['quantite' => $alimentData['quantite']];
            }
        }

        $repa->aliments()->sync($alimentsToSync);

        return redirect()->route('admin.repas.index')->with('success', 'Repas mis à jour avec succès.');
    }

    public function destroy(Repas $repa)
    {
        $repa->aliments()->detach();
        $repa->delete();

        return redirect()->route('admin.repas.index')->with('success', 'Repas supprimé avec succès.');
    }
}
