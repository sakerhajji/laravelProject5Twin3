@extends('layouts.front')

@section('title', 'Dashboard Intelligent')

@section('content')
<div class="container-fluid py-4">
    <!-- Header avec statistiques rapides -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">🧠 Dashboard Intelligent</h1>
                    <p class="text-muted mb-0">Vos insights personnalisés et recommandations IA</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="refreshInsights()">
                        <i class="fas fa-sync-alt me-1"></i>Actualiser
                    </button>
                    <a href="{{ route('front.objectives.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Nouveaux objectifs
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de performance -->
    <div class="row mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $insights['performance_summary']['total_objectives'] }}</h4>
                            <p class="mb-0">Objectifs actifs</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-bullseye fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $insights['performance_summary']['completion_rate'] }}%</h4>
                            <p class="mb-0">Taux de réussite</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-trophy fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $insights['performance_summary']['current_streak'] }}</h4>
                            <p class="mb-0">Jours de streak</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-fire fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $insights['performance_summary']['performance_score'] }}</h4>
                            <p class="mb-0">Score global</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne gauche - Insights et recommandations -->
        <div class="col-lg-8">
            <!-- Graphiques -->
            <div class="row mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-area me-2"></i>
                                Évolution des progrès (30 derniers jours)
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="progressChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommandations IA -->
            <div class="row mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-robot me-2"></i>
                                Recommandations IA
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="recommendationsContainer">
                                @foreach($recommendations as $rec)
                                <div class="recommendation-card mb-3 p-3 border rounded">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            @if($rec->objective->cover_url)
                                                <img src="{{ $rec->objective->cover_url }}" class="img-fluid rounded" alt="{{ $rec->objective->title }}" style="height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px;">
                                                    <i class="fas fa-bullseye text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-1">{{ $rec->objective->title }}</h6>
                                            <p class="text-muted small mb-1">{{ Str::limit($rec->objective->description, 80) }}</p>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-primary">{{ ucfirst($rec->objective->category) }}</span>
                                                <span class="badge bg-success">Score: {{ round($rec->score, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <p class="small text-muted mb-2">{{ $rec->reason }}</p>
                                            <form action="{{ route('front.objectives.activate', $rec->objective) }}" method="post" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus me-1"></i>Activer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insights et prédictions -->
            <div class="row" data-aos="fade-up" data-aos-delay="400">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-lightbulb me-2"></i>
                                Insights intelligents
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(!empty($insights['strengths']))
                                <h6 class="text-success mb-3">Vos points forts</h6>
                                @foreach($insights['strengths'] as $strength)
                                <div class="alert alert-success alert-sm">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ $strength['message'] }}
                                </div>
                                @endforeach
                            @endif

                            @if(!empty($insights['improvements']))
                                <h6 class="text-warning mb-3 mt-4">Suggestions d'amélioration</h6>
                                @foreach($insights['improvements'] as $improvement)
                                <div class="alert alert-warning alert-sm">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ $improvement['message'] }}
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-crystal-ball me-2"></i>
                                Prédictions
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(!empty($insights['predictions']))
                                @foreach($insights['predictions'] as $prediction)
                                <div class="prediction-item mb-3 p-2 border-start border-primary border-3">
                                    <h6 class="mb-1">{{ $prediction['objective']->title }}</h6>
                                    <p class="small text-muted mb-1">{{ $prediction['message'] }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-info">Confiance: {{ round($prediction['confidence'] * 100) }}%</span>
                                        <small class="text-muted">{{ $prediction['days_to_complete'] }} jours</small>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">Continuez vos efforts pour voir des prédictions !</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Activité récente et badges -->
        <div class="col-lg-4">
            <!-- Mes objectifs avec progrès -->
            <div class="card mb-4" data-aos="fade-up" data-aos-delay="500">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-target me-2"></i>
                        Mes objectifs
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($myObjectives->take(5) as $objective)
                        @php
                            $progress = $objective->computeProgressPercent(auth()->id());
                            $trend = $objective->trendForUser(auth()->id());
                        @endphp
                        <div class="objective-item mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0">{{ $objective->title }}</h6>
                                <span class="badge bg-primary">{{ $progress }}%</span>
                            </div>
                            <div class="progress mb-1" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $objective->target_value }} {{ $objective->unit }}</small>
                                @if($trend === 'up')
                                    <i class="fas fa-arrow-up text-success"></i>
                                @elseif($trend === 'down')
                                    <i class="fas fa-arrow-down text-danger"></i>
                                @else
                                    <i class="fas fa-minus text-muted"></i>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Aucun objectif actif</p>
                    @endforelse
                </div>
            </div>

            <!-- Activité récente -->
            <div class="card mb-4" data-aos="fade-up" data-aos-delay="600">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Activité récente
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($recentProgress as $progress)
                        <div class="activity-item mb-3 d-flex align-items-center">
                            <div class="activity-icon me-3">
                                <i class="fas fa-chart-line text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 small">{{ $progress->objective->title }}</h6>
                                <p class="text-muted small mb-0">{{ $progress->value }} {{ $progress->objective->unit }}</p>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">{{ $progress->entry_date->format('M j') }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Aucune activité récente</p>
                    @endforelse
                </div>
            </div>

            <!-- Badges récents -->
            <div class="card" data-aos="fade-up" data-aos-delay="700">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>
                        Badges récents
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($recentBadges as $badge)
                        <div class="badge-item mb-3 d-flex align-items-center">
                            <div class="badge-icon me-3">
                                <i class="{{ $badge->icon }} fa-2x" style="color: {{ $badge->color }};"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $badge->title }}</h6>
                                <p class="text-muted small mb-0">{{ $badge->description }}</p>
                                <small class="text-muted">{{ $badge->earned_at->format('M j, Y') }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Aucun badge récent</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.recommendation-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.recommendation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.prediction-item {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.activity-item {
    transition: background-color 0.2s ease;
}

.activity-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 8px;
    margin: -8px;
}

.badge-item {
    transition: transform 0.2s ease;
}

.badge-item:hover {
    transform: translateX(5px);
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le graphique
    initProgressChart();
    
    // Actualiser les insights toutes les 5 minutes
    setInterval(refreshInsights, 300000);
});

