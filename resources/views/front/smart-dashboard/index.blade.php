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
<!-- 💬 Chatbot -->
    <div id="chatbot-container"
     class="fixed bottom-5 right-5 bg-white shadow-2xl rounded-2xl w-80 overflow-hidden border border-gray-200">
    <div class="bg-blue-600 text-white p-3 text-center font-semibold">
        🤖 Smart Assistant
    </div>

    <div id="chat-window" class="p-3 h-80 overflow-y-auto space-y-2 bg-gray-50"></div>

    <div class="p-2 flex items-center border-t border-gray-300">
        <input id="user-input"
               type="text"
               placeholder="Écris un message..."
               class="flex-1 p-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
        <button id="send-btn"
                class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm transition">
            Envoyer
        </button>
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
<!-- Section Calendrier Intelligent - À ajouter après les cartes de performance -->
<div class="row mb-4" data-aos="fade-up" data-aos-delay="150">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-brain me-2"></i>
                    Calendrier Intelligent IA
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-light" id="prevMonth">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span id="currentMonth" class="px-3 py-1 text-white"></span>
                    <button class="btn btn-sm btn-light" id="nextMonth">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">
                            <i class="fas fa-bullseye text-primary me-2"></i>
                            Sélectionner un objectif :
                        </label>
                        <select id="objectiveSelect" class="form-select form-select-lg">
                            <option value="">-- Choisir un objectif --</option>
                            @foreach($myObjectives as $obj)
                                <option value="{{ $obj->id }}" 
                                        data-color="{{ $obj->category === 'sport' ? '#28a745' : ($obj->category === 'education' ? '#007bff' : '#ffc107') }}"
                                        data-difficulty="{{ $obj->difficulty ?? 'medium' }}"
                                        data-frequency="{{ $obj->frequency ?? 'daily' }}">
                                    {{ $obj->title }} ({{ ucfirst($obj->category) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-magic text-warning me-2"></i>
                            Actions intelligentes :
                        </label>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-warning flex-fill" id="aiSuggest" title="L'IA suggère les meilleurs jours">
                                <i class="fas fa-wand-magic-sparkles me-1"></i>Suggérer IA
                            </button>
                            <button class="btn btn-success" id="saveSchedule">
                                <i class="fas fa-save"></i>
                            </button>
                            <button class="btn btn-danger" id="clearSelection">
                                <i class="fas fa-eraser"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Insights IA -->
                <div id="aiInsights" class="alert alert-info border-0 shadow-sm mb-4" style="display: none;">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-lightbulb fa-2x text-warning me-3"></i>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-2">💡 Suggestions intelligentes</h6>
                            <div id="aiInsightsContent"></div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques rapides -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary text-white p-3 rounded">
                            <i class="fas fa-calendar-check mb-2"></i>
                            <h4 class="mb-0" id="totalScheduled">0</h4>
                            <small>Jours planifiés</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success text-white p-3 rounded">
                            <i class="fas fa-fire mb-2"></i>
                            <h4 class="mb-0" id="streakDays">0</h4>
                            <small>Jours consécutifs</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning text-white p-3 rounded">
                            <i class="fas fa-chart-line mb-2"></i>
                            <h4 class="mb-0" id="aiScore">0%</h4>
                            <small>Score optimal IA</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-info text-white p-3 rounded">
                            <i class="fas fa-battery-three-quarters mb-2"></i>
                            <h4 class="mb-0" id="workloadScore">Moyen</h4>
                            <small>Charge de travail</small>
                        </div>
                    </div>
                </div>
                
                <div id="calendar" class="calendar-grid"></div>
                
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-primary me-2"><i class="fas fa-circle"></i> Sport</span>
                        <span class="badge bg-info me-2"><i class="fas fa-circle"></i> Éducation</span>
                        <span class="badge bg-warning me-2"><i class="fas fa-circle"></i> Autre</span>
                        <span class="badge bg-success me-2"><i class="fas fa-star"></i> Suggéré IA</span>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Cliquez pour planifier • Double-clic pour détails
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour détails du jour -->
<div class="modal fade" id="dayDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-day me-2"></i>
                    <span id="modalDate"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Analyse IA du jour -->
                <div class="alert alert-light border mb-3">
                    <h6 class="mb-2"><i class="fas fa-brain text-primary me-2"></i>Analyse IA du jour</h6>
                    <div id="dayAiAnalysis" class="small"></div>
                </div>
                
                <h6 class="mb-3">📋 Objectifs planifiés :</h6>
                <div id="dayObjectivesList"></div>
                
                <!-- Suggestions IA pour ce jour -->
                <div id="daySuggestions" class="mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="optimizeDay">
                    <i class="fas fa-wand-magic-sparkles me-1"></i>Optimiser ce jour
                </button>
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
<style>
#chatbot-container {
    font-family: 'Inter', sans-serif;
    z-index: 9999;
    position: fixed;!important;
    bottom: 20px;
    right: 20px;
    width: 360px;
    max-height: 500px;
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease-in-out;
}

/* --- HEADER --- */
#chatbot-container .header {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    padding: 12px 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
#chatbot-container .header i {
    cursor: pointer;
    font-size: 18px;
    transition: transform 0.2s ease;
}
#chatbot-container .header i:hover {
    transform: rotate(90deg);
}

/* --- CHAT AREA --- */
#chat-window {
    flex: 1;
    padding: 15px;
    background: #f9fafb;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* --- MESSAGES --- */
