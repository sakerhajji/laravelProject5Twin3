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
    <!-- AOS Animations -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @stack('css')
    <style>
    body { padding-top: 72px; }
    .navbar-brand { font-weight: 800; letter-spacing: .3px; color:#0b1220 !important; }
    .navbar-light .navbar-nav .nav-link { color: #0b1220 !important; opacity: 1 !important; font-weight: 600; }
    .navbar-light .navbar-nav .nav-link:hover { color:#0d6efd !important; }
    .navbar-light .navbar-nav .nav-link.active { color:#0d6efd !important; }
    .dropdown-menu { border-radius: .65rem; }
    footer { background: #f8f9fa; border-top: 1px solid #e9ecef; }
    .glass { backdrop-filter: blur(8px); background: rgba(255,255,255,.6); }
    .gradient-divider { height: 2px; background: linear-gradient(90deg, #0d6efd, #20c997, #6f42c1); opacity: .25; }
    .parallax {
        background-attachment: fixed; background-size: cover; background-position: center; color: #fff;
        position: relative;
    }
    .parallax::after { content: ""; position: absolute; inset: 0; background: rgba(0,0,0,.35); }
    .parallax > .container { position: relative; z-index: 1; }
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
    <!-- AOS Animations -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            if (window.AOS) { AOS.init({ once: true, duration: 800, easing: 'ease-out-quart' }); }
            // Simple counter animation
            const counters = document.querySelectorAll('[data-counter]');
            const observe = new IntersectionObserver((entries)=>{
                entries.forEach(entry=>{
                    if(entry.isIntersecting){
                        const el = entry.target; const target = parseInt(el.getAttribute('data-target')); let cur = 0;
                        const inc = Math.max(1, Math.floor(target/60));
                        const t = setInterval(()=>{ cur += inc; if(cur >= target){ cur = target; clearInterval(t);} el.textContent = cur; }, 16);
                        observe.unobserve(el);
                    }
                });
            }, { threshold: .4 });
            counters.forEach(c=>observe.observe(c));
        });
    </script>

    @stack('scripts')
</body>
</html>


