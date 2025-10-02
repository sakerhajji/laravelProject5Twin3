<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Objective;
use App\Models\Progress;
use App\Models\UserBadge;
use App\Http\Requests\StoreProgressRequest;
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

    public function store(StoreProgressRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $progress = Progress::create($data);
        
        // Vérifier et attribuer des badges
        UserBadge::checkAndAwardBadges(Auth::id(), $data['objective_id']);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Progrès ajouté avec succès'
            ]);
        }
        
        return back()->with('status', 'Progrès ajouté');
    }
}


