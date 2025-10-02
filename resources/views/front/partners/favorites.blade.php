@extends('layouts.front')

@section('title', 'Mes Partenaires Favoris')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
           
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('front.partners.index') }}" class="text-decoration-none">Partenaires</a></li>
                    <li class="breadcrumb-item active">Mes Favoris</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="py-5 bg-primary text-white rounded-4 p-5 shadow-lg">
                <div class="text-center">
                    <h2 class="display-6 fw-bold mb-3">
                        <i class="fas fa-heart-circle-plus me-3"></i>Vos Partenaires du Cœur
                    </h2>
                    <p class="lead mb-0">Accédez rapidement à vos professionnels de santé préférés</p>
                </div>
            </div>
        </div>
    </div>

    @if($favoritePartners->count() > 0)
        <!-- Statistics Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="stat-item">
                                    <i class="fas fa-heart text-danger fa-2x mb-2"></i>
                                    <h4 class="fw-bold text-primary">{{ $favoritePartners->total() }}</h4>
                                    <p class="text-muted mb-0">Partenaire{{ $favoritePartners->total() > 1 ? 's' : '' }} favori{{ $favoritePartners->total() > 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-item">
                                    <i class="fas fa-map-marker-alt text-info fa-2x mb-2"></i>
                                    <h4 class="fw-bold text-primary">{{ $favoritePartners->unique('city')->count() }}</h4>
                                    <p class="text-muted mb-0">Ville{{ $favoritePartners->unique('city')->count() > 1 ? 's' : '' }} couverte{{ $favoritePartners->unique('city')->count() > 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-item">
                                    <i class="fas fa-tags text-success fa-2x mb-2"></i>
                                    <h4 class="fw-bold text-primary">{{ $favoritePartners->unique('type')->count() }}</h4>
                                    <p class="text-muted mb-0">Type{{ $favoritePartners->unique('type')->count() > 1 ? 's' : '' }} de partenaire{{ $favoritePartners->unique('type')->count() > 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-bolt"></i> Actions Rapides</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('front.partners.index') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-plus-circle me-2"></i>Découvrir d'autres partenaires
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="button" id="clear-all-favorites" class="btn btn-outline-danger btn-lg w-100">
                                    <i class="fas fa-heart-broken me-2"></i>Vider mes favoris
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Favorites Grid -->
        <div id="favorites-grid" class="row">
            @php
                $userFavorites = $favoritePartners->pluck('id')->toArray();
            @endphp
            @include('front.partners.partials.partners-grid', ['partners' => $favoritePartners, 'userFavorites' => $userFavorites])
        </div>

        <!-- Pagination -->
        @if($favoritePartners->hasPages())
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <div class="pagination-wrapper">
                        {{ $favoritePartners->links() }}
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="empty-state text-center py-5">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body py-5">
                            <div class="empty-icon mb-4">
                                <i class="fas fa-heart-broken fa-6x text-muted opacity-50"></i>
                                <div class="floating-hearts">
                                    <i class="fas fa-heart text-danger heart-1"></i>
                                    <i class="fas fa-heart text-danger heart-2"></i>
                                    <i class="fas fa-heart text-danger heart-3"></i>
                                </div>
                            </div>
                            <h3 class="text-muted mb-3">Aucun partenaire en favori</h3>
                            <p class="text-muted lead mb-4">Commencez à explorer notre réseau de professionnels de santé pour trouver vos partenaires préférés</p>
                            <div class="mt-4">
                                <a href="{{ route('front.partners.index') }}" class="btn btn-primary btn-lg me-3">
                                    <i class="fas fa-search me-2"></i>Découvrir les partenaires
                                </a>
                                <a href="{{ url('/') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection

@push('css')
<style>
/* Custom Styles for Professional Look - Same as index.blade.php */
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
    top: 15px;
    right: 15px;
    width: 45px;
    height: 45px;
    display: flex !important;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.95) !important;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
}

.favorite-btn:hover {
    transform: scale(1.15);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
}

.favorite-btn.favorited {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%) !important;
    border-color: #ff6b6b !important;
    animation: heartBeat 0.6s ease-in-out;
}

.favorite-btn.favorited:hover {
    background: linear-gradient(135deg, #ff5252 0%, #d32f2f 100%) !important;
    transform: scale(1.2);
}

.favorite-btn:not(.favorited):hover {
    background: rgba(255,255,255,1) !important;
    border-color: #ff6b6b !important;
}

.favorite-btn i {
    transition: all 0.3s ease;
}

.favorite-btn.favorited i {
    color: white !important;
    text-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

.favorite-btn:not(.favorited) i {
    color: #ff6b6b !important;
}

.favorite-btn:not(.favorited):hover i {
    color: #ff5252 !important;
    transform: scale(1.1);
}

@keyframes heartBeat {
    0% { transform: scale(1); }
    25% { transform: scale(1.3); }
    50% { transform: scale(1.1); }
    75% { transform: scale(1.25); }
    100% { transform: scale(1.15); }
}

.partner-card {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.rounded-4 {
    border-radius: 1rem !important;
}

.pagination-wrapper {
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* Statistics Section */
.stat-item {
    padding: 20px;
    border-radius: 10px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Empty State Animations */
.empty-icon {
    position: relative;
    display: inline-block;
}

.floating-hearts {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.floating-hearts i {
    position: absolute;
    font-size: 1.5rem;
    opacity: 0;
    animation: floatingHeart 3s infinite;
}

.heart-1 {
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.heart-2 {
    top: 60%;
    right: 15%;
    animation-delay: 1s;
}

.heart-3 {
    bottom: 30%;
    left: 70%;
    animation-delay: 2s;
}

@keyframes floatingHeart {
    0% {
        opacity: 0;
        transform: scale(0.5) translateY(20px);
    }
    25% {
        opacity: 1;
        transform: scale(1) translateY(0px);
    }
    75% {
        opacity: 1;
        transform: scale(1.2) translateY(-10px);
    }
    100% {
        opacity: 0;
        transform: scale(0.5) translateY(-30px);
    }
}

/* Button animations */
#clear-all-favorites {
    transition: all 0.3s ease;
}

#clear-all-favorites:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

/* Removal animation */
.removing-favorite {
    animation: slideOutRight 0.5s ease-in forwards;
    pointer-events: none;
}

@keyframes slideOutRight {
    0% {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
    50% {
        opacity: 0.5;
        transform: translateX(30px) scale(0.9);
    }
    100% {
        opacity: 0;
        transform: translateX(100px) scale(0.8);
        height: 0;
        margin: 0;
        padding: 0;
    }
}

/* Loading overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(5px);
}

.loading-overlay .spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize favorite buttons
    initializeFavoriteButtons();
    
    // Clear all favorites button
    const clearAllBtn = document.getElementById('clear-all-favorites');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', handleClearAllFavorites);
    }
    
    function initializeFavoriteButtons() {
        document.querySelectorAll('.toggle-favorite').forEach(button => {
            button.replaceWith(button.cloneNode(true));
        });
        
        document.querySelectorAll('.toggle-favorite').forEach(button => {
            button.addEventListener('click', handleFavoriteClick);
        });
    }
    
    function handleFavoriteClick(event) {
        event.preventDefault();
        const button = this;
        const partnerId = button.getAttribute('data-partner-id');
        const partnerCard = button.closest('.col-xl-4, .col-lg-6, .col-md-6');
        const heartIcon = button.querySelector('i');
        
        // Disable button during request
        button.disabled = true;
        
        // Animation effect
        button.style.transform = 'scale(0.9)';
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 150);
        
        fetch(`{{ url('/partenaires') }}/${partnerId}/toggle-favorite`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show notification
                showFavoriteToast('Partenaire retiré de vos favoris avec succès !', 'removed');
                
                // Animate card removal
                if (partnerCard) {
                    partnerCard.classList.add('removing-favorite');
                    
                    // Remove card after animation
                    setTimeout(() => {
                        partnerCard.remove();
                        updateFavoritesCount();
                        
                        // Check if no more favorites
                        const remainingCards = document.querySelectorAll('#favorites-grid .col-xl-4, #favorites-grid .col-lg-6, #favorites-grid .col-md-6');
                        if (remainingCards.length === 0) {
                            showLoadingOverlay();
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    }, 500);
                }
            } else {
                showToast(data.message || 'Une erreur est survenue.', 'error');
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Une erreur est survenue lors de la suppression.', 'error');
            button.disabled = false;
        });
    }
    
    function handleClearAllFavorites(event) {
        event.preventDefault();
        
        const favoriteButtons = document.querySelectorAll('.toggle-favorite');
        if (favoriteButtons.length === 0) {
            showToast('Aucun favori à supprimer.', 'warning');
            return;
        }
        
        if (confirm(`Êtes-vous sûr de vouloir supprimer tous vos ${favoriteButtons.length} favoris ?`)) {
            showLoadingOverlay();
            
            const promises = Array.from(favoriteButtons).map(button => {
                const partnerId = button.getAttribute('data-partner-id');
                
                return fetch(`{{ url('/partenaires') }}/${partnerId}/toggle-favorite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
            });
            
            Promise.all(promises)
                .then(responses => {
                    return Promise.all(responses.map(response => response.json()));
                })
                .then(results => {
                    const successCount = results.filter(result => result.success).length;
                    
                    hideLoadingOverlay();
                    
                    if (successCount > 0) {
                        showFavoriteToast(`${successCount} partenaire(s) retiré(s) de vos favoris !`, 'removed');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showToast('Aucun favori n\'a pu être supprimé.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideLoadingOverlay();
                    showToast('Une erreur est survenue lors de la suppression.', 'error');
                });
        }
    }
    
    function updateFavoritesCount() {
        const remainingCards = document.querySelectorAll('#favorites-grid .col-xl-4, #favorites-grid .col-lg-6, #favorites-grid .col-md-6');
        const countElements = document.querySelectorAll('.stat-item h4');
        
        if (countElements.length > 0) {
            countElements[0].textContent = remainingCards.length;
        }
    }
    
    function showLoadingOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.id = 'loading-overlay';
        overlay.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <h5 class="text-primary">Suppression en cours...</h5>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    
    function hideLoadingOverlay() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }
});

// Toast notification function - same as index.blade.php
function showToast(message, type = 'info', icon = null) {
    let iconClass, alertClass;
    
    switch (type) {
        case 'success':
            iconClass = icon || 'check-circle';
            alertClass = 'alert-success';
            break;
        case 'error':
            iconClass = icon || 'exclamation-circle';
            alertClass = 'alert-danger';
            break;
        case 'warning':
            iconClass = icon || 'exclamation-triangle';
            alertClass = 'alert-warning';
            break;
        default:
            iconClass = icon || 'info-circle';
            alertClass = 'alert-info';
    }
    
    const toast = document.createElement('div');
    toast.className = `alert ${alertClass} alert-dismissible fade show position-fixed shadow-lg`;
    toast.style.cssText = `
        top: 30px; 
        right: 30px; 
        z-index: 9999; 
        min-width: 350px; 
        border: none; 
        border-radius: 15px;
        backdrop-filter: blur(10px);
        animation: slideInRight 0.5s ease-out;
    `;
    
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${iconClass} me-3" style="font-size: 20px;"></i>
            <div class="flex-grow-1">
                <strong>${message}</strong>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.animation = 'slideOutRight 0.5s ease-in forwards';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 500);
        }
    }, 4000);
}

// Fonction spécialisée pour les notifications de favoris
function showFavoriteToast(message, action) {
    let iconClass, alertClass, bgGradient;
    
    if (action === 'added') {
        iconClass = 'heart';
        alertClass = 'alert-success';
        bgGradient = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
    } else {
        iconClass = 'heart-broken';
        alertClass = 'alert-info';
        bgGradient = 'linear-gradient(135deg, #17a2b8 0%, #6610f2 100%)';
    }
    
    const toast = document.createElement('div');
    toast.className = `alert ${alertClass} alert-dismissible fade show position-fixed shadow-lg`;
    toast.style.cssText = `
        top: 30px; 
        right: 30px; 
        z-index: 9999; 
        min-width: 350px; 
        border: none; 
        border-radius: 15px;
        backdrop-filter: blur(10px);
        animation: slideInRight 0.5s ease-out;
        background: ${bgGradient} !important;
        border-left: 5px solid ${action === 'added' ? '#155724' : '#0c5460'};
        color: white !important;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    `;
    
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="favorite-icon me-3" style="position: relative;">
                <i class="fas fa-${iconClass}" style="font-size: 24px; color: white; animation: ${action === 'added' ? 'heartPulse' : 'fadeInOut'} 1s ease-in-out;"></i>
                ${action === 'added' ? '<div class="sparkles"></div>' : ''}
            </div>
            <div class="flex-grow-1">
                <strong>${message}</strong>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.animation = 'slideOutRight 0.5s ease-in forwards';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 500);
        }
    }, 4000);
}

// CSS animations
const styles = document.createElement('style');
styles.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    @keyframes heartPulse {
        0% { transform: scale(1); }
        25% { transform: scale(1.3); }
        50% { transform: scale(1.1); }
        75% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    @keyframes fadeInOut {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .sparkles {
        position: absolute;
        top: -10px;
        left: -10px;
        width: 40px;
        height: 40px;
        pointer-events: none;
    }
    
    .sparkles::before,
    .sparkles::after {
        content: '✨';
        position: absolute;
        font-size: 16px;
        animation: sparkle 1.5s ease-in-out infinite;
    }
    
    .sparkles::before {
        top: 0;
        left: 0;
        animation-delay: 0.3s;
    }
    
    .sparkles::after {
        bottom: 0;
        right: 0;
        animation-delay: 0.8s;
    }
    
    @keyframes sparkle {
        0%, 100% { opacity: 0; transform: scale(0.5); }
        50% { opacity: 1; transform: scale(1); }
    }
`;
document.head.appendChild(styles);
</script>
@endpush