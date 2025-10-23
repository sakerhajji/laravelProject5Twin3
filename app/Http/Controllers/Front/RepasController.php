<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $repas = $user->repas()->with('aliments')->get();

        return view('front.repas.index', compact('repas'));
    }
}