.message {
    max-width: 80%;
    padding: 8px 12px;
    border-radius: 14px;
    line-height: 1.4;
    word-wrap: break-word;
    animation: fadeIn 0.3s ease;
    font-size: 0.9rem;
}
.user-message {
    background: #2563eb;
    color: white;
    align-self: flex-end;
    border-bottom-right-radius: 4px;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
}
.bot-message {
    background: #e5e7eb;
    color: #111827;
    align-self: flex-start;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

/* --- INPUT AREA --- */
#chatbot-container .input-area {
    display: flex;
    align-items: center;
    border-top: 1px solid #e5e7eb;
    background: #ffffff;
    padding: 8px;
}
#chatbot-container input {
    flex: 1;
    padding: 10px;
    border: none;
    background: #f3f4f6;
    border-radius: 8px;
    font-size: 0.9rem;
    outline: none;
    transition: background 0.2s;
}
#chatbot-container input:focus {
    background: #e0e7ff;
}
#chatbot-container button {
    background: #2563eb;
    color: white;
    border: none;
    margin-left: 8px;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}
#chatbot-container button:hover {
    background: #1e40af;
}

/* --- TYPING INDICATOR --- */
.typing {
    display: inline-flex;
    gap: 4px;
    align-items: center;
}
.typing span {
    width: 6px;
    height: 6px;
    background: #9ca3af;
    border-radius: 50%;
    animation: blink 1.4s infinite both;
}
.typing span:nth-child(2) { animation-delay: 0.2s; }
.typing span:nth-child(3) { animation-delay: 0.4s; }

@keyframes blink {
    0%, 80%, 100% { opacity: 0; }
    40% { opacity: 1; }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* --- RESPONSIVE --- */
@media (max-width: 600px) {
    #chatbot-container {
        width: 90%;
        right: 5%;
        bottom: 10px;
    }
}
</style>
<style>
/* Gradient background */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Styles pour le calendrier */
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    margin-top: 20px;
}

