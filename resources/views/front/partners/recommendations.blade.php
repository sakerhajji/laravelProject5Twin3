@extends('layouts.front')

@section('title', 'Recommandations personnalisées')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h2 mb-2">
                        <i class="fas fa-magic text-primary me-2"></i>
                        Recommandations pour vous
                    </h1>
                    <p class="text-muted mb-0">
                        Découvrez les partenaires qui correspondent le mieux à vos besoins
                    </p>
                </div>
                <a href="{{ route('front.partners.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-th-large me-2"></i>Voir tous les partenaires
                </a>
            </div>
        </div>
    </div>

    @if($stats)
    <!-- User Stats -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg profile-stats-card">
                <div class="card-body p-4">
                    <h5 class="mb-3 text-white">
                        <i class="fas fa-chart-line me-2"></i>Votre profil
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex flex-column text-white">
                                <small class="mb-1" style="opacity: 0.85;">Favoris</small>
                                <strong class="h4 mb-0">{{ $stats['total_favorites'] }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex flex-column text-white">
                                <small class="mb-1" style="opacity: 0.85;">Avis donnés</small>
                                <strong class="h4 mb-0">{{ $stats['total_ratings'] }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex flex-column text-white">
                                <small class="mb-1" style="opacity: 0.85;">Note moyenne</small>
                                <strong class="h4 mb-0">{{ $stats['average_rating_given'] }}/5</strong>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex flex-column text-white">
                                <small class="mb-1" style="opacity: 0.85;">Localisation</small>
                                <strong class="h5 mb-0">{{ $stats['preferred_city'] ?? 'Non définie' }}</strong>
                            </div>
                        </div>
                    </div>
                    
                    @if($stats['favorite_types']->isNotEmpty())
                    <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.3);">
                        <small class="d-block mb-2 text-white" style="opacity: 0.85;">Vos types préférés :</small>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($stats['favorite_types'] as $type => $count)
                                <span class="badge bg-white text-primary px-3 py-2" style="font-weight: 600;">
                                    {{ $type }} ({{ $count }})
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recommendations -->
    @if($recommendations->count() > 0)
    <div class="row mb-3">
        <div class="col-12">
            <h4 class="mb-3">
                <i class="fas fa-star text-warning me-2"></i>
                Nos recommandations ({{ $recommendations->count() }})
            </h4>
        </div>
    </div>

    <div class="row g-4 mb-5">
        @foreach($recommendations as $partner)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow position-relative">
                <!-- Recommendation Badge -->
                <div class="position-absolute top-0 start-0 m-3" style="z-index: 10;">
                    <span class="badge bg-primary px-3 py-2">
                        <i class="fas fa-award me-1"></i>
                        Recommandé
                    </span>
                </div>

                <!-- Favorite Button -->
                <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                    <button class="btn btn-sm {{ in_array($partner->id, $userFavorites) ? 'btn-danger' : 'btn-outline-danger' }} rounded-circle toggle-favorite-btn"
                            data-partner-id="{{ $partner->id }}"
                            title="{{ in_array($partner->id, $userFavorites) ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <!-- Partner Logo -->
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    @if($partner->logo)
                        <img src="{{ asset('storage/' . $partner->logo) }}" 
                             alt="{{ $partner->name }}" 
                             class="img-fluid p-3"
                             style="max-height: 180px; max-width: 100%; object-fit: contain;">
                    @else
                        <div class="text-center">
                            <i class="fas fa-building fa-4x text-muted opacity-25"></i>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <!-- Partner Name -->
                    <h5 class="card-title mb-2">
                        <a href="{{ route('front.partners.show', $partner) }}" class="text-decoration-none text-dark">
                            {{ $partner->name }}
                        </a>
                    </h5>

                    <!-- Partner Type -->
                    <p class="text-muted small mb-2">
                        <i class="fas fa-tag me-1"></i>
                        {{ \App\Models\Partner::getTypes()[$partner->type] ?? $partner->type }}
                    </p>

                    <!-- Rating -->
                    <div class="mb-2">
                        @php
                            $rating = $partner->rating;
                            $fullStars = floor($rating);
                            $halfStar = ($rating - $fullStars) >= 0.5;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            $ratingsCount = $partner->ratings()->count();
                        @endphp
                        
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                @for($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star text-warning"></i>
                                @endfor
                                @if($halfStar)
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                @endif
                                @for($i = 0; $i < $emptyStars; $i++)
                                    <i class="far fa-star text-warning"></i>
                                @endfor
                            </div>
                            <span class="text-muted small">
                                {{ number_format($rating, 1) }}/5 ({{ $ratingsCount }} avis)
                            </span>
                        </div>
                    </div>

                    <!-- Recommendation Reason -->
                    <div class="alert alert-info py-2 px-3 mb-3" style="font-size: 0.85rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Pourquoi ?</strong> {{ $partner->recommendation_reason }}
                    </div>

                    <!-- Location -->
                    @if($partner->city)
                    <p class="text-muted small mb-2">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        {{ $partner->city }}
                    </p>
                    @endif

                    <!-- Description -->
                    @if($partner->description)
                    <p class="card-text text-muted small mb-3">
                        {{ Str::limit($partner->description, 100) }}
                    </p>
                    @endif

                    <!-- Services -->
                    @if(is_array($partner->services) && count($partner->services) > 0)
                    <div class="mb-3">
                        <div class="d-flex flex-wrap gap-1">
                            @foreach(array_slice($partner->services, 0, 3) as $service)
                                <span class="badge bg-light text-dark border">{{ $service }}</span>
                            @endforeach
                            @if(count($partner->services) > 3)
                                <span class="badge bg-light text-dark border">+{{ count($partner->services) - 3 }}</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-white border-top-0">
                    <a href="{{ route('front.partners.show', $partner) }}" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-eye me-2"></i>Voir les détails
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Call to Action -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center py-5">
                    <i class="fas fa-lightbulb fa-3x text-warning mb-3"></i>
                    <h4 class="mb-3">Vous cherchez quelque chose de spécifique ?</h4>
                    <p class="text-muted mb-4">
                        Utilisez notre recherche avancée pour trouver exactement ce dont vous avez besoin
                    </p>
                    <a href="{{ route('front.partners.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-search me-2"></i>Recherche avancée
                    </a>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Empty State -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-4 opacity-25"></i>
                    <h4 class="mb-3">Aucune recommandation disponible</h4>
                    <p class="text-muted mb-4">
                        Commencez à explorer nos partenaires et ajoutez-en à vos favoris pour obtenir des recommandations personnalisées.
                    </p>
                    <a href="{{ route('front.partners.index') }}" class="btn btn-primary">
                        <i class="fas fa-compass me-2"></i>Explorer les partenaires
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('css')
<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.profile-stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    position: relative;
    overflow: hidden;
}

.profile-stats-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.profile-stats-card .card-body {
    position: relative;
    z-index: 1;
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.toggle-favorite-btn {
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.toggle-favorite-btn:hover {
    transform: scale(1.1);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle favorite functionality
    const favoriteButtons = document.querySelectorAll('.toggle-favorite-btn');
    
    favoriteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const partnerId = this.dataset.partnerId;
            
            fetch(`/partenaires/${partnerId}/toggle-favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.action === 'added') {
                        this.classList.remove('btn-outline-danger');
                        this.classList.add('btn-danger');
                        this.title = 'Retirer des favoris';
                    } else {
                        this.classList.remove('btn-danger');
                        this.classList.add('btn-outline-danger');
                        this.title = 'Ajouter aux favoris';
                    }
                    
                    showToast(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Une erreur est survenue.', 'error');
            });
        });
    });
});

function showToast(message, type = 'info') {
    const toastContainer = document.querySelector('.toast-container') || createToastContainer();
    
    const toastElement = document.createElement('div');
    toastElement.className = `toast align-items-center text-bg-${type === 'success' ? 'success' : 'danger'} border-0`;
    toastElement.setAttribute('role', 'alert');
    toastElement.setAttribute('aria-live', 'assertive');
    toastElement.setAttribute('aria-atomic', 'true');
    
    toastElement.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    toastContainer.appendChild(toastElement);
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    container.style.zIndex = '1055';
    document.body.appendChild(container);
    return container;
}
</script>
@endpush
