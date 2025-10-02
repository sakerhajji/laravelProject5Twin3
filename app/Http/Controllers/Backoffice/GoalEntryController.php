<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\GoalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalEntryController extends Controller
{
    public function store(Request $request, Goal $goal)
    {
        $this->authorizeGoal($goal);
        $data = $request->validate([
            'entry_date' => 'required|date',
            'value' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:500',
        ]);
        $data['goal_id'] = $goal->id;
        GoalEntry::create($data);
        return back()->with('status', 'Progression ajoutée');
    }

    public function destroy(Goal $goal, GoalEntry $entry)
    {
        $this->authorizeGoal($goal);
        abort_unless($entry->goal_id === $goal->id, 404);
        $entry->delete();
        return back()->with('status', 'Entrée supprimée');
    }

    protected function authorizeGoal(Goal $goal): void
    {
        abort_unless($goal->user_id === Auth::id(), 403);
    }
}


