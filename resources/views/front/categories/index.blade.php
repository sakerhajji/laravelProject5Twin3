@extends('layouts.front')

@section('title', 'Nos Catégories de Santé')

@section('content')
<style>
.category-img {
    height: 180px;       /* slightly smaller */
    object-fit: cover;   /* crop to fit */
    width: 100%;         /* full card width */
    border-top-left-radius: .25rem; 
    border-top-right-radius: .25rem;
}
</style>

<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Accueil</a></li>
                    <li class="breadcrumb-item active">Catégories de Santé</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="py-5 bg-primary text-white rounded-4 p-5 shadow-lg">
                <div class="text-center">
                    <h2 class="display-6 fw-bold mb-3">Découvrez nos catégories de santé</h2>
                    <p class="lead mb-0">Explorez nos catégories et trouvez le service qu'il vous faut</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-filter"></i> Filtres de Recherche Automatique</h4>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3">
                            <label for="category-search" class="form-label fw-bold">Recherche instantanée</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="category-search" name="search" class="form-control" 
                                       placeholder="Titre ou description...">
                                <button type="button" id="clear-search" class="btn btn-outline-secondary" style="display: none;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold d-block">&nbsp;</label>
                            <button type="button" id="reset-filters" class="btn btn-secondary w-100">
                                <i class="fas fa-undo me-2"></i>Réinitialiser
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Categories Found -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info" id="total-categories">
                <i class="fas fa-info-circle"></i>
                <strong>{{ $categories->count() }}</strong> catégorie(s) trouvée(s)
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="row g-4" id="categories-grid">
        @foreach($categories as $category)
            <div class="col-lg-3 col-md-4 col-sm-6 category-card" 
                 data-title="{{ strtolower($category->title) }}" 
                 data-description="{{ strtolower($category->description) }}">
                <div class="card h-100 shadow-sm">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" 
                             class="card-img-top category-img" 
                             alt="{{ $category->title }}">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $category->title }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($category->description, 100) }}</p>
                        <a href="{{ route('front.activities.byCategory', $category->id) }}" class="btn btn-primary mt-2">
    Afficher Activités
</a>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $categories->appends(request()->all())->links() }}
            </div>
        </div>
    @endif
</div>

<script>
const searchInput = document.getElementById('category-search');
const clearBtn = document.getElementById('clear-search');
const resetBtn = document.getElementById('reset-filters');

function filterCategories() {
    const query = searchInput.value.toLowerCase();
    const cards = document.querySelectorAll('.category-card');
    let count = 0;

    cards.forEach(card => {
        const title = card.dataset.title;
        const desc = card.dataset.description;

        if (title.includes(query) || desc.includes(query)) {
            card.style.display = 'block';
            count++;
        } else {
            card.style.display = 'none';
        }
    });

    clearBtn.style.display = query ? 'inline-flex' : 'none';

    // Update total count dynamically
    const totalDiv = document.getElementById('total-categories');
    if (totalDiv) {
        totalDiv.innerHTML = `<i class="fas fa-info-circle"></i> <strong>${count}</strong> catégorie(s) trouvée(s)`;
    }
}

searchInput.addEventListener('input', filterCategories);

clearBtn.addEventListener('click', () => {
    searchInput.value = '';
    filterCategories();
});

resetBtn.addEventListener('click', () => {
    searchInput.value = '';
    filterCategories();
});
</script>
@endsection


@push('css')
<style>
/* Custom Styles for Professional Look */
.category-card {
    min-height: 120px;
    transition: all 0.3s ease;
    border-radius: 12px;
    text-decoration: none;
}

.category-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    border-color: var(--bs-primary);
}

.category-icon {
    transition: transform 0.3s ease;
}

.category-card:hover .category-icon {
    transform: scale(1.1);
}

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

.category-card {
    transition: all 0.3s ease;
    cursor: pointer;
    border-radius: 15px !important;
}

.category-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.auto-filter {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}

.auto-filter:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    transform: translateY(-1px);
}

.auto-filter:hover {
    border-color: #007bff;
}

