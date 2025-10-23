@extends('layouts.front')

@section('title', 'Nos Activités')

@section('content')
<style>
/* === Overall Page Styling === */
body {
    background: #f9fafc;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

/* === Activity Cards === */
.activity-img {
    height: 200px;
    object-fit: cover;
    width: 100%;
    border-top-left-radius: .85rem; 
    border-top-right-radius: .85rem;
    transition: transform 0.4s ease;
}

.card {
    border: none;
    border-radius: .85rem;
    overflow: hidden;
    transition: all 0.4s ease;
    background: #fff;
}

.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.activity-img:hover {
    transform: scale(1.07);
}

/* === Badges and Buttons === */
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
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0056b3);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(13,110,253,0.3);
}

.icon-btn {
    color: #6c757d;
    font-size: 1.2rem;
    margin-right: 10px;
    transition: all 0.3s ease;
}

.icon-btn:hover {
    color: #0d6efd;
    transform: scale(1.15);
}

/* === Card Footer === */
.activity-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}

/* === Filters === */
.card-header h4 i {
    color: #0d6efd;
}

.breadcrumb-item a {
    color: #0d6efd;
}
</style>

<div class="container py-4">

    <!-- Breadcrumb -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Activités</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Hero Banner -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="py-5 bg-gradient-primary text-white rounded-4 shadow-lg text-center">
                <h2 class="display-6 fw-bold mb-3">🌟 Découvrez nos Activités</h2>
                <p class="lead mb-0">Inspirez-vous et explorez les expériences qui nourrissent votre esprit et votre corps.</p>
            </div>
        </div>
    </div>

    <!-- Search Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h4 class="mb-0"><i class="fas fa-filter text-primary"></i> Filtres de Recherche</h4>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3">
                            <label for="activity-search" class="form-label fw-bold">Recherche instantanée</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" id="activity-search" class="form-control" placeholder="Titre ou description...">
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

    <!-- Activities Grid -->
    <div class="row g-4" id="activities-grid">
        @foreach($activities as $activity)
            <div class="col-lg-3 col-md-4 col-sm-6 activity-card" 
                 data-title="{{ strtolower($activity->title) }}" 
                 data-description="{{ strtolower($activity->description) }}">
                <div class="card h-100 position-relative">
                    @if($activity->is_partner ?? false)
                        <span class="partner-badge"><i class="fas fa-handshake"></i> Partenaire</span>
                    @endif

                    @if($activity->media_url)
                        @if($activity->media_type === 'image')
                            <img src="{{ $activity->media_url }}" class="activity-img" alt="{{ $activity->title }}">
                        @elseif($activity->media_type === 'video')
                            <video class="activity-img" controls>
                                <source src="{{ $activity->media_url }}" type="video/mp4">
                            </video>
                        @endif
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">{{ $activity->title }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($activity->description, 90) }}</p>

                        <div class="activity-footer mt-3">
                            <div>
                                <i class="fas fa-heart icon-btn"></i>
                                <i class="fas fa-share-alt icon-btn"></i>
                                <i class="fas fa-bookmark icon-btn"></i>
                            </div>
                            <small class="text-muted">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ $activity->created_at->format('d M Y') }}
                            </small>
                        </div>

                        <a href="#" class="btn btn-primary w-100 mt-3">
                            <i class="fas fa-eye me-2"></i>Voir Détails
                        </a>
                        <a href="{{ route('checkexercice') }}" class="btn btn-success w-100 mt-2">
    <i class="fas fa-dumbbell me-2"></i>Vérifier l'Exercice
</a>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($activities->hasPages())
        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center">
                {{ $activities->links() }}
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
clearBtn.addEventListener('click', () => { searchInput.value = ''; filterActivities(); });
resetBtn.addEventListener('click', () => { searchInput.value = ''; filterActivities(); });
</script>
@endsection
