@extends('layouts.front')

@section('title', 'Accueil')

@section('content')
<section class="py-5 bg-light rounded-3 mx-2">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold mb-3">Suivez votre santé avec <span class="text-primary">Health Tracker</span></h1>
                <p class="lead text-muted">Un tableau de bord simple et épuré pour visualiser vos données, suivre vos progrès et gérer vos objectifs au quotidien.</p>
                <div class="d-flex gap-2 mt-3">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Commencer
                        </a>
                        <a href="#features" class="btn btn-outline-primary btn-lg">En savoir plus</a>
                    @else
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-gauge-high me-2"></i>Aller au tableau de bord
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-lg">Paramètres</a>
                    @endguest
                </div>
                <div class="d-flex gap-4 mt-4 text-muted small">
                    <div><i class="fa-solid fa-shield-heart me-1 text-primary"></i> Données sécurisées</div>
                    <div><i class="fa-solid fa-mobile-screen-button me-1 text-primary"></i> 100% responsive</div>
                    <div><i class="fa-solid fa-wand-magic-sparkles me-1 text-primary"></i> UX moderne</div>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="p-4 bg-white border rounded-4 shadow-sm">
                    <div class="row row-cols-3 g-2">
                        <div class="col"><div class="border rounded-3 py-4 fw-bold">7k<br><span class="text-muted small">Pas</span></div></div>
                        <div class="col"><div class="border rounded-3 py-4 fw-bold">68<br><span class="text-muted small">BPM</span></div></div>
                        <div class="col"><div class="border rounded-3 py-4 fw-bold">1.9k<br><span class="text-muted small">Kcal</span></div></div>
                        <div class="col"><div class="border rounded-3 py-4 fw-bold">8h<br><span class="text-muted small">Sommeil</span></div></div>
                        <div class="col"><div class="border rounded-3 py-4 fw-bold">2L<br><span class="text-muted small">Eau</span></div></div>
                        <div class="col"><div class="border rounded-3 py-4 fw-bold">5/7<br><span class="text-muted small">Habitudes</span></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Tout ce dont vous avez besoin</h2>
            <p class="text-muted">Un kit minimaliste pour démarrer rapidement et évoluer ensuite.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fa-solid fa-bolt fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">Rapide</h5>
                        <p class="card-text text-muted">Bootstrap 5 + Blade, temps de chargement optimisé, sans complexité inutile.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fa-solid fa-layer-group fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">Évolutif</h5>
                        <p class="card-text text-muted">Structure claire Front / Backoffice pour grandir proprement.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fa-solid fa-user-shield fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">Sécurisé</h5>
                        <p class="card-text text-muted">Routes protégées par rôle (admin / client), profil et mot de passe.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container text-center">
        <h3 class="fw-bold mb-3">Prêt à démarrer ?</h3>
        <p class="text-muted mb-4">Créez un compte en 1 minute et suivez vos progrès au quotidien.</p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg"><i class="fa-regular fa-id-card me-2"></i>Créer un compte</a>
        @else
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg"><i class="fa-solid fa-gauge-high me-2"></i>Ouvrir le tableau de bord</a>
        @endguest
    </div>
</section>
@endsection


