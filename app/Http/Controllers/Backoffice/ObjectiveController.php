<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Objective;
use App\Models\User;
use App\Models\UserObjective;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    public function index()
    {
        $objectives = Objective::latest()->paginate(12);
        return view('backoffice.objectives.index', compact('objectives'));
    }

    public function create()
    {
        return view('backoffice.objectives.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:20',
            'target_value' => 'required|numeric|min:0.01',
            'category' => 'required|in:activite,nutrition,sommeil,sante',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_url' => 'nullable|url',
        ]);

        // Gérer l'upload d'image
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/objectives'), $imageName);
            $data['cover_url'] = '/images/objectives/' . $imageName;
        }

        Objective::create($data);
        return redirect()->route('admin.objectives.index')->with('status', 'Objectif type créé');
    }

    public function edit(Objective $objective)
    {
        return view('backoffice.objectives.edit', compact('objective'));
    }

    public function update(Request $request, Objective $objective)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:20',
            'target_value' => 'required|numeric|min:0.01',
            'category' => 'required|in:activite,nutrition,sommeil,sante',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_url' => 'nullable|url',
        ]);

        // Gérer l'upload d'image
        if ($request->hasFile('cover_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($objective->cover_url && file_exists(public_path($objective->cover_url))) {
                unlink(public_path($objective->cover_url));
            }
            
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/objectives'), $imageName);
            $data['cover_url'] = '/images/objectives/' . $imageName;
        }

        $objective->update($data);
        return redirect()->route('admin.objectives.index')->with('status', 'Objectif type mis à jour');
    }

    public function destroy(Objective $objective)
    {
        $objective->delete();
        return back()->with('status', 'Objectif type supprimé');
    }

    // Assign objective to user
    public function assignments()
    {
        $users = User::orderBy('name')->get();
        $objectives = Objective::orderBy('title')->get();
        $links = UserObjective::with(['user','objective'])->latest()->paginate(20);
        return view('backoffice.objectives.assign', compact('users','objectives','links'));
    }

    public function assign(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'objective_id' => 'required|exists:objectives,id',
        ]);
        UserObjective::firstOrCreate($data, ['status' => 'active']);
        return back()->with('status', 'Objectif attribué à l’utilisateur');
    }

    public function unassign(UserObjective $link)
    {
        $link->delete();
        return back()->with('status', 'Attribution supprimée');
    }
}


