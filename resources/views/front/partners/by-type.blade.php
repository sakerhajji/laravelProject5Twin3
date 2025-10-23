@extends('layouts.front')

@section('title', $typeName)

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('front.partners.index') }}" class="btn btn-outline-primary me-3">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <div>
                    <h1 class="display-6 fw-bold text-primary mb-1">{{ $typeName }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('front.partners.index') }}" class="text-decoration-none">Partenaires</a></li>
                            <li class="breadcrumb-item active">{{ $typeName }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
        @if($partners->count() > 0)
            <div class="row mb-3">
                <div class="col-12">
                    <p class="text-muted">{{ $partners->total() }} {{ $typeName }}(s) trouvé(s)</p>
                </div>
            </div>

            <div class="row">
                @foreach($partners as $partner)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm partner-card">
                            @if($partner->logo)
                                <img src="{{ asset('storage/' . $partner->logo) }}" class="card-img-top" style="height: 220px; object-fit: cover; border-radius: 15px 15px 0 0;">
                            @else
                                <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 220px; border-radius: 15px 15px 0 0;">
                                    @if($type == 'doctor')
                                        <i class="fas fa-user-md fa-4x text-white opacity-75"></i>
                                    @elseif($type == 'gym')
                                        <i class="fas fa-dumbbell fa-4x text-white opacity-75"></i>
                                    @elseif($type == 'laboratory')
                                        <i class="fas fa-flask fa-4x text-white opacity-75"></i>
                                    @elseif($type == 'pharmacy')
                                        <i class="fas fa-pills fa-4x text-white opacity-75"></i>
                                    @elseif($type == 'nutritionist')
                                        <i class="fas fa-apple-alt fa-4x text-white opacity-75"></i>
                                    @else
                                        <i class="fas fa-brain fa-4x text-white opacity-75"></i>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $partner->name }}</h5>
                                    @auth
                                        <button class="btn btn-sm btn-outline-danger toggle-favorite" 
                                                data-partner-id="{{ $partner->id }}"
                                                title="Ajouter aux favoris">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    @endauth
                                </div>
                                
                                @if($partner->specialization)
                                    <p class="text-primary small mb-2"><strong>{{ $partner->specialization }}</strong></p>
                                @endif
                                
                                @if($partner->description)
                                    <p class="card-text small">{{ Str::limit($partner->description, 120) }}</p>
                                @endif
                                
                                <div class="small text-muted mb-3">
                                    @if($partner->city)
                                        <i class="fas fa-map-marker-alt"></i> {{ $partner->city }}
                                    @endif
                                    
                                    @if($partner->rating > 0)
                                        <br><div class="mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $partner->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ml-1">{{ number_format($partner->rating, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($partner->services && count($partner->services) > 0)
                                    <div class="mb-3">
                                        @foreach(array_slice($partner->services, 0, 3) as $service)
                                            <span class="badge badge-outline-info me-1 mb-1">{{ $service }}</span>
                                        @endforeach
                                        @if(count($partner->services) > 3)
                                            <span class="badge badge-secondary">+{{ count($partner->services) - 3 }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-footer">
                                <div class="btn-group btn-block" role="group">
                                    <a href="{{ route('front.partners.show', $partner) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Détails
                                    </a>
                                    @if($partner->phone)
                                        <a href="tel:{{ $partner->phone }}" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                    @endif
                                    @if($partner->email)
                                        <a href="mailto:{{ $partner->email }}" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($partners->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $partners->links() }}
                </div>
            @endif
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-{{ $type == 'doctor' ? 'user-md' : ($type == 'gym' ? 'dumbbell' : ($type == 'laboratory' ? 'flask' : ($type == 'pharmacy' ? 'pills' : ($type == 'nutritionist' ? 'apple-alt' : 'brain')))) }} fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun {{ strtolower($typeName) }} trouvé</h5>
                            <p class="text-muted">Il n'y a actuellement aucun {{ strtolower($typeName) }} disponible.</p>
                            <a href="{{ route('front.partners.index') }}" class="btn btn-primary">Voir tous les partenaires</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle favorite functionality with Bootstrap 5 Toast
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
                        heartIcon.classList.add('fas', 'text-danger');
                    } else {
                        heartIcon.classList.remove('fas', 'text-danger');
                        heartIcon.classList.add('far');
                    }
                    
                    // Show Bootstrap 5 toast
                    showToast(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue', 'error');
            });
        });
    });
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
                        this.classList.remove('btn-outline-danger');
                        this.classList.add('btn-danger');
                        this.title = 'Retirer des favoris';
                    } else {
                        heartIcon.classList.remove('fas');
                        heartIcon.classList.add('far');
                        this.classList.remove('btn-danger');
                        this.classList.add('btn-outline-danger');
                        this.title = 'Ajouter aux favoris';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue.');
            });
        });
    });
});

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    container.style.zIndex = '1055';
    document.body.appendChild(container);
    return container;
}
</script>
@endpush