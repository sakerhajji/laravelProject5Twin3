@extends('layouts.front')

@section('title', $partner->name)

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('front.partners.index') }}" class="btn btn-outline-primary me-3">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux partenaires
                </a>
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('front.partners.index') }}" class="text-decoration-none">Partenaires</a></li>
                            <li class="breadcrumb-item active">{{ $partner->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if($partner->logo)
                                    <img src="{{ asset('storage/' . $partner->logo) }}" class="img-fluid rounded shadow mb-3" alt="{{ $partner->name }}">
                                @else
                                    <div class="bg-gradient-primary rounded d-flex align-items-center justify-content-center mb-3 shadow" style="height: 200px;">
                                        @if($partner->type == 'doctor')
                                            <i class="fas fa-user-md fa-4x text-white"></i>
                                        @elseif($partner->type == 'gym')
                                            <i class="fas fa-dumbbell fa-4x text-white"></i>
                                        @elseif($partner->type == 'laboratory')
                                            <i class="fas fa-flask fa-4x text-white"></i>
                                        @elseif($partner->type == 'pharmacy')
                                            <i class="fas fa-pills fa-4x text-white"></i>
                                        @elseif($partner->type == 'nutritionist')
                                            <i class="fas fa-apple-alt fa-4x text-white"></i>
                                        @else
                                            <i class="fas fa-brain fa-4x text-white"></i>
                                        @endif
                                    </div>
                                @endif
                                
                                @auth
                                    <button class="btn btn-{{ $isFavorite ? '' : 'outline-' }}danger btn-block toggle-favorite" 
                                            data-partner-id="{{ $partner->id }}"
                                            title="{{ $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                                        <i class="fas fa-heart"></i> 
                                        {{ $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
                                    </button>
                                @endauth
                            </div>
                            <div class="col-md-8">
                                <h4>{{ $partner->name }}</h4>
                                <span class="badge badge-primary mb-2">{{ $partner->type_label }}</span>
                                
                                @if($partner->specialization)
                                    <p><strong>Spécialisation:</strong> {{ $partner->specialization }}</p>
                                @endif

                                @if($partner->description)
                                    <p><strong>Description:</strong></p>
                                    <p>{{ $partner->description }}</p>
                                @endif

                                @if($partner->rating > 0)
                                    <div class="mb-3">
                                        <strong>Note moyenne:</strong>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $partner->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        
                                       
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <strong>Note:</strong>
                                        <span class="text-muted">Pas encore d'avis</span>
                                    </div>
                                @endif
                                
                                @auth
                                    <div class="mb-3">
                                        <strong>Votre note:</strong>
                                        <div class="rating-stars" id="rating-stars" data-partner-id="{{ $partner->id }}">
                                            @php
                                                $userRating = $partner->ratings()->where('user_id', Auth::id())->first();
                                                $currentRating = $userRating ? $userRating->rating : 0;
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star rating-star {{ $i <= $currentRating ? 'text-warning' : 'text-muted' }}" 
                                                   data-rating="{{ $i }}" 
                                                   style="cursor: pointer; font-size: 1.5rem;"
                                                   title="Noter {{ $i }} étoile(s)"></i>
                                            @endfor
                                        </div>
                                        <small class="text-muted d-block mt-1">Cliquez sur les étoiles pour noter ce partenaire</small>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <a href="{{ route('login') }}">Connectez-vous</a> pour noter ce partenaire
                                        </small>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                @if($partner->services && count($partner->services) > 0)
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h4 class="mb-0 text-primary">
                                <i class="fas fa-concierge-bell me-2 text-primary"></i>Services proposés
                            </h4>
                        </div>
                        <div class="card-body bg-white">
                            <div class="row">
                                @foreach($partner->services as $service)
                                    <div class="col-lg-6 col-md-6 mb-3">
                                        <div class="service-item p-3 border rounded-3 h-100 shadow-sm bg-light">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span class="service-text fw-semibold">{{ trim($service) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if($partner->opening_hours && count($partner->opening_hours) > 0)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <x-opening-hours :partner="$partner" />
                        </div>
                    </div>
                @endif

                @if($similarPartners->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h4>Partenaires similaires</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($similarPartners as $similarPartner)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>{{ $similarPartner->name }}</h6>
                                                @if($similarPartner->city)
                                                    <small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $similarPartner->city }}</small>
                                                @endif
                                                <div class="mt-2">
                                                    <a href="{{ route('front.partners.show', $similarPartner) }}" class="btn btn-sm btn-primary">Voir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations de contact</h4>
                    </div>
                    <div class="card-body">
                        @if($partner->email)
                            <div class="mb-3">
                                <strong>Email:</strong><br>
                                <a href="mailto:{{ $partner->email }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-envelope"></i> {{ $partner->email }}
                                </a>
                            </div>
                        @endif

                        @if($partner->phone)
                            <div class="mb-3">
                                <strong>Téléphone:</strong><br>
                                <a href="tel:{{ $partner->phone }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-phone"></i> {{ $partner->phone }}
                                </a>
                            </div>
                        @endif

                        @if($partner->website)
                            <div class="mb-3">
                                <strong>Site web:</strong><br>
                                <a href="{{ $partner->website }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-globe"></i> Visiter
                                </a>
                            </div>
                        @endif

                        @if($partner->address)
                            <div class="mb-3">
                                <strong>Adresse:</strong><br>
                                {{ $partner->address }}
                                @if($partner->city)
                                    <br>{{ $partner->city }}
                                    @if($partner->postal_code)
                                        {{ $partner->postal_code }}
                                    @endif
                                @endif
                            </div>
                        @endif

                        @if($partner->contact_person)
                            <div class="mb-3">
                                <strong>Personne de contact:</strong><br>
                                {{ $partner->contact_person }}
                            </div>
                        @endif
                    </div>
                </div>

                @if($partner->latitude && $partner->longitude)
                    <div class="card">
                        <div class="card-header">
                            <h4>Localisation</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <strong>Coordonnées GPS:</strong><br>
                                <small class="text-muted">{{ $partner->latitude }}, {{ $partner->longitude }}</small>
                            </div>
                            
                            <div id="map" style="height: 200px; width: 100%;" class="bg-light rounded d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="fas fa-map-marked-alt fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Carte disponible</p>
                                    <small class="text-muted">{{ $partner->latitude }}, {{ $partner->longitude }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
.service-item {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef !important;
}

.service-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,123,255,0.15) !important;
    border-color: #007bff !important;
}

.service-text {
    font-size: 0.95rem;
    color: #2c3e50;
}

.card-header {
    border-bottom: 3px solid #007bff !important;
}

.card-header h4 {
    font-weight: 600;
    font-size: 1.1rem;
}

.rating-star {
    transition: all 0.2s ease;
}

.rating-star:hover {
    transform: scale(1.2);
}

.rating-stars {
    display: inline-block;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle favorite functionality
    const favoriteButton = document.querySelector('.toggle-favorite');
    
    if (favoriteButton) {
        favoriteButton.addEventListener('click', function() {
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
                        this.innerHTML = '<i class="fas fa-heart"></i> Retirer des favoris';
                        this.title = 'Retirer des favoris';
                    } else {
                        this.classList.remove('btn-danger');
                        this.classList.add('btn-outline-danger');
                        this.innerHTML = '<i class="fas fa-heart"></i> Ajouter aux favoris';
                        this.title = 'Ajouter aux favoris';
                    }
                    
                    // Show Bootstrap 5 toast notification
                    showToast(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Une erreur est survenue.', 'error');
            });
        });
    }
    
    // Rating functionality
    const ratingStars = document.querySelectorAll('.rating-star');
    const ratingContainer = document.getElementById('rating-stars');
    
    if (ratingStars.length > 0 && ratingContainer) {
        const partnerId = ratingContainer.dataset.partnerId;
        
        // Hover effect
        ratingStars.forEach((star, index) => {
            star.addEventListener('mouseenter', function() {
                highlightStars(index + 1);
            });
            
            star.addEventListener('mouseleave', function() {
                const currentRating = getCurrentRating();
                highlightStars(currentRating);
            });
            
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                submitRating(partnerId, rating);
            });
        });
        
        function highlightStars(count) {
            ratingStars.forEach((star, index) => {
                if (index < count) {
                    star.classList.remove('text-muted');
                    star.classList.add('text-warning');
                } else {
                    star.classList.remove('text-warning');
                    star.classList.add('text-muted');
                }
            });
        }
        
        function getCurrentRating() {
            let rating = 0;
            ratingStars.forEach((star) => {
                if (star.classList.contains('text-warning')) {
                    rating++;
                }
            });
            return rating;
        }
        
        function submitRating(partnerId, rating) {
            fetch(`/partenaires/${partnerId}/rating`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ rating: parseInt(rating) })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    highlightStars(data.user_rating);
                    
                    // Update average rating display
                    updateAverageRating(data.average);
                } else {
                    showToast(data.message || 'Une erreur est survenue.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Une erreur est survenue lors de l\'envoi de votre note.', 'error');
            });
        }
        
        function updateAverageRating(average) {
            // Find the average rating display and update it
            const ratingDisplay = document.querySelector('.mb-3 strong');
            if (ratingDisplay && ratingDisplay.textContent === 'Note moyenne:') {
                const parent = ratingDisplay.parentElement;
                const stars = parent.querySelectorAll('i.fa-star:not(.rating-star)');
                stars.forEach((star, index) => {
                    if (index < Math.round(average)) {
                        star.classList.remove('text-muted');
                        star.classList.add('text-warning');
                    } else {
                        star.classList.remove('text-warning');
                        star.classList.add('text-muted');
                    }
                });
                
                const ratingText = parent.querySelector('span');
                if (ratingText) {
                    ratingText.textContent = `${average}/5`;
                }
            }
        }
    }
});

// Bootstrap 5 Toast notification function
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