<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Objective;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $myObjectives = Objective::whereHas('users', function($q){
            $q->where('user_id', Auth::id());
        })->with(['progresses' => function($q){ $q->where('user_id', Auth::id())->latest(); }])->get();
        return view('front.progress.index', compact('myObjectives'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'objective_id' => 'required|exists:objectives,id',
            'entry_date' => 'required|date',
            'value' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:500',
        ]);
        $data['user_id'] = Auth::id();
        Progress::create($data);
        return back()->with('status', 'Progrès ajouté');
    }
}


