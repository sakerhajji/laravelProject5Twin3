<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Objective;
use App\Models\UserObjective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObjectiveBrowseController extends Controller
{
    public function index()
    {
        $objectives = Objective::latest()->paginate(12);
        return view('front.objectives.index', compact('objectives'));
    }

    public function activate(Objective $objective)
    {
        UserObjective::firstOrCreate([
            'user_id' => Auth::id(),
            'objective_id' => $objective->id,
        ], ['status' => 'active']);
        return back()->with('status', 'Objectif activé');
    }
}