.calendar-day-header {
    text-align: center;
    font-weight: 700;
    padding: 12px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    font-size: 0.85rem;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.calendar-day {
    aspect-ratio: 1;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 10px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: white;
    position: relative;
    min-height: 90px;
    overflow: hidden;
}

.calendar-day::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, transparent 0%, rgba(102, 126, 234, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.calendar-day:hover::before {
    opacity: 1;
}

.calendar-day:hover {
    border-color: #667eea;
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
}

.calendar-day.other-month {
    opacity: 0.3;
    pointer-events: none;
}

.calendar-day.today {
    border-color: #667eea;
    background: linear-gradient(135deg, #f0f2ff 0%, #e8ebff 100%);
    font-weight: 600;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.calendar-day.selected {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.calendar-day.ai-suggested {
    border: 2px dashed #28a745;
    background: linear-gradient(135deg, #f0fff4 0%, #e6ffed 100%);
    animation: pulse-suggestion 2s infinite;
}

.calendar-day.ai-suggested::after {
    content: '⭐';
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 0.8rem;
}

@keyframes pulse-suggestion {
    0%, 100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
    50% { box-shadow: 0 0 0 8px rgba(40, 167, 69, 0); }
}

.calendar-day.overloaded {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
    border-color: #dc3545;
}

.calendar-day.overloaded::after {
    content: '⚠️';
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 0.8rem;
}

.day-number {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 6px;
    position: relative;
    z-index: 1;
}

.day-objectives {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-top: 8px;
    position: relative;
    z-index: 1;
}

.objective-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.objective-dot:hover {
    transform: scale(1.5);
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

.objective-count {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: rgba(0,0,0,0.7);
    color: white;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 0.7rem;
    font-weight: 600;
}

/* Statistiques */
.stat-card {
    text-align: center;
    transition: all 0.3s ease;
    border: none;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.stat-card i {
    font-size: 1.5rem;
    display: block;
}

.stat-card h4 {
    font-size: 1.8rem;
    font-weight: 700;
}

/* Modal styles */
.objective-item-modal {
    padding: 15px;
    border-left: 4px solid;
    margin-bottom: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.objective-item-modal:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Loading animation */
@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}

.loading {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
}

/* Responsive */
@media (max-width: 768px) {
    .calendar-day {
        min-height: 70px;
        padding: 6px;
    }
    
    .day-number {
        font-size: 0.85rem;
    }
    
    .objective-dot {
        width: 8px;
        height: 8px;
    }
    
    .stat-card h4 {
        font-size: 1.3rem;
    }
}

/* Animations pour mini notification */
@keyframes slideIn {
    from {
        transform: translateX(100px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100px);
        opacity: 0;
    }
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
    let chartData = @json($chartData);

    if (!chartData || typeof chartData !== 'object') {
        chartData = {};
    }

    const dates = [];
    const dailyData = [];
    const cumulativeData = [];
    let runningTotal = 0;

    for (let i = 29; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dateStr = date.toISOString().split('T')[0];
        dates.push(date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' }));
        
        const value = chartData[dateStr] || 0;
        dailyData.push(value);
        runningTotal += value;
        cumulativeData.push(runningTotal);
    }

    // Create gradients
    const gradientDaily = ctx.createLinearGradient(0, 0, 0, 150);
    gradientDaily.addColorStop(0, 'rgba(105, 108, 255, 0.3)');
    gradientDaily.addColorStop(1, 'rgba(105, 108, 255, 0)');

    const gradientCumulative = ctx.createLinearGradient(0, 0, 0, 150);
    gradientCumulative.addColorStop(0, 'rgba(3, 195, 236, 0.3)');
    gradientCumulative.addColorStop(1, 'rgba(3, 195, 236, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Progrès quotidiens',
                data: dailyData,
                borderColor: '#696cff',
                backgroundColor: gradientDaily,
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: '#696cff',
                pointHoverBorderColor: '#fff'
            }, {
                label: 'Progrès cumulé',
                data: cumulativeData,
                borderColor: '#03c3ec',
                backgroundColor: gradientCumulative,
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: '#03c3ec',
                pointHoverBorderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Hiding legend for a cleaner look
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: '#fff',
                    titleColor: '#333',
                    bodyColor: '#666',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 10,
                    usePointStyle: true,
                    boxPadding: 4,
                    callbacks: {
                        label: function(context) {
                            return ` ${context.dataset.label}: ${context.formattedValue}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                        borderDash: [3, 3],
                        drawBorder: false
                    },
                    ticks: {
                        padding: 10,
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        padding: 10,
                        font: { size: 11 },
                        maxRotation: 0,
                        minRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 7
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
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
<script>
const chatWindow = document.getElementById('chat-window');
const input = document.getElementById('user-input');
const sendBtn = document.getElementById('send-btn');

function appendMessage(content, type) {
    const msg = document.createElement('div');
    msg.classList.add('message', type === 'user' ? 'user-message' : 'bot-message');
    msg.textContent = content;
    chatWindow.appendChild(msg);
    chatWindow.scrollTop = chatWindow.scrollHeight;
}

function appendTyping() {
    const typing = document.createElement('div');
    typing.classList.add('message', 'bot-message');
    typing.innerHTML = `<div class="typing"><span></span><span></span><span></span></div>`;
    chatWindow.appendChild(typing);
    chatWindow.scrollTop = chatWindow.scrollHeight;
    return typing;
}

sendBtn.addEventListener('click', handleSend);
input.addEventListener('keypress', e => { if (e.key === 'Enter') handleSend(); });

async function handleSend() {
    const message = input.value.trim();
    if (!message) return;
    appendMessage(message, 'user');
    input.value = '';

    const typing = appendTyping();

    try {
        const response = await fetch('{{ route("chatbot.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        });

        const data = await response.json();
        typing.remove();

        if (data.reply) {
            simulateTyping(data.reply);
        } else {
            appendMessage("😕 Oups, pas de réponse du serveur.", 'bot');
        }
    } catch (error) {
        typing.remove();
        appendMessage("🚨 Erreur serveur.", 'bot');
    }
}

function simulateTyping(text, i = 0) {
    const msg = document.createElement('div');
    msg.classList.add('message', 'bot-message');
    chatWindow.appendChild(msg);

    function type() {
        if (i < text.length) {
            msg.textContent += text.charAt(i);
            i++;
            chatWindow.scrollTop = chatWindow.scrollHeight;
            setTimeout(type, 15);
        }
    }
    type();
}
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    initSmartCalendar();
});

let currentDate = new Date();
let selectedObjective = null;
let scheduledObjectives = {};
let aiSuggestions = {};
let currentUserId = null; // ID de l'utilisateur connecté

// Données de performance utilisateur simulées (à remplacer par des vraies données)
const userPerformanceData = {
    bestDays: [1, 3, 5], // Lundi, Mercredi, Vendredi (0 = Dimanche)
    avgCompletionRate: 0.75,
    preferredTimes: ['morning', 'evening'],
    energyLevels: {
        monday: 0.8,
        tuesday: 0.7,
        wednesday: 0.9,
        thursday: 0.6,
        friday: 0.8,
        saturday: 0.9,
        sunday: 0.7
    }
};

// Récupérer l'ID utilisateur depuis le DOM
function getCurrentUserId() {
    // Essayer de récupérer depuis un élément meta ou data attribute
    const userElement = document.querySelector('[data-user-id]');
    if (userElement) {
        return userElement.dataset.userId;
    }
    
    // Alternative: depuis un meta tag
    const metaUser = document.querySelector('meta[name="user-id"]');
    if (metaUser) {
        return metaUser.content;
    }
    
    // Par défaut, utiliser un identifiant unique basé sur le timestamp du navigateur
    let userId = localStorage.getItem('temp_user_id');
    if (!userId) {
        userId = 'user_' + Date.now();
        localStorage.setItem('temp_user_id', userId);
    }
    return userId;
}

// Obtenir la clé de stockage pour l'utilisateur courant
function getStorageKey() {
    if (!currentUserId) {
        currentUserId = getCurrentUserId();
    }
    return 'objective_schedule_' + currentUserId;
}

function initSmartCalendar() {
    loadScheduledObjectives();
    
    document.getElementById('prevMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    
    document.getElementById('nextMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    
    document.getElementById('objectiveSelect').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        selectedObjective = this.value ? {
            id: this.value,
            name: option.text,
            color: option.dataset.color,
            difficulty: option.dataset.difficulty,
            frequency: option.dataset.frequency
        } : null;
        
        if (selectedObjective) {
            showAiInsights();
        }
    });
    
    document.getElementById('aiSuggest').addEventListener('click', generateAiSuggestions);
    document.getElementById('saveSchedule').addEventListener('click', saveSchedule);
    document.getElementById('clearSelection').addEventListener('click', clearSelection);
    document.getElementById('optimizeDay').addEventListener('click', optimizeCurrentDay);
    
    renderCalendar();
    updateStatistics();
}

function renderCalendar() {
    const calendar = document.getElementById('calendar');
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    document.getElementById('currentMonth').textContent = 
        currentDate.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
    
    calendar.innerHTML = '';
    
    const dayHeaders = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
    dayHeaders.forEach(day => {
        const header = document.createElement('div');
        header.className = 'calendar-day-header';
        header.textContent = day;
        calendar.appendChild(header);
    });
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    
    let startDay = firstDay.getDay() - 1;
    if (startDay === -1) startDay = 6;
    
    const prevMonthLastDay = new Date(year, month, 0).getDate();
    for (let i = startDay - 1; i >= 0; i--) {
        const day = prevMonthLastDay - i;
        const cell = createSmartDayCell(day, true, year, month - 1);
        calendar.appendChild(cell);
    }
    
    const today = new Date();
    for (let day = 1; day <= lastDay.getDate(); day++) {
        const isToday = day === today.getDate() && 
                       month === today.getMonth() && 
                       year === today.getFullYear();
        const cell = createSmartDayCell(day, false, year, month, isToday);
        calendar.appendChild(cell);
    }
    
    const remainingCells = 42 - (startDay + lastDay.getDate());
    for (let day = 1; day <= remainingCells; day++) {
        const cell = createSmartDayCell(day, true, year, month + 1);
        calendar.appendChild(cell);
    }
    
    updateStatistics();
}

function createSmartDayCell(day, isOtherMonth, year, month, isToday = false) {
    const cell = document.createElement('div');
    cell.className = 'calendar-day';
    
    if (isOtherMonth) {
        cell.classList.add('other-month');
    }
    
    if (isToday) {
        cell.classList.add('today');
    }
    
    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    const date = new Date(year, month, day);
    const dayOfWeek = date.getDay();
    
    cell.dataset.date = dateStr;
    
    // Vérifier si le jour est suggéré par l'IA
    if (aiSuggestions[dateStr]) {
        cell.classList.add('ai-suggested');
    }
    
    // Vérifier la charge de travail
    const objectiveCount = scheduledObjectives[dateStr] ? scheduledObjectives[dateStr].length : 0;
    if (objectiveCount > 3) {
        cell.classList.add('overloaded');
    }
    
    const dayNumber = document.createElement('div');
    dayNumber.className = 'day-number';
    dayNumber.textContent = day;
    cell.appendChild(dayNumber);
    
    const objectivesContainer = document.createElement('div');
    objectivesContainer.className = 'day-objectives';
    
    if (scheduledObjectives[dateStr]) {
        scheduledObjectives[dateStr].forEach(objId => {
            const select = document.getElementById('objectiveSelect');
            const option = select.querySelector(`option[value="${objId}"]`);
            if (option) {
                const dot = document.createElement('span');
                dot.className = 'objective-dot';
                dot.style.backgroundColor = option.dataset.color;
                dot.title = option.text;
                objectivesContainer.appendChild(dot);
            }
        });
        
        if (scheduledObjectives[dateStr].length > 5) {
            const count = document.createElement('span');
            count.className = 'objective-count';
            count.textContent = `+${scheduledObjectives[dateStr].length - 5}`;
            cell.appendChild(count);
        }
    }
    
    cell.appendChild(objectivesContainer);
    
    if (!isOtherMonth) {
        let clickTimeout;
        cell.addEventListener('click', function() {
            clearTimeout(clickTimeout);
            clickTimeout = setTimeout(() => {
                if (selectedObjective) {
                    toggleObjectiveForDay(dateStr, selectedObjective.id);
                    renderCalendar();
                }
            }, 200);
        });
        
        cell.addEventListener('dblclick', function() {
            clearTimeout(clickTimeout);
            showSmartDayDetails(dateStr, date);
        });
    }
    
    return cell;
}

function generateAiSuggestions() {
    if (!selectedObjective) {
        showNotification('Veuillez sélectionner un objectif', 'warning');
        return;
    }
    
    const btn = document.getElementById('aiSuggest');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Analyse IA...';
    btn.disabled = true;
    
    setTimeout(() => {
        aiSuggestions = {};
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const lastDay = new Date(year, month + 1, 0).getDate();
        
        const frequency = selectedObjective.frequency || 'daily';
        const difficulty = selectedObjective.difficulty || 'medium';
        
        let suggestedDays = [];
        
        // Algorithme IA intelligent basé sur les performances et préférences
        for (let day = 1; day <= lastDay; day++) {
            const date = new Date(year, month, day);
            const dayOfWeek = date.getDay();
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            // Ne pas suggérer les jours passés
            if (date < new Date()) continue;
            
            // Ne pas surcharger les jours déjà pleins
            const currentLoad = scheduledObjectives[dateStr] ? scheduledObjectives[dateStr].length : 0;
            if (currentLoad >= 4) continue;
            
            // Score basé sur les performances historiques
            let score = 0;
            
            // Jours de meilleure performance
            if (userPerformanceData.bestDays.includes(dayOfWeek)) {
                score += 30;
            }
            
            // Niveau d'énergie du jour
            const dayName = date.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
            score += (userPerformanceData.energyLevels[dayName] || 0.5) * 40;
            
            // Difficulté de l'objectif
            if (difficulty === 'hard' && userPerformanceData.energyLevels[dayName] > 0.7) {
                score += 15;
            }
            
            // Éviter le weekend pour les objectifs difficiles
            if (difficulty === 'hard' && (dayOfWeek === 0 || dayOfWeek === 6)) {
                score -= 20;
            }
            
            // Bonus pour le début de semaine
            if (dayOfWeek === 1) score += 10;
            
            // Distribution équilibrée
            const existingCount = scheduledObjectives[dateStr] ? scheduledObjectives[dateStr].length : 0;
            score -= existingCount * 10;
            
            suggestedDays.push({ date: dateStr, score });
        }
        
        // Trier par score et sélectionner les meilleurs jours
        suggestedDays.sort((a, b) => b.score - a.score);
        
        let daysToSuggest = 0;
        if (frequency === 'daily') daysToSuggest = Math.min(20, suggestedDays.length);
        else if (frequency === 'weekly') daysToSuggest = 4;
        else if (frequency === 'monthly') daysToSuggest = 2;
        else daysToSuggest = Math.min(10, suggestedDays.length);
        
        for (let i = 0; i < daysToSuggest; i++) {
            aiSuggestions[suggestedDays[i].date] = {
                score: suggestedDays[i].score,
                reason: generateSuggestionReason(suggestedDays[i].date, suggestedDays[i].score)
            };
        }
        
        renderCalendar();
        
        const insights = document.getElementById('aiInsights');
        const content = document.getElementById('aiInsightsContent');
        content.innerHTML = `
            <p class="mb-2"><strong>${daysToSuggest} jours optimaux</strong> ont été suggérés pour "${selectedObjective.name}"</p>
            <ul class="mb-0 small">
                <li>Basé sur vos meilleures performances (${userPerformanceData.bestDays.map(d => ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'][d]).join(', ')})</li>
                <li>Adapté à la difficulté de l'objectif (${difficulty})</li>
                <li>Charge de travail équilibrée sur le mois</li>
                <li>Taux de réussite estimé : ${Math.round(userPerformanceData.avgCompletionRate * 100)}%</li>
            </ul>
            <button class="btn btn-sm btn-success mt-2" onclick="acceptAllSuggestions()">
                <i class="fas fa-check me-1"></i>Accepter toutes les suggestions
            </button>
        `;
        insights.style.display = 'block';
        
        btn.innerHTML = '<i class="fas fa-wand-magic-sparkles me-1"></i>Suggérer IA';
        btn.disabled = false;
        
        showNotification('Suggestions IA générées avec succès !', 'success');
    }, 1500);
}

function generateSuggestionReason(dateStr, score) {
    const date = new Date(dateStr);
    const dayName = date.toLocaleDateString('fr-FR', { weekday: 'long' });
    
    if (score > 80) return `${dayName} - Journée optimale avec haute énergie`;
    if (score > 60) return `${dayName} - Bon jour pour cet objectif`;
    if (score > 40) return `${dayName} - Performance attendue correcte`;
    return `${dayName} - Jour disponible`;
}

function acceptAllSuggestions() {
    Object.keys(aiSuggestions).forEach(dateStr => {
        if (!scheduledObjectives[dateStr]) {
            scheduledObjectives[dateStr] = [];
        }
        if (!scheduledObjectives[dateStr].includes(selectedObjective.id)) {
            scheduledObjectives[dateStr].push(selectedObjective.id);
        }
    });
    
    aiSuggestions = {};
    autoSaveSchedule(); // Auto-sauvegarde
    renderCalendar();
    showNotification('Toutes les suggestions ont été appliquées !', 'success');
}

function showSmartDayDetails(dateStr, date) {
    document.getElementById('modalDate').textContent = 
        date.toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    
    // Analyse IA du jour
    const dayOfWeek = date.getDay();
    const dayName = date.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
    const energyLevel = userPerformanceData.energyLevels[dayName] || 0.5;
    const isBestDay = userPerformanceData.bestDays.includes(dayOfWeek);
    const objectiveCount = scheduledObjectives[dateStr] ? scheduledObjectives[dateStr].length : 0;
    
    let workloadLevel = 'Faible';
    let workloadColor = 'success';
    if (objectiveCount > 3) {
        workloadLevel = 'Élevée';
        workloadColor = 'danger';
    } else if (objectiveCount > 1) {
        workloadLevel = 'Modérée';
        workloadColor = 'warning';
    }
    
    const analysis = document.getElementById('dayAiAnalysis');
    analysis.innerHTML = `
        <div class="row g-2">
            <div class="col-6">
                <div class="border rounded p-2 text-center">
                    <div class="text-${energyLevel > 0.7 ? 'success' : energyLevel > 0.5 ? 'warning' : 'danger'}">
                        <i class="fas fa-battery-${energyLevel > 0.7 ? 'full' : energyLevel > 0.5 ? 'half' : 'quarter'} fa-2x"></i>
                    </div>
                    <small>Énergie: ${Math.round(energyLevel * 100)}%</small>
                </div>
            </div>
            <div class="col-6">
                <div class="border rounded p-2 text-center">
                    <div class="text-${workloadColor}">
                        <i class="fas fa-tasks fa-2x"></i>
                    </div>
                    <small>Charge: ${workloadLevel}</small>
                </div>
            </div>
            <div class="col-6">
                <div class="border rounded p-2 text-center">
                    <div class="text-${isBestDay ? 'success' : 'muted'}">
                        <i class="fas fa-${isBestDay ? 'star' : 'star-half-alt'} fa-2x"></i>
                    </div>
                    <small>${isBestDay ? 'Jour optimal' : 'Jour standard'}</small>
                </div>
            </div>
            <div class="col-6">
                <div class="border rounded p-2 text-center">
                    <div class="text-info">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <small>${objectiveCount} objectif${objectiveCount > 1 ? 's' : ''}</small>
                </div>
            </div>
        </div>
        <div class="mt-2 p-2 bg-light rounded">
            <strong>Recommandation IA :</strong>
            ${generateDayRecommendation(energyLevel, objectiveCount, isBestDay)}
        </div>
    `;
    
    // Liste des objectifs
    const list = document.getElementById('dayObjectivesList');
    list.innerHTML = '';
    
    if (scheduledObjectives[dateStr] && scheduledObjectives[dateStr].length > 0) {
        scheduledObjectives[dateStr].forEach(objId => {
            const select = document.getElementById('objectiveSelect');
            const option = select.querySelector(`option[value="${objId}"]`);
            if (option) {
                const item = document.createElement('div');
                item.className = 'objective-item-modal';
                item.style.borderColor = option.dataset.color;
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <strong>${option.text}</strong>
                            <div class="small text-muted mt-1">
                                <span class="badge bg-secondary">${option.dataset.difficulty || 'medium'}</span>
                                <span class="badge bg-info">${option.dataset.frequency || 'daily'}</span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-danger" onclick="removeObjectiveFromDay('${dateStr}', '${objId}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                list.appendChild(item);
            }
        });
    } else {
        list.innerHTML = '<p class="text-muted text-center py-3">Aucun objectif planifié ce jour</p>';
    }
    
    // Suggestions IA pour ce jour
    const suggestions = document.getElementById('daySuggestions');
    if (objectiveCount < 3 && date > new Date()) {
        const availableObjectives = getAvailableObjectivesForDay(dateStr);
        if (availableObjectives.length > 0) {
            suggestions.innerHTML = `
                <div class="alert alert-success border-0">
                    <h6 class="mb-2"><i class="fas fa-lightbulb me-2"></i>Objectifs recommandés pour ce jour</h6>
                    <div class="d-flex flex-wrap gap-2">
                        ${availableObjectives.map(obj => `
                            <button class="btn btn-sm btn-outline-success" onclick="quickAddObjective('${dateStr}', '${obj.id}')">
                                <i class="fas fa-plus me-1"></i>${obj.name}
                            </button>
                        `).join('')}
                    </div>
                </div>
            `;
        } else {
            suggestions.innerHTML = '';
        }
    } else if (objectiveCount >= 3) {
        suggestions.innerHTML = `
            <div class="alert alert-warning border-0">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Ce jour est déjà bien chargé. Évitez d'ajouter plus d'objectifs.
            </div>
        `;
    } else {
        suggestions.innerHTML = '';
    }
    
    // Stocker la date courante pour l'optimisation
    document.getElementById('optimizeDay').dataset.currentDate = dateStr;
    
    new bootstrap.Modal(document.getElementById('dayDetailsModal')).show();
}

function generateDayRecommendation(energyLevel, objectiveCount, isBestDay) {
    if (objectiveCount === 0) {
        if (energyLevel > 0.7 && isBestDay) {
            return "🎯 Journée excellente ! C'est le moment idéal pour planifier des objectifs ambitieux.";
        } else if (energyLevel > 0.5) {
            return "✅ Bon jour pour ajouter 2-3 objectifs modérés.";
        } else {
            return "💡 Planifiez des objectifs légers pour cette journée.";
        }
    } else if (objectiveCount <= 2) {
        if (energyLevel > 0.7) {
            return "✨ Vous pouvez encore ajouter 1-2 objectifs sans surcharge.";
        } else {
            return "👍 Charge actuelle appropriée pour ce jour.";
        }
    } else if (objectiveCount <= 3) {
        return "⚖️ Charge optimale atteinte. Évitez d'en ajouter davantage.";
    } else {
        return "⚠️ Attention : Charge élevée ! Risque de burnout. Redistribuez certains objectifs.";
    }
}

function getAvailableObjectivesForDay(dateStr) {
    const select = document.getElementById('objectiveSelect');
    const available = [];
    
    for (let i = 1; i < select.options.length; i++) {
        const option = select.options[i];
        const objId = option.value;
        
        // Vérifier si l'objectif n'est pas déjà planifié ce jour
        if (!scheduledObjectives[dateStr] || !scheduledObjectives[dateStr].includes(objId)) {
            available.push({
                id: objId,
                name: option.text.split(' (')[0],
                color: option.dataset.color
            });
        }
    }
    
    return available.slice(0, 3); // Maximum 3 suggestions
}

window.quickAddObjective = function(dateStr, objId) {
    if (!scheduledObjectives[dateStr]) {
        scheduledObjectives[dateStr] = [];
    }
    scheduledObjectives[dateStr].push(objId);
    autoSaveSchedule(); // Auto-sauvegarde
    renderCalendar();
    showSmartDayDetails(dateStr, new Date(dateStr));
    showNotification('Objectif ajouté !', 'success');
};

window.removeObjectiveFromDay = function(dateStr, objectiveId) {
    toggleObjectiveForDay(dateStr, objectiveId);
    renderCalendar();
    showSmartDayDetails(dateStr, new Date(dateStr));
};

function optimizeCurrentDay() {
    const dateStr = document.getElementById('optimizeDay').dataset.currentDate;
    const date = new Date(dateStr);
    
    if (!scheduledObjectives[dateStr] || scheduledObjectives[dateStr].length === 0) {
        showNotification('Aucun objectif à optimiser', 'info');
        return;
    }
    
    // Algorithme d'optimisation basé sur la difficulté et l'énergie
    const dayName = date.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
    const energyLevel = userPerformanceData.energyLevels[dayName] || 0.5;
    
    const select = document.getElementById('objectiveSelect');
    const objectives = scheduledObjectives[dateStr].map(id => {
        const option = select.querySelector(`option[value="${id}"]`);
        return {
            id,
            difficulty: option.dataset.difficulty || 'medium',
            name: option.text
        };
    });
    
    // Recommandations d'optimisation
    let recommendations = [];
    
    // Trop d'objectifs difficiles pour un jour à faible énergie
    const hardObjectives = objectives.filter(o => o.difficulty === 'hard').length;
    if (hardObjectives > 1 && energyLevel < 0.6) {
        recommendations.push("⚠️ Trop d'objectifs difficiles pour votre niveau d'énergie ce jour-là.");
        recommendations.push("💡 Redistribuez 1-2 objectifs difficiles vers des jours à haute énergie.");
    }
    
    // Surcharge
    if (objectives.length > 4) {
        recommendations.push("⚠️ Charge trop élevée ! Redistribuez au moins " + (objectives.length - 3) + " objectif(s).");
    }
    
    // Optimisations positives
    if (hardObjectives <= 1 && energyLevel > 0.7 && objectives.length <= 3) {
        recommendations.push("✅ Planning optimal ! Bonne répartition pour ce jour.");
    }
    
    if (recommendations.length > 0) {
        showNotification(recommendations.join('\n'), 'info');
    } else {
        showNotification('Ce jour est déjà bien optimisé !', 'success');
    }
}

function showAiInsights() {
    if (!selectedObjective) return;
    
    const insights = document.getElementById('aiInsights');
    const content = document.getElementById('aiInsightsContent');
    
    const difficulty = selectedObjective.difficulty || 'medium';
    const frequency = selectedObjective.frequency || 'daily';
    
    let difficultyAdvice = '';
    if (difficulty === 'hard') {
        difficultyAdvice = 'Planifiez cet objectif les jours où vous êtes le plus performant (énergie élevée).';
    } else if (difficulty === 'easy') {
        difficultyAdvice = 'Objectif flexible, peut être planifié n\'importe quel jour.';
    } else {
        difficultyAdvice = 'Objectif modéré, recommandé pour les jours avec énergie moyenne à élevée.';
    }
    
    content.innerHTML = `
        <p class="mb-2"><strong>Analyse pour "${selectedObjective.name}"</strong></p>
        <ul class="mb-2 small">
            <li>Difficulté : <span class="badge bg-${difficulty === 'hard' ? 'danger' : difficulty === 'easy' ? 'success' : 'warning'}">${difficulty}</span></li>
            <li>Fréquence recommandée : ${frequency}</li>
            <li>${difficultyAdvice}</li>
            <li>Meilleurs jours : ${userPerformanceData.bestDays.map(d => ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'][d]).join(', ')}</li>
        </ul>
        <button class="btn btn-sm btn-primary" onclick="document.getElementById('aiSuggest').click()">
            <i class="fas fa-magic me-1"></i>Obtenir des suggestions
        </button>
    `;
    insights.style.display = 'block';
}

function toggleObjectiveForDay(dateStr, objectiveId) {
    if (!scheduledObjectives[dateStr]) {
        scheduledObjectives[dateStr] = [];
    }
    
    const index = scheduledObjectives[dateStr].indexOf(objectiveId);
    if (index > -1) {
        scheduledObjectives[dateStr].splice(index, 1);
        if (scheduledObjectives[dateStr].length === 0) {
            delete scheduledObjectives[dateStr];
        }
    } else {
        scheduledObjectives[dateStr].push(objectiveId);
    }
    
    // Auto-save en localStorage à chaque modification
    autoSaveSchedule();
}

// Fonction d'auto-sauvegarde AMÉLIORÉE
function autoSaveSchedule() {
    try {
        // Vérifier qu'on a des données à sauvegarder
        if (!scheduledObjectives || typeof scheduledObjectives !== 'object') {
            console.warn('⚠️ Aucune donnée à sauvegarder');
            return false;
        }
        
        const objectiveCount = Object.keys(scheduledObjectives).length;
        console.log('💾 Tentative de sauvegarde:', objectiveCount, 'jours planifiés');
        
        if (objectiveCount === 0) {
            console.log('ℹ️ Planning vide, pas de sauvegarde');
            return false;
        }
        
        const storageKey = getStorageKey();
        const dataToSave = JSON.stringify(scheduledObjectives);
        
        console.log('📝 Clé:', storageKey);
        console.log('📝 Données à sauvegarder:', dataToSave);
        
        // Sauvegarder
        localStorage.setItem(storageKey, dataToSave);
        
        // Vérifier immédiatement
        const verification = localStorage.getItem(storageKey);
        if (verification === dataToSave) {
            console.log('✅ Sauvegarde réussie et vérifiée!');
            showMiniNotification('💾 Sauvegardé (' + objectiveCount + ' jours)');
            return true;
        } else {
            console.error('❌ Échec de la vérification de sauvegarde');
            return false;
        }
        
    } catch (error) {
        console.error('❌ Erreur auto-sauvegarde:', error);
        showNotification('Erreur de sauvegarde: ' + error.message, 'error');
        return false;
    }
}

// Mini notification discrète
function showMiniNotification(text) {
    const existing = document.querySelector('.mini-notification');
    if (existing) existing.remove();
    
    const notif = document.createElement('div');
    notif.className = 'mini-notification';
    notif.textContent = text;
    notif.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: rgba(40, 167, 69, 0.9);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    document.body.appendChild(notif);
    
    setTimeout(() => {
        notif.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notif.remove(), 300);
    }, 2000);
}

function updateStatistics() {
    // Total des jours planifiés
    const totalScheduled = Object.keys(scheduledObjectives).length;
    document.getElementById('totalScheduled').textContent = totalScheduled;
    
    // Calculer le streak
    let streak = 0;
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    for (let i = 0; i < 30; i++) {
        const checkDate = new Date(today);
        checkDate.setDate(today.getDate() - i);
        const dateStr = checkDate.toISOString().split('T')[0];
        
        if (scheduledObjectives[dateStr] && scheduledObjectives[dateStr].length > 0) {
            streak++;
        } else if (i > 0) {
            break;
        }
    }
    document.getElementById('streakDays').textContent = streak;
    
    // Score optimal IA
    let totalScore = 0;
    let scoredDays = 0;
    
    Object.keys(scheduledObjectives).forEach(dateStr => {
        const date = new Date(dateStr);
        const dayOfWeek = date.getDay();
        const dayName = date.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
        const energyLevel = userPerformanceData.energyLevels[dayName] || 0.5;
        const isBestDay = userPerformanceData.bestDays.includes(dayOfWeek);
        const objectiveCount = scheduledObjectives[dateStr].length;
        
        let dayScore = 0;
        
        // Score basé sur l'énergie
        dayScore += energyLevel * 40;
        
        // Bonus si c'est un jour optimal
        if (isBestDay) dayScore += 20;
        
        // Pénalité pour surcharge
        if (objectiveCount > 3) dayScore -= 20;
        else if (objectiveCount === 0) dayScore = 0;
        else dayScore += 40;
        
        totalScore += dayScore;
        scoredDays++;
    });
    
    const aiScore = scoredDays > 0 ? Math.round(totalScore / scoredDays) : 0;
    document.getElementById('aiScore').textContent = aiScore + '%';
    
    // Charge de travail
    const avgObjectivesPerDay = totalScheduled > 0 ? 
        Object.values(scheduledObjectives).reduce((sum, arr) => sum + arr.length, 0) / totalScheduled : 0;
    
    let workloadText = 'Faible';
    if (avgObjectivesPerDay > 3) workloadText = 'Élevé';
    else if (avgObjectivesPerDay > 1.5) workloadText = 'Moyen';
    
    document.getElementById('workloadScore').textContent = workloadText;
}

function clearSelection() {
    selectedObjective = null;
    document.getElementById('objectiveSelect').value = '';
    document.getElementById('aiInsights').style.display = 'none';
    aiSuggestions = {};
    renderCalendar();
    showNotification('Sélection effacée', 'info');
    // Note: On ne vide pas scheduledObjectives, seulement la sélection courante
}

async function saveSchedule() {
    const btn = document.getElementById('saveSchedule');
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;
    
    try {
        const storageKey = getStorageKey();
        
        // Sauvegarder d'abord en localStorage pour persistance immédiate
        localStorage.setItem(storageKey, JSON.stringify(scheduledObjectives));
        console.log('✅ Planning sauvegardé en localStorage');
        console.log('📦 Données:', scheduledObjectives);
        
        // Puis envoyer au serveur
        const response = await fetch('/objectives/schedule', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ schedule: scheduledObjectives })
        });
        
        if (!response.ok) {
            throw new Error('Erreur serveur');
        }
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('✅ Planning enregistré avec succès !', 'success');
            console.log('✅ Planning sauvegardé sur le serveur');
        } else {
            showNotification('⚠️ Sauvegardé localement seulement', 'warning');
        }
    } catch (error) {
        console.error('❌ Erreur:', error);
        // Même si le serveur échoue, on a sauvegardé en localStorage
        showNotification('✅ Planning sauvegardé localement', 'info');
    } finally {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}

async function loadScheduledObjectives() {
    const storageKey = getStorageKey();
    console.log('🔄 Chargement du planning pour clé:', storageKey);
    
    try {
        // Charger depuis localStorage
        const localData = localStorage.getItem(storageKey);
        console.log('📦 Données brutes localStorage (typeof):', typeof localData);
        console.log('📦 Données brutes localStorage (value):', localData);
        console.log('📦 Données brutes localStorage (length):', localData ? localData.length : 0);
        
        if (localData && localData !== 'null' && localData !== '{}' && localData !== '[]') {
            try {
                const parsed = JSON.parse(localData);
                console.log('🔍 Type après parsing:', typeof parsed);
                console.log('🔍 Valeur après parsing:', parsed);
                console.log('🔍 Est un Array?:', Array.isArray(parsed));
                console.log('🔍 Keys:', Object.keys(parsed));
                
                if (parsed && typeof parsed === 'object' && Object.keys(parsed).length > 0) {
                    scheduledObjectives = parsed;
                    console.log('✅ Planning chargé depuis localStorage:', scheduledObjectives);
                    console.log('📊 Nombre de jours planifiés:', Object.keys(scheduledObjectives).length);
                    renderCalendar();
                    return;
                } else {
                    console.warn('⚠️ Données vides ou invalides:', parsed);
                }
            } catch (e) {
                console.error('❌ Erreur parsing localStorage:', e);
                localStorage.removeItem(storageKey);
            }
        } else {
            console.log('ℹ️ Aucune donnée locale trouvée ou données vides');
        }
        
        // Si pas de données locales, essayer le serveur
        console.log('🌐 Tentative de chargement depuis le serveur...');
        const response = await fetch('/objectives/get-schedule');
        
        if (response.ok) {
            const data = await response.json();
            console.log('📥 Réponse serveur:', data);
            
            if (data.success && data.schedule && Object.keys(data.schedule).length > 0) {
                scheduledObjectives = data.schedule;
                localStorage.setItem(storageKey, JSON.stringify(scheduledObjectives));
                console.log('✅ Planning chargé depuis le serveur');
                renderCalendar();
                return;
            }
        }
        
        // Si rien n'est trouvé, initialiser avec un objet vide
        if (Object.keys(scheduledObjectives).length === 0) {
            scheduledObjectives = {};
            console.log('ℹ️ Initialisation avec planning vide');
        }
        
        renderCalendar();
        
    } catch (error) {
        console.error('❌ Erreur lors du chargement:', error);
        scheduledObjectives = {};
        renderCalendar();
    }
}

// Fonction utilitaire pour les notifications
function showNotification(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : type === 'info' ? 'info' : 'danger'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
    }
    
    container.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}
</script>


@endpush
