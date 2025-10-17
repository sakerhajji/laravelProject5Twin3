<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Objective;
use App\Models\Progress;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function show(User $user)
    {
        $assignedObjectives = $user->objectives()->with(['progresses' => function($q) use ($user){
            $q->where('user_id', $user->id)->latest('entry_date');
        }])->get();

        $recentProgress = Progress::with('objective')
            ->where('user_id', $user->id)
            ->latest('entry_date')
            ->limit(50)
            ->get();

        $badges = UserBadge::where('user_id', $user->id)->latest('earned_at')->get();

        return view('backoffice.users.show', compact('user','assignedObjectives','recentProgress','badges'));
    }
}