function initProgressChart() {
    const ctx = document.getElementById('progressChart').getContext('2d');
    const chartData = @json($chartData['daily']);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Progrès quotidiens',
                data: chartData.daily,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Progrès cumulé',
                data: chartData.cumulative,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function refreshInsights() {
    // Afficher un indicateur de chargement
    const refreshBtn = document.querySelector('[onclick="refreshInsights()"]');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Actualisation...';
    refreshBtn.disabled = true;
    
    // Récupérer les nouvelles recommandations
    fetch('{{ route("front.smart-dashboard.recommendations") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateRecommendations(data.recommendations);
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'actualisation:', error);
        })
        .finally(() => {
            // Restaurer le bouton
            refreshBtn.innerHTML = originalText;
            refreshBtn.disabled = false;
        });
}

function updateRecommendations(recommendations) {
    const container = document.getElementById('recommendationsContainer');
    
    if (recommendations.length === 0) {
        container.innerHTML = '<p class="text-muted text-center">Aucune nouvelle recommandation pour le moment</p>';
        return;
    }
    
    let html = '';
    recommendations.forEach(rec => {
        html += `
            <div class="recommendation-card mb-3 p-3 border rounded">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        ${rec.cover_url ? 
                            `<img src="${rec.cover_url}" class="img-fluid rounded" alt="${rec.title}" style="height: 60px; object-fit: cover;">` :
                            `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px;">
                                <i class="fas fa-bullseye text-muted"></i>
                            </div>`
                        }
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-1">${rec.title}</h6>
                        <p class="text-muted small mb-1">${rec.description.substring(0, 80)}...</p>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary">${rec.category}</span>
                            <span class="badge bg-success">Score: ${rec.score}</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <p class="small text-muted mb-2">${rec.reason}</p>
                        <button class="btn btn-sm btn-primary" onclick="activateObjective(${rec.id})">
                            <i class="fas fa-plus me-1"></i>Activer
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function activateObjective(objectiveId) {
    // Implémenter l'activation d'objectif via AJAX
    fetch(`/objectifs/${objectiveId}/activate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher une notification de succès
            showNotification('Objectif activé avec succès !', 'success');
            // Actualiser la page après un court délai
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur lors de l\'activation', 'error');
    });
}

function showNotification(message, type) {
    // Créer une notification toast
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    // Ajouter au container de toasts
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
    }
    
    container.appendChild(toast);
    
    // Afficher la notification
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Supprimer après fermeture
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}
</script>
@endpush
