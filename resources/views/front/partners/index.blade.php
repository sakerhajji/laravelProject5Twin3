@extends('layouts.front')

@section('title', 'Nos Partenaires Santé')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-primary mb-3">
                <i class="fas fa-hospital-user me-3"></i>Nos Partenaires Santé
            </h1>
            <p class="lead text-muted">Découvrez notre réseau de professionnels de la santé pour vous accompagner</p>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Accueil</a></li>
                    <li class="breadcrumb-item active">Partenaires de Santé</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient-primary text-white rounded-4 p-5 shadow-lg">
                <div class="text-center">
                    <h2 class="display-6 fw-bold mb-3">Trouvez le partenaire santé qu'il vous faut</h2>
                    <p class="lead mb-0">Notre réseau de professionnels de santé qualifiés vous accompagne dans votre parcours bien-être</p>
                </div>
            </div>
        </div>
    </div>

        <!-- Quick Access Categories -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-th-large"></i> Catégories de Partenaires</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach(\App\Models\Partner::getTypes() as $key => $value)
                                <div class="col-lg-2 col-md-4 col-sm-6 col-6 mb-3">
                                    <a href="{{ route('front.partners.by-type', $key) }}" class="btn btn-outline-primary btn-lg btn-block py-3 category-card" data-type="{{ $key }}">
                                        <div class="category-icon mb-2">
                                            @if($key == 'doctor')
                                                <i class="fas fa-user-md fa-2x text-primary"></i>
                                            @elseif($key == 'gym')
                                                <i class="fas fa-dumbbell fa-2x text-success"></i>
                                            @elseif($key == 'laboratory')
                                                <i class="fas fa-flask fa-2x text-info"></i>
                                            @elseif($key == 'pharmacy')
                                                <i class="fas fa-pills fa-2x text-warning"></i>
                                            @elseif($key == 'nutritionist')
                                                <i class="fas fa-apple-alt fa-2x text-success"></i>
                                            @else
                                                <i class="fas fa-brain fa-2x text-purple"></i>
                                            @endif
                                        </div>
                                        <div class="category-name">
                                            <small class="font-weight-bold">{{ $value }}</small>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-filter"></i> Filtres de Recherche</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('front.partners.index') }}" class="row align-items-end">
                            <div class="col-md-3">
                                <label for="partner-type" class="form-label fw-bold">Type de partenaire</label>
                                <select name="type" id="partner-type" class="form-select">
                                    <option value="">Tous les types</option>
                                    @foreach(\App\Models\Partner::getTypes() as $key => $value)
                                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="partner-city" class="form-label fw-bold">Ville</label>
                                <select name="city" id="partner-city" class="form-select">
                                    <option value="">Toutes les villes</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="partner-search" class="form-label fw-bold">Recherche</label>
                                <div class="input-group">
                                    <input type="text" name="search" id="partner-search" class="form-control" placeholder="Nom, spécialisation..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('front.partners.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-undo"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Count -->
        @if($partners->count() > 0)
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>{{ $partners->total() }}</strong> partenaire(s) trouvé(s)
                        @if(request()->filled('type') || request()->filled('city') || request()->filled('search'))
                            selon vos critères de recherche
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Partners Grid -->
        <div class="row">
            @forelse($partners as $partner)
                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="card partner-card h-100 shadow-sm">
                        <!-- Partner Image/Logo -->
                        <div class="partner-image-container position-relative">
                            @if($partner->logo)
                                <img src="{{ asset('storage/' . $partner->logo) }}"
                                     class="card-img-top partner-image"
                                     alt="{{ $partner->name }}"
                                     style="height: 220px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center partner-image-placeholder" style="height: 220px;">
                                    @if($partner->type == 'doctor')
                                        <i class="fas fa-user-md fa-4x text-white opacity-75"></i>
                                    @elseif($partner->type == 'gym')
                                        <i class="fas fa-dumbbell fa-4x text-white opacity-75"></i>
                                    @elseif($partner->type == 'laboratory')
                                        <i class="fas fa-flask fa-4x text-white opacity-75"></i>
                                    @elseif($partner->type == 'pharmacy')
                                        <i class="fas fa-pills fa-4x text-white opacity-75"></i>
                                    @elseif($partner->type == 'nutritionist')
                                        <i class="fas fa-apple-alt fa-4x text-white opacity-75"></i>
                                    @else
                                        <i class="fas fa-brain fa-4x text-white opacity-75"></i>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Favorite Button -->
                            @auth
                                <button class="btn btn-sm btn-light position-absolute favorite-btn toggle-favorite rounded-circle shadow"
                                        style="top: 10px; right: 10px; width: 40px; height: 40px;"
                                        data-partner-id="{{ $partner->id }}"
                                        title="Ajouter aux favoris">
                                    <i class="fas fa-heart text-danger"></i>
                                </button>
                            @endauth
                            
                            <!-- Type Badge -->
                            <div class="position-absolute" style="bottom: 10px; left: 10px;">
                                <span class="badge badge-primary badge-lg px-3 py-2">{{ $partner->type_label }}</span>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body">
                            <!-- Partner Name & Rating -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0 font-weight-bold">{{ $partner->name }}</h5>
                                @if($partner->rating > 0)
                                    <div class="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $partner->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="rating-text ml-1 text-muted">{{ number_format($partner->rating, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Specialization -->
                            @if($partner->specialization)
                                <p class="text-primary font-weight-bold mb-2">
                                    <i class="fas fa-certificate"></i> {{ $partner->specialization }}
                                </p>
                            @endif
                            
                            <!-- Description -->
                            @if($partner->description)
                                <p class="card-text text-muted">{{ Str::limit($partner->description, 120) }}</p>
                            @endif
                            
                            <!-- Location -->
                            @if($partner->city)
                                <div class="location mb-3">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    <span class="text-muted">{{ $partner->city }}</span>
                                </div>
                            @endif
                            
                            <!-- Services Preview -->
                            @if($partner->services && count($partner->services) > 0)
                                <div class="services-preview mb-3">
                                    <small class="text-muted font-weight-bold">Services:</small>
                                    <div class="mt-1">
                                        @foreach(array_slice($partner->services, 0, 2) as $service)
                                            <span class="badge badge-light text-dark mr-1 mb-1">{{ $service }}</span>
                                        @endforeach
                                        @if(count($partner->services) > 2)
                                            <span class="badge badge-info">+{{ count($partner->services) - 2 }} autres</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent border-0">
                            <div class="row no-gutters">
                                <div class="col-8">
                                    <a href="{{ route('front.partners.show', $partner) }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-info-circle"></i> Voir détails
                                    </a>
                                </div>
                                <div class="col-4 pl-2">
                                    <div class="btn-group btn-block">
                                        @if($partner->phone)
                                            <a href="tel:{{ $partner->phone }}" class="btn btn-success" title="Appeler">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                        @endif
                                        @if($partner->email)
                                            <a href="mailto:{{ $partner->email }}" class="btn btn-info" title="Email">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state text-center py-5">
                        <div class="card">
                            <div class="card-body py-5">
                                <i class="fas fa-search fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted">Aucun partenaire trouvé</h4>
                                <p class="text-muted">Aucun partenaire ne correspond à vos critères de recherche.</p>
                                <div class="mt-4">
                                    <a href="{{ route('front.partners.index') }}" class="btn btn-primary mr-2">
                                        <i class="fas fa-undo"></i> Réinitialiser les filtres
                                    </a>
                                    <button class="btn btn-outline-primary" onclick="window.scrollTo(0,0)">
                                        <i class="fas fa-filter"></i> Modifier les critères
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($partners->hasPages())
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <div class="pagination-wrapper">
                        {{ $partners->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('css')
<style>
/* Custom Styles for Professional Look */
.partner-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.partner-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.partner-image {
    height: 220px;
    object-fit: cover;
    transition: transform 0.3s ease;
    border-radius: 15px 15px 0 0;
}

.partner-card:hover .partner-image {
    transform: scale(1.05);
}

.partner-image-placeholder {
    border-radius: 15px 15px 0 0;
}

.favorite-btn {
    top: 10px;
    right: 10px;
    width: 40px;
    height: 40px;
    display: flex !important;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.9) !important;
    transition: all 0.3s ease;
}

.favorite-btn:hover {
    background: white !important;
    transform: scale(1.1);
}

.favorite-btn.favorited i {
    color: #dc3545 !important;
}

.category-card {
    transition: all 0.3s ease;
    cursor: pointer;
    border-radius: 15px !important;
}

.category-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.filter-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.rounded-4 {
    border-radius: 1rem !important;
}

.hero-categories .card {
    border: none;
    transition: all 0.3s ease;
}

.hero-categories .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
}

