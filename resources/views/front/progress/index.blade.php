@extends('layouts.front')

@section('title', 'Mes objectifs')

@section('content')
<div class="container">
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    
    <!-- Header avec statistiques -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">🎯 Mes Objectifs</h1>
                    <p class="text-muted mb-0">Suivez vos progrès et atteignez vos objectifs</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#progressModal">
                        <i class="fas fa-plus me-1"></i>Ajouter progrès
                    </button>
                    <a href="{{ route('front.progress.import.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-file-import me-1"></i>Import CSV
                    </a>
                    <a href="{{ route('front.objectives.index') }}" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Parcourir
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Skeleton loader -->
    <div id="skeletonLoader" class="row" style="display: none;">
        @for($i = 0; $i < 4; $i++)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="skeleton-img"></div>
                <div class="card-body">
                    <div class="skeleton-text mb-2"></div>
                    <div class="skeleton-text small mb-3"></div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="skeleton-circle"></div>
                        <div class="skeleton-progress flex-grow-1"></div>
                    </div>
                    <div class="skeleton-text small"></div>
                </div>
            </div>
        </div>
        @endfor
    </div>

    <!-- Objectifs réels -->
    <div id="objectivesContent" class="row">
        @forelse($myObjectives as $o)
            @php
                $userProgress = $o->progresses->where('user_id', auth()->id());
                $sum = $userProgress->sum('value');
                $pct = $o->target_value > 0 ? min(100, round(($sum/$o->target_value)*100)) : 0;
                $trend = method_exists($o, 'trendForUser') ? $o->trendForUser(auth()->id()) : 'flat';
                $lastUpdate = method_exists($o, 'lastUpdateForUser') ? $o->lastUpdateForUser(auth()->id()) : null;
                $recentData = $userProgress->sortBy('entry_date')->take(7)->pluck('value')->toArray();
            @endphp
            <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="card h-100 shadow-sm border-0 overflow-hidden objective-card">
                    @if($o->cover_url)
                        <div class="card-img-container">
                            <img src="{{ $o->cover_url }}" class="card-img-top" alt="{{ $o->title }}" loading="lazy">
                            <div class="card-overlay">
                                <span class="badge bg-primary bg-opacity-90">{{ ucfirst($o->category) }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">{{ $o->title }}</h5>
                                <div class="small text-muted">
                                    <i class="fas fa-bullseye me-1"></i>
                                    Cible: {{ number_format($o->target_value,2) }} {{ $o->unit }}
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('front.objectives.show', $o) }}">
                                        <i class="fas fa-eye me-2"></i>Voir détails
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#progressModal" data-obj='{"id":{{ $o->id }},"title":"{{ addslashes($o->title) }}","unit":"{{ $o->unit }}"}'>
                                        <i class="fas fa-plus me-2"></i>Ajouter progrès
                                    </a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Anneau de progression avancé -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="progress-ring-container">
                                <div class="progress-ring" data-percentage="{{ $pct }}" data-trend="{{ $trend }}">
                                    <svg class="progress-ring-svg" width="60" height="60">
                                        <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"></circle>
                                        <circle class="progress-ring-circle" cx="30" cy="30" r="25" 
                                                data-percentage="{{ $pct }}" data-trend="{{ $trend }}"></circle>
                                    </svg>
                                    <div class="progress-ring-text">
                                        <span class="percentage">{{ $pct }}%</span>
                                        <span class="trend-icon">
                                            @if($trend==='up')
                                                <i class="fas fa-arrow-up text-success"></i>
                                            @elseif($trend==='down')
                                                <i class="fas fa-arrow-down text-danger"></i>
                                            @else
                                                <i class="fas fa-minus text-muted"></i>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex-grow-1">
                                <!-- Mini sparkline -->
                                <div class="sparkline-container mb-2">
                                    <canvas class="sparkline" width="120" height="30" 
                                            data-values="{{ implode(',', $recentData) }}"></canvas>
                                </div>
                                
                                <!-- Barre de progression -->
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         role="progressbar" style="width: {{ $pct }}%" 
                                         aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-1 small text-muted">
                                    <span>{{ $sum }}/{{ $o->target_value }} {{ $o->unit }}</span>
                                    <span>{{ $pct }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions rapides -->
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-sm btn-success flex-grow-1" 
                                    data-bs-toggle="modal" data-bs-target="#progressModal" 
                                    data-obj='{"id":{{ $o->id }},"title":"{{ addslashes($o->title) }}","unit":"{{ $o->unit }}"}'>
                                <i class="fas fa-plus me-1"></i>Progrès
                            </button>
                            <a href="{{ route('front.objectives.show', $o) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-chart-line"></i>
                            </a>
                        </div>

                        <!-- Dernière mise à jour -->
                        @if($userProgress->count())
                            <div class="small text-muted d-flex align-items-center">
                                <i class="fas fa-clock me-1"></i>
                                Dernière mise à jour: {{ $lastUpdate ?? optional($userProgress->first())->entry_date?->format('d/m/Y') }}
                            </div>
                        @else
                            <div class="small text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Aucun progrès enregistré
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-bullseye fa-3x text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Aucun objectif actif</h4>
                    <p class="text-muted mb-4">Commencez votre parcours en activant vos premiers objectifs</p>
                    <a href="{{ route('front.objectives.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-search me-2"></i>Parcourir les objectifs
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    <!-- Modal Progrès -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un progrès</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('front.progress.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="objective_id" id="pm_objective_id">
                        <div class="mb-3">
                            <label class="form-label">Objectif</label>
                            <input type="text" class="form-control" id="pm_objective_title" disabled>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">Date</label>
                                <input type="date" name="entry_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Valeur <span id="pm_unit"></span></label>
                                <input type="number" step="0.01" min="0" name="value" class="form-control" placeholder="">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Note</label>
                                <textarea name="note" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('styles')
<style>
/* Skeleton loaders */
.skeleton-img {
    height: 140px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
}

.skeleton-text {
    height: 1rem;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
    border-radius: 4px;
}

.skeleton-text.small {
    height: 0.75rem;
    width: 60%;
}

.skeleton-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
}

.skeleton-progress {
    height: 8px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
    border-radius: 4px;
}

@keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Anneau de progression avancé */
.progress-ring-container {
    position: relative;
    display: inline-block;
}

.progress-ring {
    position: relative;
    display: inline-block;
}

.progress-ring-svg {
    transform: rotate(-90deg);
}

.progress-ring-circle-bg {
    fill: none;
    stroke: #e9ecef;
    stroke-width: 4;
}

.progress-ring-circle {
    fill: none;
    stroke-width: 4;
    stroke-linecap: round;
    transition: stroke-dasharray 0.5s ease-in-out;
}

.progress-ring-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    font-size: 0.75rem;
    font-weight: 600;
}

