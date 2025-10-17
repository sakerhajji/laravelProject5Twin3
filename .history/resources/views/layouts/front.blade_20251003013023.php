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
    /* Circular ring progress */
    .ring { width: 56px; height: 56px; border-radius: 50%; display: grid; place-items: center; background:
      conic-gradient(var(--color, #0d6efd) calc(var(--val,0) * 1%), #e9ecef 0);
    }
    .ring::before { content: ""; width: 44px; height: 44px; border-radius: 50%; background: #fff; display:block; }
    .ring > span { position: absolute; font-size: .75rem; font-weight: 700; }
    /* Skeleton */
    .skeleton { position: relative; overflow: hidden; background: #e9ecef; }
    .skeleton::after { content: ""; position: absolute; inset: 0; transform: translateX(-100%);
      background: linear-gradient(90deg, transparent, rgba(255,255,255,.5), transparent);
      animation: shimmer 1.6s infinite; }
    @keyframes shimmer { 100% { transform: translateX(100%); } }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm fixed-top w-100" style="left:0;right:0;">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="fa-solid fa-heart-pulse text-primary me-2"></i>
                Health Tracker
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#frontNavbar" aria-controls="frontNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="frontNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0" style="justify-content: center;">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}"><i class="fa-solid fa-house me-1 text-secondary"></i>Accueil</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('front.smart-dashboard.index') }}"><i class="fa-solid fa-gauge-high me-1 text-primary"></i>Dashboard IA</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="healthDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-heartbeat text-danger me-1"></i>Santé & Bien-être
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="healthDropdown">
                                <li><h6 class="dropdown-header"><i class="fas fa-hospital text-success me-2"></i>Partenaires</h6></li>
                                <li><a class="dropdown-item" href="{{ route('front.partners.index') }}">
                                    <i class="fas fa-hospital me-2 text-success"></i>Partenaires Santé
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('front.partners.favorites') }}">
                                    <i class="fas fa-heart text-danger me-2"></i>Mes Favoris
                                </a></li>
                               
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('front.maladie.diagnose') }}">
                            <i class="fa-solid fa-stethoscope text-primary me-1"></i>Diagnostic Maladies
                        </a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('front.maladie.history') }}">
                            <i class="fa-solid fa-clock-rotate-left text-info me-1"></i>Historique Maladies
                        </a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('repas.index') }}"><i class="fa-solid fa-utensils me-1 text-warning"></i>Mes Repas</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('front.profile.show') }}"><i class="fa-solid fa-gear me-1 text-secondary"></i>Paramètres</a></li>

                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="fa-solid fa-database me-1 text-secondary"></i>Mes données</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="fa-solid fa-hospital me-1 text-success"></i>Partenaires</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="fa-solid fa-gear me-1 text-secondary"></i>Paramètres</a></li>
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

                                <li><a class="dropdown-item" href="{{ route('front.profile.show') }}">Profil</a></li>
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

    <main class="py-4 d-flex justify-content-center">
        <div class="content-wrapper w-100" style="min-width:320px; max-width:900px; margin:0 auto; padding-bottom:32px;">
            @yield('content')
        </div>
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

            // Flash toast
            const flash = document.getElementById('flash-toast');
            if (flash) { const t = new bootstrap.Toast(flash); t.show(); }
        });
    </script>
    @if(session('status'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1080">
        <div id="flash-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">{{ session('status') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    @stack('scripts')
</body>
</html>


