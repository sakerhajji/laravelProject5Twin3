@extends('layouts.front')

@section('title', 'Accueil')

@section('content')
<section class="py-5 bg-light rounded-3 mx-2" data-aos="fade-up">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7" data-aos="fade-right">
                <h1 class="display-5 fw-bold mb-3">Suivez votre santé avec <span class="text-primary">Health Tracker</span></h1>
                <p class="lead text-secondary">Un tableau de bord <span class="fw-semibold">lisible</span> et <span class="fw-semibold">moderne</span> pour suivre vos progrès et gérer vos objectifs au quotidien.</p>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Commencer
                        </a>
                        <a href="{{ route('front.objectives.index') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fa-solid fa-bullseye me-2"></i>Parcourir les objectifs
                        </a>
                        <a href="#features" class="btn btn-outline-secondary btn-lg">En savoir plus</a>
                    @else
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-gauge-high me-2"></i>Aller au tableau de bord
                        </a>
                        <a href="{{ route('front.objectives.index') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fa-solid fa-bullseye me-2"></i>Parcourir les objectifs
                        </a>
                        <a href="{{ route('front.progress.index') }}" class="btn btn-outline-success btn-lg">
                            <i class="fa-solid fa-bars-progress me-2"></i>Mes progrès
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-lg">Paramètres</a>
                    @endguest
                </div>
                <div class="d-flex flex-wrap gap-4 mt-4 text-secondary small">
                    <div><i class="fa-solid fa-shield-heart me-1 text-primary"></i> Données sécurisées</div>
                    <div><i class="fa-solid fa-mobile-screen-button me-1 text-primary"></i> 100% responsive</div>
                    <div><i class="fa-solid fa-wand-magic-sparkles me-1 text-primary"></i> UX moderne</div>
                </div>
            </div>
            <div class="col-lg-5 text-center" data-aos="fade-left">
                <div class="p-2">
                    <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?q=80&w=1200&auto=format&fit=crop" class="img-fluid rounded-4 shadow-sm" alt="Fitness illustration" />
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="py-5" data-aos="fade-up">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Tout ce dont vous avez besoin</h2>
            <p class="text-secondary">Un kit minimaliste pour démarrer rapidement et évoluer ensuite.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="0">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1554284126-aa88f22d8b74?q=80&w=1200&auto=format&fit=crop" class="card-img-top" alt="Rapide" />
                    <div class="card-body text-center p-4">
                        <i class="fa-solid fa-bolt fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">Rapide</h5>
                        <p class="card-text text-muted">Bootstrap 5 + Blade, temps de chargement optimisé, sans complexité inutile.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1545996124-0501ebae84d0?q=80&w=1200&auto=format&fit=crop" class="card-img-top" alt="Évolutif" />
                    <div class="card-body text-center p-4">
                        <i class="fa-solid fa-layer-group fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">Évolutif</h5>
                        <p class="card-text text-muted">Structure claire Front / Backoffice pour grandir proprement.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1518152006812-edab29b069ac?q=80&w=1200&auto=format&fit=crop" class="card-img-top" alt="Sécurisé" />
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

<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-center" data-aos="fade-up">
            <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1200&auto=format&fit=crop" class="img-fluid rounded-4 shadow-sm" alt="Mes données" />
            </div>
            <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                <h3 class="fw-bold mb-3">Vos données, au bon endroit</h3>
                <p class="text-muted">Visualisez l'essentiel: rythme cardiaque, sommeil, hydratation et habitudes. Un design clair, des cartes lisibles, et une navigation ultra-simple.</p>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><i class="fa-solid fa-circle-check text-primary me-2"></i>Cartes et graphiques lisibles</li>
                    <li class="mb-2"><i class="fa-solid fa-circle-check text-primary me-2"></i>Mode responsive mobile-first</li>
                    <li class="mb-2"><i class="fa-solid fa-circle-check text-primary me-2"></i>Accès rapide au profil et paramètres</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="container mt-5" data-aos="fade-up">
        <div class="text-center mb-4">
            <h4 class="fw-bold">Ils utilisent Health Tracker</h4>
            <p class="text-secondary">Quelques retours de nos utilisateurs</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://i.pravatar.cc/80?img=12" class="rounded-circle me-3" width="48" height="48" alt="avatar" />
                            <div>
                                <div class="fw-semibold">Sarah</div>
                                <div class="text-muted small">Sportive</div>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Interface super claire, j'adore les cartes et la navigation rapide.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://i.pravatar.cc/80?img=5" class="rounded-circle me-3" width="48" height="48" alt="avatar" />
                            <div>
                                <div class="fw-semibold">Yassine</div>
                                <div class="text-muted small">Développeur</div>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Simple et efficace. Le responsive est nickel sur mobile.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://i.pravatar.cc/80?img=32" class="rounded-circle me-3" width="48" height="48" alt="avatar" />
                            <div>
                                <div class="fw-semibold">Amel</div>
                                <div class="text-muted small">Etudiante</div>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Un look moderne et des appels à l'action bien placés.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="parallax py-5" style="background-image:url('https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?q=80&w=1600&auto=format&fit=crop');">
    <div class="container text-center text-white py-5" data-aos="zoom-in">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="fw-bold mb-3">Des objectifs simples, des résultats concrets</h2>
                <p class="lead mb-4">Hydratation, sommeil, activité — restez régulier, on s’occupe du reste.</p>
                <div class="d-flex justify-content-center gap-4">
                    <div>
                        <div class="h2 mb-0" data-counter data-target="7000">0</div>
                        <div class="small">Pas / jour</div>
                    </div>
                    <div>
                        <div class="h2 mb-0" data-counter data-target="8">0</div>
                        <div class="small">Heures de sommeil</div>
                    </div>
                    <div>
                        <div class="h2 mb-0" data-counter data-target="2">0</div>
                        <div class="small">Litres d’eau</div>
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


