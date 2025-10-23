<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function index(Request $request)
    {
        $goals = Goal::where('user_id', Auth::id())
            ->latest()->paginate(10);
        return view('backoffice.goals.index', compact('goals'));
    }

    public function create()
    {
        return view('backoffice.goals.create');
    }

    public function show(Goal $goal)
    {
        $this->authorizeGoal($goal);
        $entries = $goal->entries()->orderBy('entry_date')->get();
        return view('backoffice.goals.show', compact('goal', 'entries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:steps,calories,water,sleep,custom',
            'target_value' => 'required|numeric|min:0.01',
            'unit' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        $data['user_id'] = Auth::id();
        $data['unit'] = $data['unit'] ?? '';
        Goal::create($data);
        return redirect()->route('back.goals.index')->with('status', 'Objectif créé');
    }

    public function edit(Goal $goal)
    {
        $this->authorizeGoal($goal);
        return view('backoffice.goals.edit', compact('goal'));
    }

    public function update(Request $request, Goal $goal)
    {
        $this->authorizeGoal($goal);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:steps,calories,water,sleep,custom',
            'target_value' => 'required|numeric|min:0.01',
            'unit' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,paused,completed',
        ]);
        $goal->update($data);
        return redirect()->route('back.goals.index')->with('status', 'Objectif mis à jour');
    }

    public function destroy(Goal $goal)
    {
        $this->authorizeGoal($goal);
        $goal->delete();
        return redirect()->route('back.goals.index')->with('status', 'Objectif supprimé');
    }

    protected function authorizeGoal(Goal $goal): void
    {
        abort_unless($goal->user_id === Auth::id(), 403);
    }
}


