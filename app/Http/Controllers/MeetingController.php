<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\MeetingInvitation;
use Illuminate\Support\Facades\Mail;

class MeetingController extends Controller
{
    // Show form to select users
    public function showForm()
    {
        $users = User::where('role', '!=', 'admin')->get(); // Only non-admin
        return view('meet-form', compact('users'));
    }

    // Handle form submission
    public function start(Request $request)
    {
        $request->validate([
            'users' => 'required|array|min:1'
        ]);

        $roomName = 'Meet_' . Str::random(10);
        $invitedUsers = User::whereIn('id', $request->users)->get();

        foreach ($invitedUsers as $user) {
            Mail::to($user->email)->send(new MeetingInvitation($roomName));
        }

        return view('meet', compact('roomName'));
    }
}
