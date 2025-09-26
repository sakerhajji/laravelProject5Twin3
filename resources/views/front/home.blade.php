@extends('layouts.front')

@section('title', 'Accueil')

@section('content')
<div class="container">
    <div class="jumbotron bg-white mt-4">
        <h1 class="display-4">Bienvenue</h1>
        <p class="lead">Front Office avec Blade + Bootstrap</p>
        <hr class="my-4">
        @guest
            <a class="btn btn-primary btn-lg" href="{{ route('login') }}">Login</a>
        @else
            <a class="btn btn-primary btn-lg" href="{{ route('home') }}">Backoffice</a>
        @endguest
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-bolt fa-2x mb-2"></i>
                    <h5 class="card-title">Rapide</h5>
                    <p class="card-text">Front léger basé sur Blade et Bootstrap.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-layer-group fa-2x mb-2"></i>
                    <h5 class="card-title">Réutilisable</h5>
                    <p class="card-text">Mêmes assets que le backoffice (Stisla/Bootstrap).</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-check fa-2x mb-2"></i>
                    <h5 class="card-title">Prêt pour auth</h5>
                    <p class="card-text">Liens login/register intégrés.</p>
                </div>
            </div>
        </div>
    </div>
@endsection