.rating {
    font-size: 14px;
}

.badge-lg {
    font-size: 0.875rem;
}

.services-preview .badge {
    font-size: 0.75rem;
}

.empty-state .card {
    border: none;
    background: #f8f9fa;
    border-radius: 15px;
}

.location {
    font-size: 14px;
}

.pagination-wrapper {
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle favorite functionality
    const favoriteButtons = document.querySelectorAll('.toggle-favorite');
    
    favoriteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const partnerId = this.dataset.partnerId;
            const heartIcon = this.querySelector('i');
            
            fetch(`/partenaires/${partnerId}/toggle-favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.action === 'added') {
                        heartIcon.classList.remove('far');
                        heartIcon.classList.add('fas');
                        this.classList.remove('btn-light');
                        this.classList.add('btn-danger');
                        this.title = 'Retirer des favoris';
                        
                        // Animation de succès
                        this.style.transform = 'scale(1.3)';
                        setTimeout(() => {
                            this.style.transform = 'scale(1)';
                        }, 200);
                    } else {
                        heartIcon.classList.remove('fas');
                        heartIcon.classList.add('far');
                        this.classList.remove('btn-danger');
                        this.classList.add('btn-light');
                        this.title = 'Ajouter aux favoris';
                    }
                    
                    // Show toast notification
                    showToast(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Une erreur est survenue.', 'error');
            });
        });
    });
    
    // Category hover effects
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.querySelector('i').style.transform = 'scale(1.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.querySelector('i').style.transform = 'scale(1)';
        });
    });
});

// Toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}
</script>
@endpush