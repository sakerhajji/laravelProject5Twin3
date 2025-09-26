<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Front Office') — Laravel - Stisla</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @stack('css')
    <style>
    body { padding-top: 72px; }
    .navbar-brand { font-weight: 800; letter-spacing: .3px; }
    .nav-link { font-weight: 500; }
    footer { background: #f8f9fa; border-top: 1px solid #e9ecef; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="fa-solid fa-heart-pulse text-primary me-2"></i>
                Health Tracker
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#frontNavbar" aria-controls="frontNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="frontNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Accueil</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Tableau de bord</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Mes données</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}">Paramètres</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Tableau de bord</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Mes données</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Paramètres</a></li>
                    @endauth
                </ul>
                <div class="d-flex">
                    @guest
                        <a class="btn btn-primary" href="{{ route('login') }}">Connexion</a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-user me-1"></i>{{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('home') }}">Backoffice</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="py-4 mt-auto">
        <div class="container text-center text-muted small">
            &copy; {{ date('Y') }} Health Tracker — Tous droits réservés
        </div>
    </footer>

    <!-- Bootstrap 5 bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>


