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
        $recommended = Objective::inRandomOrder()->limit(3)->get();
        return view('front.objectives.index', compact('objectives','recommended'));
    }

    public function show(Objective $objective, Request $request)
    {
        $days = (int) $request->query('days', 30);
        $days = in_array($days, [7,30,90]) ? $days : 30;
        $series = $objective->seriesForUser(Auth::id(), $days);
        $percent = $objective->computeProgressPercent(Auth::id(), (string) $days);
        return view('front.objectives.show', compact('objective','series','percent','days'));
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


