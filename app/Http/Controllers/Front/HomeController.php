<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Vérifier si l'utilisateur est connecté et s'il est admin
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            // Rediriger l'admin vers le dashboard backoffice
            return redirect()->route('home')->with('warning', 'Accès à la page frontend non autorisé pour les administrateurs.');
        }
        
        return view('front.home');
    }
}


