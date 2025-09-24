@extends('layouts/blankLayout')

@section('title', 'Health Tracker - Accueil')

@section('page-style')
<style>
  .layout-overlay, .drag-target { display: none !important; }
</style>
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <div class="card">
        <div class="card-body text-center">
          <img src="{{ asset('assets/img/favicon/favicon.ico') }}" alt="Logo" width="48" height="48" class="mb-3"/>
          <h3 class="mb-2">Health Tracker</h3>
          <p class="text-muted mb-4">Suivez vos repas, activités et objectifs santé.</p>
          @guest
            <div class="d-flex justify-content-center gap-2">
              <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
              <a href="{{ route('register') }}" class="btn btn-outline-primary">Créer un compte</a>
            </div>
          @endguest

          @auth
            <div class="d-flex justify-content-center gap-2">
              <a href="{{ route('dashboard') }}" class="btn btn-primary">Aller au dashboard</a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary">Se déconnecter</button>
              </form>
            </div>
          @endauth
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
