@extends('layouts.front')

@section('title', 'Nos Activités')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Accueil</a></li>
                    <li class="breadcrumb-item active">Activités</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="py-5 bg-primary text-white rounded-4 p-5 shadow-lg">
                <div class="text-center">
                    <h2 class="display-6 fw-bold mb-3">🌟 Découvrez nos Activités</h2>
                    <p class="lead mb-0">Inspirez-vous et explorez les expériences qui nourrissent votre esprit et votre corps.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-filter"></i> Filtres de Recherche Automatique</h4>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 mb-3">
                            <label for="activity-type" class="form-label fw-bold">Type d'activité</label>
                            <select name="type" id="activity-type" class="form-select auto-filter" data-filter="type">
                                <option value="">Tous les types</option>
                                <option value="fitness">Fitness</option>
                                <option value="nutrition">Nutrition</option>
                                <option value="meditation">Méditation</option>
                                <option value="cardio">Cardio</option>
                                <option value="yoga">Yoga</option>
                                <option value="swimming">Natation</option>
                            </select>
                        </div>
                       
                        <div class="col-md-4 mb-3">
                            <label for="activity-search" class="form-label fw-bold">Recherche instantanée</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" name="search" id="activity-search" class="form-control auto-filter" 
                                       data-filter="search" placeholder="Titre, description...">
                                <button type="button" id="clear-search" class="btn btn-outline-secondary" title="Effacer la recherche" style="display: none;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="reset-filters" class="form-label fw-bold d-block">&nbsp;</label>
                            <button type="button" id="reset-filters" class="btn btn-secondary w-100" title="Réinitialiser tous les filtres">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                        </div>
                    </div>
                    
                    <!-- Indicateur de chargement -->
                    <div id="loading-indicator" class="text-center py-2" style="display: none;">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status" aria-label="Chargement">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <small class="text-muted">Recherche en cours...</small>
                    </div>
                    
                    <!-- Filters active indicator -->
                    <div id="active-filters" class="mt-3" style="display: none;">
                        <small class="text-muted fw-bold">Filtres actifs :</small>
                        <div id="active-filters-list" class="mt-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    @if($activities->count() > 0)
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>{{ $activities->total() }}</strong> activité(s) trouvée(s)
                    @if(request()->filled('type') || request()->filled('search'))
                        selon vos critères de recherche
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Activities Grid -->
    <div id="activities-grid" class="row g-4">
        @foreach($activities as $activity)
            <div class="col-lg-3 col-md-4 col-sm-6 activity-card" 
                 data-type="{{ strtolower($activity->type ?? '') }}" 
                 data-title="{{ strtolower($activity->title) }}" 
                 data-description="{{ strtolower($activity->description) }}">
                <div class="card h-100 position-relative partner-card">
                    @if($activity->is_partner ?? false)
                        <span class="partner-badge"><i class="fas fa-handshake"></i> Partenaire</span>
                    @endif

                    <!-- Favorite Button (Top Right) -->
                    <button class="btn favorite-btn toggle-favorite position-absolute" data-activity-id="{{ $activity->id }}">
                        <i class="fas fa-heart"></i>
                    </button>

                    @if($activity->media_url)
                        @if($activity->media_type === 'image')
                            <img src="{{ $activity->media_url }}" class="activity-img partner-image" alt="{{ $activity->title }}">
                        @elseif($activity->media_type === 'video')
                            <video class="activity-img partner-image" controls>
                                <source src="{{ $activity->media_url }}" type="video/mp4">
                            </video>
                        @endif
                    @else
                        <div class="activity-img partner-image partner-image-placeholder bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-running fa-3x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">{{ $activity->title }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($activity->description, 90) }}</p>

                        <!-- Activity Meta Info -->
                        <div class="activity-meta mb-3">
                            <div class="d-flex justify-content-between align-items-center text-muted small">
                                <span>
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $activity->created_at->format('d M Y') }}
                                </span>
                                @if($activity->duration ?? false)
                                <span>
                                    <i class="far fa-clock me-1"></i>
                                    {{ $activity->duration }} min
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons - Organized Layout -->
                        <div class="activity-actions mt-auto">
                            <!-- Primary Actions Row -->
                            <div class="d-grid gap-2 mb-3">
                                <!-- Updated Voir Détails button to navigate to activities by category -->
                                <a href="{{ route('front.activities.byCategory', $activity->category ?? 'default') }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>Voir Détails
                                </a>
                                <a href="{{ route('checkexercice') }}" class="btn btn-success">
                                    <i class="fas fa-dumbbell me-2"></i>Vérifier l'Exercice
                                </a>
                            </div>
                            
                            <!-- Secondary Actions Row -->
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Share Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-share-alt me-1"></i>Partager
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fab fa-facebook me-2 text-primary"></i>Facebook</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fab fa-twitter me-2 text-info"></i>Twitter</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fab fa-whatsapp me-2 text-success"></i>WhatsApp</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-link me-2"></i>Copier le lien</a></li>
                                    </ul>
                                </div>
                                
                                <!-- Save/Bookmark Button -->
                                <button class="btn btn-sm btn-outline-primary toggle-bookmark" data-activity-id="{{ $activity->id }}">
                                    <i class="far fa-bookmark me-1"></i>Sauvegarder
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($activities->hasPages())
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                <div class="pagination-wrapper">
                    {{ $activities->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('css')
<style>
/* Custom Styles for Professional Look - Same as Partners Page */
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

.activity-img, .partner-image {
    height: 220px;
    object-fit: cover;
    transition: transform 0.3s ease;
    border-radius: 15px 15px 0 0;
}

.partner-card:hover .activity-img,
.partner-card:hover .partner-image {
    transform: scale(1.05);
}

.partner-image-placeholder {
    border-radius: 15px 15px 0 0;
}

/* Favorite Button Styling */
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
    z-index: 10;
    border-radius: 50%;
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

.partner-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    color: #fff;
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 20px;
    box-shadow: 0 4px 10px rgba(13,110,253,0.3);
    z-index: 10;
}

/* Activity Actions Styling */
.activity-actions .btn {
    transition: all 0.3s ease;
    border-radius: 10px;
    font-weight: 500;
}

.activity-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.activity-actions .btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0056b3);
    border: none;
}