.progress-ring-text .percentage {
    display: block;
    font-size: 0.9rem;
}

.progress-ring-text .trend-icon {
    font-size: 0.6rem;
}

/* Sparkline */
.sparkline-container {
    height: 30px;
    display: flex;
    align-items: center;
}

.sparkline {
    width: 100%;
    height: 30px;
}

/* Card enhancements */
.objective-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.objective-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.card-img-container {
    position: relative;
    overflow: hidden;
}

.card-img-container img {
    transition: transform 0.3s ease-in-out;
}

.objective-card:hover .card-img-container img {
    transform: scale(1.05);
}

.card-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
}

/* Progress bar animations */
.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% { background-position: 1rem 0; }
    100% { background-position: 0 0; }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .progress-ring-svg {
        width: 50px;
        height: 50px;
    }
    
    .progress-ring-circle-bg,
    .progress-ring-circle {
        r: 20;
        cx: 25;
        cy: 25;
    }
    
    .progress-ring-text {
        font-size: 0.7rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des anneaux de progression
    initProgressRings();
    
    // Initialisation des sparklines
    initSparklines();
    
    // Gestion de la modale
    document.getElementById('progressModal')?.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        try {
            const data = JSON.parse(button.getAttribute('data-obj'));
            document.getElementById('pm_objective_id').value = data.id;
            document.getElementById('pm_objective_title').value = data.title;
            document.getElementById('pm_unit').textContent = '(' + (data.unit||'') + ')';
        } catch(e) {}
    });
    
    // Simulation de chargement avec skeleton
    setTimeout(() => {
        document.getElementById('skeletonLoader').style.display = 'none';
        document.getElementById('objectivesContent').style.display = 'block';
    }, 1000);
});

function initProgressRings() {
    const rings = document.querySelectorAll('.progress-ring-circle');
    
    rings.forEach(ring => {
        const percentage = parseInt(ring.getAttribute('data-percentage'));
        const trend = ring.getAttribute('data-trend');
        const radius = ring.getAttribute('r');
        const circumference = 2 * Math.PI * radius;
        
        // Couleur selon la tendance
        let color = '#0d6efd'; // bleu par défaut
        if (trend === 'up') color = '#28a745'; // vert
        else if (trend === 'down') color = '#dc3545'; // rouge
        
        ring.style.stroke = color;
        ring.style.strokeDasharray = circumference;
        ring.style.strokeDashoffset = circumference - (percentage / 100) * circumference;
    });
}

function initSparklines() {
    const sparklines = document.querySelectorAll('.sparkline');
    
    sparklines.forEach(canvas => {
        const values = canvas.getAttribute('data-values').split(',').map(v => parseFloat(v) || 0);
        
        if (values.length === 0 || values.every(v => v === 0)) {
            // Afficher une ligne plate si pas de données
            const ctx = canvas.getContext('2d');
            ctx.strokeStyle = '#dee2e6';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(0, 15);
            ctx.lineTo(120, 15);
            ctx.stroke();
            return;
        }
        
        const ctx = canvas.getContext('2d');
        const width = canvas.width;
        const height = canvas.height;
        const padding = 5;
        
        // Normaliser les valeurs
        const min = Math.min(...values);
        const max = Math.max(...values);
        const range = max - min || 1;
        
        // Dessiner la ligne
        ctx.strokeStyle = '#0d6efd';
        ctx.lineWidth = 2;
        ctx.beginPath();
        
        values.forEach((value, index) => {
            const x = (index / (values.length - 1)) * (width - 2 * padding) + padding;
            const y = height - padding - ((value - min) / range) * (height - 2 * padding);
            
            if (index === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        });
        
        ctx.stroke();
        
        // Ajouter des points
        ctx.fillStyle = '#0d6efd';
        values.forEach((value, index) => {
            const x = (index / (values.length - 1)) * (width - 2 * padding) + padding;
            const y = height - padding - ((value - min) / range) * (height - 2 * padding);
            
            ctx.beginPath();
            ctx.arc(x, y, 2, 0, 2 * Math.PI);
            ctx.fill();
        });
    });
}
</script>
@endpush
@endsection