#loading-indicator {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 10px;
    padding: 10px;
    margin-top: 10px;
}

#active-filters .badge {
    font-size: 0.8rem;
    padding: 8px 12px;
    border-radius: 20px;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    color: white;
    border: none;
    box-shadow: 0 2px 5px rgba(0, 123, 255, 0.3);
}

#clear-search {
    border-left: none;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

#clear-search:hover {
    background: #e9ecef;
    color: #dc3545;
}

#reset-filters {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    border: none;
    transition: all 0.3s ease;
}

#reset-filters:hover {
    background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

#active-filters {
    background: rgba(0, 123, 255, 0.05);
    border-radius: 10px;
    padding: 10px 15px;
    border-left: 4px solid #007bff;
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

.rounded-4 {
    border-radius: 1rem !important;
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
    let searchTimeout;
    const searchDelay = 500;
    
    // Get filter elements
    const searchInput = document.getElementById('partner-search');
    const typeSelect = document.getElementById('partner-type');
    const citySelect = document.getElementById('partner-city');
    const clearSearchBtn = document.getElementById('clear-search');
    const resetFiltersBtn = document.getElementById('reset-filters');
    
    // Results and loading elements
    const partnersGrid = document.getElementById('partners-grid');
    const loadingIndicator = document.getElementById('loading-indicator');
    const activeFilters = document.getElementById('active-filters');
    const activeFiltersList = document.getElementById('active-filters-list');
    
    // Perform AJAX search function
    function performSearch() {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            if (loadingIndicator) {
                loadingIndicator.style.display = 'block';
            }
            
            const params = new URLSearchParams();
            
            if (searchInput && searchInput.value.trim()) {
                params.append('search', searchInput.value.trim());
            }
            
            if (typeSelect && typeSelect.value && typeSelect.value !== '') {
                params.append('type', typeSelect.value);
            }
            
            if (citySelect && citySelect.value && citySelect.value !== '') {
                params.append('city', citySelect.value);
            }
            
            fetch(`{{ route('front.partners.search') }}?${params}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (partnersGrid && data.html) {
                    partnersGrid.innerHTML = data.html;
                    initializeFavoriteButtons();
                }
                
                updateActiveFilters(data.filters);
                
                if (params.toString()) {
                    const newUrl = `${window.location.pathname}?${params}`;
                    window.history.pushState({}, '', newUrl);
                } else {
                    window.history.pushState({}, '', window.location.pathname);
                }
                
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'none';
                }
                
                if (partnersGrid) {
                    partnersGrid.style.opacity = '0.7';
                    setTimeout(() => {
                        partnersGrid.style.opacity = '1';
                    }, 100);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'none';
                }
                
                if (partnersGrid) {
                    partnersGrid.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Erreur lors de la recherche. Veuillez réessayer.
                            </div>
                        </div>
                    `;
                }
                
                showToast('Erreur lors de la recherche', 'error');
            });
        }, searchDelay);
    }
    
    // Update active filters display
    function updateActiveFilters(filters = null) {
        if (!activeFilters || !activeFiltersList) return;
        
        let filtersHtml = '';
        let hasActiveFilters = false;
        
        if (!filters) {
            filters = {
                search: searchInput ? searchInput.value : '',
                type: typeSelect ? typeSelect.value : '',
                city: citySelect ? citySelect.value : ''
            };
        }
        
        if (filters.search && filters.search.trim() !== '') {
            hasActiveFilters = true;
            filtersHtml += `
                <span class="badge bg-primary me-2 mb-2">
                    <i class="fas fa-search me-1"></i>
                    "${filters.search}"
                    <button type="button" class="btn-close btn-close-white ms-1" data-clear="search" title="Supprimer ce filtre"></button>
                </span>
            `;
        }
        
        if (filters.type && filters.type !== '') {
            hasActiveFilters = true;
            const typeOption = typeSelect ? typeSelect.querySelector(`option[value="${filters.type}"]`) : null;
            const typeText = typeOption ? typeOption.textContent : filters.type;
            
            filtersHtml += `
                <span class="badge bg-success me-2 mb-2">
                    <i class="fas fa-tag me-1"></i>
                    ${typeText}
                    <button type="button" class="btn-close btn-close-white ms-1" data-clear="type" title="Supprimer ce filtre"></button>
                </span>
            `;
        }
        
        if (filters.city && filters.city !== '') {
            hasActiveFilters = true;
            filtersHtml += `
                <span class="badge bg-info me-2 mb-2">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    ${filters.city}
                    <button type="button" class="btn-close btn-close-white ms-1" data-clear="city" title="Supprimer ce filtre"></button>
                </span>
            `;
        }
        
        if (hasActiveFilters) {
            activeFiltersList.innerHTML = filtersHtml;
            activeFilters.style.display = 'block';
            
            activeFiltersList.querySelectorAll('[data-clear]').forEach(button => {
                button.addEventListener('click', function() {
                    const filterType = this.getAttribute('data-clear');
                    clearFilter(filterType);
                });
            });
        } else {
            activeFilters.style.display = 'none';
        }
        
        if (clearSearchBtn) {
            if (searchInput && searchInput.value.trim()) {
                clearSearchBtn.style.display = 'block';
            } else {
                clearSearchBtn.style.display = 'none';
            }
        }
    }
    
    function clearFilter(filterType) {
        switch (filterType) {
            case 'search':
                if (searchInput) searchInput.value = '';
                break;
            case 'type':
                if (typeSelect) typeSelect.value = '';
                break;
            case 'city':
                if (citySelect) citySelect.value = '';
                break;
        }
        performSearch();
    }
    
    function clearAllFilters() {
        if (searchInput) searchInput.value = '';
        if (typeSelect) typeSelect.value = '';
        if (citySelect) citySelect.value = '';
        performSearch();
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
        const heartIcon = button.querySelector('i');
        
        button.disabled = true;
        
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
                if (data.is_favorite) {
                    button.classList.remove('btn-light');
                    button.classList.add('btn-danger', 'favorited');
                    heartIcon.classList.remove('text-danger');
                    heartIcon.classList.add('text-white');
                    button.title = 'Retirer des favoris';
                    
                    // Notification verte pour l'ajout aux favoris
                    showFavoriteToast('Partenaire ajouté aux favoris avec succès !', 'added');
                } else {
                    button.classList.remove('btn-danger', 'favorited');
                    button.classList.add('btn-light');
                    heartIcon.classList.remove('text-white');
                    heartIcon.classList.add('text-danger');
                    button.title = 'Ajouter aux favoris';
                    
                    // Notification bleue pour la suppression des favoris
                    showFavoriteToast('Partenaire retiré des favoris.', 'removed');
                }
            } else {
                showToast(data.message || 'Une erreur est survenue.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Une erreur est survenue.', 'error');
        })
        .finally(() => {
            button.disabled = false;
        });
    }
    
    // Event listeners
    if (searchInput) {
        searchInput.addEventListener('input', performSearch);
        searchInput.addEventListener('keyup', function(event) {
            if (event.key === 'Escape') {
                this.value = '';
                performSearch();
            }
        });
    }
    
    if (typeSelect) {
        typeSelect.addEventListener('change', performSearch);
    }
    
    if (citySelect) {
        citySelect.addEventListener('change', performSearch);
    }
    
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            performSearch();
        });
    }
    
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', clearAllFilters);
    }
    
    // Initialize
    initializeFavoriteButtons();
    updateActiveFilters();
    
    // Category hover effects
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1.1)';
                icon.style.transition = 'transform 0.3s ease';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1)';
            }
        });
    });
});

// Toast notification function
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
    
    // Ajouter les styles pour les animations
    if (!document.querySelector('#favorite-toast-styles')) {
        const styles = document.createElement('style');
        styles.id = 'favorite-toast-styles';
        styles.textContent = `
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
    }
    
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
    
    #partners-grid {
        transition: opacity 0.3s ease;
    }
    
    .auto-filter {
        transition: border-color 0.3s ease;
    }
    
    .auto-filter:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
`;
document.head.appendChild(styles);
</script>
@endpush