.activity-actions .btn-success {
    background: linear-gradient(135deg, #198754, #0f5132);
    border: none;
}

.activity-actions .btn-outline-secondary,
.activity-actions .btn-outline-primary {
    border-width: 2px;
    font-size: 0.85rem;
}

.activity-actions .btn-outline-secondary:hover {
    background: #6c757d;
    border-color: #6c757d;
}

.activity-actions .btn-outline-primary:hover {
    background: #0d6efd;
    border-color: #0d6efd;
}

/* Bookmark Button Animation */
.toggle-bookmark.bookmarked {
    background: linear-gradient(135deg, #0d6efd, #6610f2) !important;
    border-color: #0d6efd !important;
    color: white !important;
    animation: bookmarkPulse 0.5s ease-in-out;
}

@keyframes bookmarkPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Activity Meta Info */
.activity-meta {
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
    padding: 10px 0;
}

/* Dropdown Styling */
.dropdown-menu {
    border-radius: 10px;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    padding: 8px;
}

.dropdown-item {
    border-radius: 6px;
    padding: 8px 12px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: #f8f9fa;
    transform: translateX(3px);
}

.text-purple {
    color: #6f42c1 !important;
}

.btn-outline-purple {
    color: #6f42c1;
    border-color: #6f42c1;
}

.btn-outline-purple:hover {
    color: #fff;
    background-color: #6f42c1;
    border-color: #6f42c1;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let searchTimeout;
    const searchDelay = 500;
    
    // Get filter elements
    const searchInput = document.getElementById('activity-search');
    const typeSelect = document.getElementById('activity-type');
    const clearSearchBtn = document.getElementById('clear-search');
    const resetFiltersBtn = document.getElementById('reset-filters');
    
    // Results and loading elements
    const activitiesGrid = document.getElementById('activities-grid');
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
            
            // For now, we'll do client-side filtering
            filterActivitiesClientSide();
            
            // Update URL without reloading
            if (params.toString()) {
                const newUrl = `${window.location.pathname}?${params}`;
                window.history.pushState({}, '', newUrl);
            } else {
                window.history.pushState({}, '', window.location.pathname);
            }
            
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
        }, searchDelay);
    }
    
    // Client-side filtering for activities
    function filterActivitiesClientSide() {
        const searchQuery = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const typeQuery = typeSelect ? typeSelect.value : '';
        
        const activityCards = document.querySelectorAll('.activity-card');
        let visibleCount = 0;
        
        activityCards.forEach(card => {
            const title = card.dataset.title || '';
            const description = card.dataset.description || '';
            const type = card.dataset.type || '';
            
            const matchesSearch = !searchQuery || 
                title.includes(searchQuery) || 
                description.includes(searchQuery);
                
            const matchesType = !typeQuery || type === typeQuery;
            
            if (matchesSearch && matchesType) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        updateActiveFilters({
            search: searchQuery,
            type: typeQuery
        });
        
        // Update results count
        const resultsAlert = document.querySelector('.alert-info');
        if (resultsAlert) {
            const countSpan = resultsAlert.querySelector('strong');
            if (countSpan) {
                countSpan.textContent = visibleCount;
            }
        }
    }
    
    // Update active filters display
    function updateActiveFilters(filters = null) {
        if (!activeFilters || !activeFiltersList) return;
        
        let filtersHtml = '';
        let hasActiveFilters = false;
        
        if (!filters) {
            filters = {
                search: searchInput ? searchInput.value : '',
                type: typeSelect ? typeSelect.value : ''
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
        }
        performSearch();
    }
    
    function clearAllFilters() {
        if (searchInput) searchInput.value = '';
        if (typeSelect) typeSelect.value = '';
        performSearch();
    }
    
    // Initialize favorite buttons
    function initializeFavoriteButtons() {
        document.querySelectorAll('.toggle-favorite').forEach(button => {
            button.addEventListener('click', handleFavoriteClick);
        });
    }
    
    // Initialize bookmark buttons
    function initializeBookmarkButtons() {
        document.querySelectorAll('.toggle-bookmark').forEach(button => {
            button.addEventListener('click', handleBookmarkClick);
        });
    }
    
    function handleFavoriteClick(event) {
        event.preventDefault();
        const button = this;
        const activityId = button.getAttribute('data-activity-id');
        const heartIcon = button.querySelector('i');
        
        button.disabled = true;
        
        button.style.transform = 'scale(0.9)';
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 150);
        
        // For demo purposes - replace with actual API call
        setTimeout(() => {
            const isCurrentlyFavorited = button.classList.contains('favorited');
            
            if (!isCurrentlyFavorited) {
                button.classList.add('favorited');
                heartIcon.classList.remove('text-danger');
                heartIcon.classList.add('text-white');
                button.title = 'Retirer des favoris';
                
                // Notification for adding to favorites
                showFavoriteToast('Activité ajoutée aux favoris avec succès !', 'added');
            } else {
                button.classList.remove('favorited');
                heartIcon.classList.remove('text-white');
                heartIcon.classList.add('text-danger');
                button.title = 'Ajouter aux favoris';
                
                // Notification for removing from favorites
                showFavoriteToast('Activité retirée des favoris.', 'removed');
            }
            
            button.disabled = false;
        }, 300);
    }
    
    function handleBookmarkClick(event) {
        event.preventDefault();
        const button = this;
        const activityId = button.getAttribute('data-activity-id');
        const bookmarkIcon = button.querySelector('i');
        
        button.disabled = true;
        
        // For demo purposes - replace with actual API call
        setTimeout(() => {
            const isCurrentlyBookmarked = button.classList.contains('bookmarked');
            
            if (!isCurrentlyBookmarked) {
                button.classList.add('bookmarked');
                bookmarkIcon.classList.remove('far');
                bookmarkIcon.classList.add('fas');
                button.innerHTML = '<i class="fas fa-bookmark me-1"></i>Sauvegardé';
                
                // Notification for bookmarking
                showToast('Activité sauvegardée !', 'success', 'bookmark');
            } else {
                button.classList.remove('bookmarked');
                bookmarkIcon.classList.remove('fas');
                bookmarkIcon.classList.add('far');
                button.innerHTML = '<i class="far fa-bookmark me-1"></i>Sauvegarder';
                
                // Notification for unbookmarking
                showToast('Activité retirée des sauvegardes.', 'info', 'bookmark');
            }
            
            button.disabled = false;
        }, 300);
    }
    
    // Share functionality
    function initializeShareButtons() {
        // Copy link functionality
        document.querySelectorAll('.dropdown-item').forEach(item => {
            if (item.textContent.includes('Copier le lien')) {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        showToast('Lien copié dans le presse-papier !', 'success', 'link');
                    });
                });
            }
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
    initializeBookmarkButtons();
    initializeShareButtons();
    updateActiveFilters();
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
    
    #activities-grid {
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