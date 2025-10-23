@extends('layouts.front')

@section('title', 'Activités - ' . $category->title)

@section('content')
<style>
.activity-img {
    height: 180px;
    object-fit: cover;
    width: 100%;
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
                    <li class="breadcrumb-item"><a href="{{ route('front.categories.index') }}" class="text-decoration-none">Catégories</a></li>
                    <li class="breadcrumb-item active">{{ $category->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="py-5 bg-primary text-white rounded-4 p-5 shadow-lg text-center">
                <h2 class="display-6 fw-bold mb-3">Activités pour : {{ $category->title }}</h2>
                <p class="lead mb-0">Découvrez toutes les activités associées à cette catégorie</p>
            </div>
        </div>
    </div>

    <!-- Search Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-filter"></i> Filtres de Recherche</h4>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3">
                            <label for="activity-search" class="form-label fw-bold">Recherche instantanée</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" id="activity-search" name="search" class="form-control" placeholder="Titre ou description...">
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

    <!-- Total Activities Found -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info" id="total-activities">
                <i class="fas fa-info-circle"></i>
                <strong>{{ $activities->total() }}</strong> activité(s) trouvée(s)
            </div>
        </div>
    </div>

    <!-- Activities Grid -->
    <div class="row g-4" id="activities-grid">
        @foreach($activities as $activity)
        <div class="col-lg-3 col-md-4 col-sm-6 activity-card" 
             data-title="{{ strtolower($activity->title) }}" 
             data-description="{{ strtolower($activity->description) }}">
            <div class="card h-100 shadow-sm">
                @if($activity->media_url)
                    <img src="{{ $activity->media_url }}" class="card-img-top activity-img" alt="{{ $activity->title }}">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $activity->title }}</h5>
                    <p class="card-text flex-grow-1">{{ Str::limit($activity->description, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">{{ $activity->time }}</small>
                        <div>
                            <i class="fas fa-heart text-danger me-1"></i> {{ $activity->likes_count }}
                            <i class="fas fa-bookmark text-primary ms-3 me-1"></i> {{ $activity->saves_count }}
                        </div>
                    </div>
                    <a  class="btn btn-primary mt-2">Voir Détails</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($activities->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center pagination-wrapper">
                {{ $activities->appends(request()->all())->links() }}
            </div>
        </div>
    @endif
</div>

<script>
const searchInput = document.getElementById('activity-search');
const clearBtn = document.getElementById('clear-search');
const resetBtn = document.getElementById('reset-filters');

function filterActivities() {
    const query = searchInput.value.toLowerCase();
    const cards = document.querySelectorAll('.activity-card');
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

    const totalDiv = document.getElementById('total-activities');
    if (totalDiv) {
        totalDiv.innerHTML = `<i class="fas fa-info-circle"></i> <strong>${count}</strong> activité(s) trouvée(s)`;
    }
}

searchInput.addEventListener('input', filterActivities);

clearBtn.addEventListener('click', () => {
    searchInput.value = '';
    filterActivities();
});

resetBtn.addEventListener('click', () => {
    searchInput.value = '';
    filterActivities();
});
</script>
@endsection
