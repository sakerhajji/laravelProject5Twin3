<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * Redirection conditionnelle selon le rôle
     */
    public function index()
    {
        $user = Auth::user();
        
        if (in_array($user->role, ['admin', 'superadmin'])) {
            // Admin va vers le backoffice
            return redirect()->route('admin.dashboard');
        } else {
            // User va vers le frontend
            return redirect()->route('front.home');
        }
    }

    public function blank()
    {
        return view('layouts.blank-page');
    }
}
