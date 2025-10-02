@extends('layouts.front')

@section('title', 'Import CSV & Heatmap')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">📊 Import CSV & Heatmap</h1>
                    <p class="text-muted mb-0">Importez vos données et visualisez votre activité</p>
                </div>
                <a href="{{ route('front.progress.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Import CSV -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-import me-2"></i>
                        Import CSV
                    </h5>
                </div>
                <div class="card-body">
                    <form id="importForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="objective_id" class="form-label">Objectif</label>
                            <select class="form-select" id="objective_id" name="objective_id" required>
                                <option value="">Sélectionner un objectif</option>
                                @foreach($objectives as $objective)
                                    <option value="{{ $objective->id }}">
                                        {{ $objective->title }} ({{ $objective->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Fichier CSV</label>
                            <input type="file" class="form-control" id="csv_file" name="csv_file" 
                                   accept=".csv,.txt" required>
                            <div class="form-text">
                                Format: date,valeur,note (optionnel)
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i>Importer
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="downloadTemplate()">
                                <i class="fas fa-download me-1"></i>Template
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4">
                        <h6>Format CSV attendu:</h6>
                        <pre class="bg-light p-3 rounded small">date,valeur,note
2024-01-01,5.5,Commentaire optionnel
2024-01-02,6.0,
2024-01-03,5.8,Très bien</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Heatmap -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Heatmap d'activité
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="heatmap_objective" class="form-label">Objectif</label>
                        <select class="form-select" id="heatmap_objective" onchange="loadHeatmap()">
                            <option value="">Sélectionner un objectif</option>
                            @foreach($objectives as $objective)
                                <option value="{{ $objective->id }}">
                                    {{ $objective->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="heatmapContainer">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-calendar fa-3x mb-3"></i>
                            <p>Sélectionnez un objectif pour voir la heatmap</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="d-flex align-items-center justify-content-between small text-muted">
                            <span>Moins d'activité</span>
                            <div class="d-flex gap-1">
                                <div class="heatmap-legend" style="background-color: #ebedf0;"></div>
                                <div class="heatmap-legend" style="background-color: #c6e48b;"></div>
                                <div class="heatmap-legend" style="background-color: #7bc96f;"></div>
                                <div class="heatmap-legend" style="background-color: #239a3b;"></div>
                                <div class="heatmap-legend" style="background-color: #196127;"></div>
                            </div>
                            <span>Plus d'activité</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Badges -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>
                        Mes Badges
                    </h5>
                </div>
                <div class="card-body">
                    <div id="badgesContainer" class="row">
                        <!-- Les badges seront chargés ici -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="importToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-info-circle me-2"></i>
            <strong class="me-auto">Import</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <!-- Message dynamique -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.heatmap-legend {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

.heatmap-day {
    width: 12px;
    height: 12px;
    border-radius: 2px;
    margin: 1px;
    display: inline-block;
    background-color: #ebedf0;
    cursor: pointer;
    transition: transform 0.1s ease;
}

.heatmap-day:hover {
    transform: scale(1.2);
}

.badge-card {
    transition: transform 0.2s ease;
}

.badge-card:hover {
    transform: translateY(-2px);
}

.badge-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.badge-gold { color: #ffd700; }
.badge-orange { color: #ff8c00; }
.badge-red { color: #dc3545; }
.badge-blue { color: #0d6efd; }
.badge-green { color: #28a745; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du formulaire d'import
    document.getElementById('importForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("front.progress.import.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                document.getElementById('importForm').reset();
                loadBadges(); // Recharger les badges
            } else {
                showToast('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Erreur lors de l\'import');
        });
    });
    
    // Charger les badges au démarrage
    loadBadges();
});

function downloadTemplate() {
    window.open('{{ route("front.progress.import.template") }}', '_blank');
}

function loadHeatmap() {
    const objectiveId = document.getElementById('heatmap_objective').value;
    if (!objectiveId) {
        document.getElementById('heatmapContainer').innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-calendar fa-3x mb-3"></i>
                <p>Sélectionnez un objectif pour voir la heatmap</p>
            </div>
        `;
        return;
    }
    
    // Simuler le chargement de la heatmap
    document.getElementById('heatmapContainer').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2">Génération de la heatmap...</p>
        </div>
    `;
    
    // Simuler des données de heatmap
    setTimeout(() => {
        generateHeatmap(objectiveId);
    }, 1000);
}

function generateHeatmap(objectiveId) {
    // Générer une heatmap simulée pour les 365 derniers jours
    const heatmapHtml = `
        <div class="heatmap-wrapper">
            <div class="d-flex flex-wrap justify-content-center">
                ${generateHeatmapDays()}
            </div>
            <div class="text-center mt-3 small text-muted">
                Activité des 365 derniers jours
            </div>
        </div>
    `;
    
    document.getElementById('heatmapContainer').innerHTML = heatmapHtml;
}

function generateHeatmapDays() {
    let html = '';
    const colors = ['#ebedf0', '#c6e48b', '#7bc96f', '#239a3b', '#196127'];
    
    for (let i = 0; i < 365; i++) {
        const intensity = Math.floor(Math.random() * 5);
        const color = colors[intensity];
        const date = new Date();
        date.setDate(date.getDate() - (364 - i));
        
        html += `<div class="heatmap-day" style="background-color: ${color};" 
                     title="${date.toLocaleDateString()}: ${intensity} entrées"></div>`;
    }
    
    return html;
}

function loadBadges() {
    // Simuler le chargement des badges
    const badges = [
        {
            title: 'Premier pas',
            description: 'Premier progrès enregistré',
            icon: 'fas fa-baby',
            color: 'blue',
            earned_at: '2024-01-15'
        },
        {
            title: 'Streak 7 jours',
            description: '7 jours consécutifs de progrès',
            icon: 'fas fa-fire',
            color: 'orange',
            earned_at: '2024-01-20'
        },
        {
            title: 'Objectif atteint',
            description: 'Objectif 100% accompli',
            icon: 'fas fa-trophy',
            color: 'gold',
            earned_at: '2024-02-01'
        }
    ];
    
    let badgesHtml = '';
    if (badges.length === 0) {
        badgesHtml = `
            <div class="col-12 text-center py-4">
                <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun badge obtenu</h5>
                <p class="text-muted">Continuez vos efforts pour débloquer vos premiers badges !</p>
            </div>
        `;
    } else {
        badges.forEach(badge => {
            badgesHtml += `
                <div class="col-md-4 mb-3">
                    <div class="card badge-card h-100 text-center">
                        <div class="card-body">
                            <div class="badge-icon badge-${badge.color}">
                                <i class="${badge.icon}"></i>
                            </div>
                            <h6 class="card-title">${badge.title}</h6>
                            <p class="card-text small text-muted">${badge.description}</p>
                            <small class="text-muted">Obtenu le ${new Date(badge.earned_at).toLocaleDateString()}</small>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    
    document.getElementById('badgesContainer').innerHTML = badgesHtml;
}

function showToast(type, message) {
    const toastElement = document.getElementById('importToast');
    const toastBody = toastElement.querySelector('.toast-body');
    const toastHeader = toastElement.querySelector('.toast-header');
    
    toastBody.textContent = message;
    
    // Changer la couleur selon le type
    toastHeader.className = 'toast-header';
    if (type === 'success') {
        toastHeader.classList.add('bg-success', 'text-white');
    } else if (type === 'error') {
        toastHeader.classList.add('bg-danger', 'text-white');
    } else {
        toastHeader.classList.add('bg-primary', 'text-white');
    }
    
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}
</script>
@endpush
