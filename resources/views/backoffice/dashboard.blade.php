@extends('layouts.app')

@section('title', 'Dashboard Admin - Health Tracker')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
:root {
    --primary: #6366f1;
    --secondary: #8b5cf6;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --info: #3b82f6;
}

body {
    font-family: 'Inter', sans-serif !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    min-height: 100vh;
    position: relative;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.4), transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.4), transparent 50%);
    animation: bgMove 15s ease-in-out infinite;
    pointer-events: none;
}

@keyframes bgMove {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.main-wrapper {
    position: relative;
    z-index: 1;
    padding: 2rem 0;
}

.header-box {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.stat-box {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 1.75rem;
    height: 100%;
    border: 1px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.stat-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.stat-box:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.stat-box:hover::before {
    transform: scaleX(1);
}

.icon-box {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    position: relative;
}

.icon-box::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 18px;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
    pointer-events: none;
}

.stat-box:hover .icon-box {
    transform: rotate(-10deg) scale(1.15);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
}

.stat-num {
    font-size: 2.5rem;
    font-weight: 900;
    color: #1e293b;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-text {
    font-size: 0.9rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.progress-line {
    height: 8px;
    border-radius: 10px;
    background: rgba(0, 0, 0, 0.06);
    overflow: hidden;
    margin-top: 1rem;
}

.progress-fill {
    height: 100%;
    border-radius: 10px;
    transition: width 1s ease;
}

.chart-box {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    height: 100%;
    border: 1px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
}

.chart-area {
    position: relative;
    height: 320px;
    width: 100%;
}

.list-box {
    max-height: 450px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.list-box::-webkit-scrollbar {
    width: 8px;
}

.list-box::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 10px;
}

.list-box::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 10px;
}

.item-row {
    background: rgba(0, 0, 0, 0.02);
    border-radius: 14px;
    padding: 1.2rem;
    margin-bottom: 0.8rem;
    border-left: 3px solid transparent;
    transition: all 0.3s ease;
}

.item-row:hover {
    background: rgba(99, 102, 241, 0.08);
    border-left-color: var(--primary);
    transform: translateX(5px);
}

.mini-box {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 18px;
    padding: 1.75rem;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    transition: all 0.4s ease;
    height: 100%;
}

.mini-box:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15);
}

.mini-stat-icon {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    margin-bottom: 1rem;
}

.mini-stat-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.25), transparent);
    pointer-events: none;
}

.mini-box:hover .mini-stat-icon {
    transform: scale(1.1) rotate(-5deg);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
}

.activity-icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.activity-icon-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
    pointer-events: none;
}

.activity-icon-wrapper i {
    font-size: 1.3rem;
    position: relative;
    z-index: 1;
}

.item-row:hover .activity-icon-wrapper {
    transform: rotate(-5deg) scale(1.1);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
}

.user-icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    font-size: 1.1rem;
}

.user-icon-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
    pointer-events: none;
}

.item-row:hover .user-icon-wrapper {
    transform: rotate(-5deg) scale(1.1);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
}

.user-avatar-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.user-avatar {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-row:hover .user-avatar-wrapper {
    transform: scale(1.1);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.section-header-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    position: relative;
    overflow: hidden;
}

.section-header-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.25), transparent);
    pointer-events: none;
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
</style>
@endpush

@section('content')
<div class="container-fluid main-wrapper">
    
    <div class="header-box">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box bg-primary bg-gradient">
                        <i class="bi bi-speedometer2 fs-2 text-white"></i>
                    </div>
                    <div>
                        <h1 class="mb-1 fw-bold" style="font-size: 2rem; color: #1e293b;">Dashboard Admin</h1>
                        <p class="text-muted mb-0 fw-medium">Bienvenue sur Health Tracker Platform</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
                <span class="badge rounded-pill px-4 py-2 fs-6" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white;">
                    <i class="bi bi-clock-history me-2"></i>
                    {{ now()->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-box">
                <div class="icon-box bg-primary bg-gradient">
                    <i class="bi bi-people-fill fs-3 text-white"></i>
                </div>
                <h3 class="stat-num">{{ number_format($totalUsers ?? 1) }}</h3>
                <p class="stat-text mb-3">Utilisateurs Total</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-success text-white px-3 py-2">
                        <i class="bi bi-arrow-up"></i> +15%
                    </span>
                    <small class="text-muted fw-semibold">Cette semaine</small>
                </div>
                <div class="progress-line">
                    <div class="progress-fill bg-primary" style="width: 75%"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-box">
                <div class="icon-box bg-success bg-gradient">
                    <i class="bi bi-person-check-fill fs-3 text-white"></i>
                </div>
                <h3 class="stat-num">{{ number_format($activeUsers ?? 1) }}</h3>
                <p class="stat-text mb-3">Utilisateurs Actifs</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-info text-white px-3 py-2">
                        <i class="bi bi-calendar-check"></i> Ce mois
                    </span>
                    <small class="text-muted fw-semibold">En ligne</small>
                </div>
                <div class="progress-line">
                    <div class="progress-fill bg-success" style="width: 85%"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-box">
                <div class="icon-box bg-info bg-gradient">
                    <i class="bi bi-activity fs-3 text-white"></i>
                </div>
                <h3 class="stat-num">{{ number_format($totalActivities ?? 4) }}</h3>
                <p class="stat-text mb-3">Activités</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-warning text-dark px-3 py-2">
                        <i class="bi bi-collection"></i> 8 types
                    </span>
                    <small class="text-muted fw-semibold">Catégories</small>
                </div>
                <div class="progress-line">
                    <div class="progress-fill bg-info" style="width: 65%"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-box">
                <div class="icon-box bg-warning bg-gradient">
                    <i class="bi bi-people fs-3 text-white"></i>
                </div>
                <h3 class="stat-num">{{ number_format($totalPartners ?? 7) }}</h3>
                <p class="stat-text mb-3">Partenaires</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-primary text-white px-3 py-2">
                        <i class="bi bi-check-circle"></i> Actifs
                    </span>
                    <small class="text-muted fw-semibold">Vérifiés</small>
                </div>
                <div class="progress-line">
                    <div class="progress-fill bg-warning" style="width: 90%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="chart-box">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="section-header-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        <i class="bi bi-graph-up-arrow fs-5 text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1 fw-bold" style="color: #1e293b;">Évolution des Inscriptions</h5>
                        <small class="text-muted fw-medium">Tendance sur 12 mois</small>
                    </div>
                    <div class="btn-group btn-group-sm shadow-sm">
                        <button type="button" class="btn btn-outline-primary active">Mois</button>
                        <button type="button" class="btn btn-outline-primary">Semaine</button>
                    </div>
                </div>
                <div class="chart-area">
                    <canvas id="userChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="chart-box">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="section-header-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="bi bi-pie-chart-fill fs-5 text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold" style="color: #1e293b;">Types de Partenaires</h5>
                        <small class="text-muted fw-medium">Répartition par catégorie</small>
                    </div>
                </div>
                <div class="chart-area">
                    <canvas id="partnerChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="chart-box">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="section-header-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="bi bi-clock-history fs-5 text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1 fw-bold" style="color: #1e293b;">Activités Récentes</h5>
                        <small class="text-muted fw-medium">Dernières activités créées</small>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #3b82f6, #2563eb); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                        {{ $recentActivities->count() ?? 0 }}
                    </span>
                </div>
                
                <div class="list-box">
                    @php
                        $activityGradients = [
                            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                            'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                            'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                            'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                            'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                            'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)'
                        ];
                        $activityIcons = ['activity', 'bicycle', 'heart-pulse', 'water', 'person-arms-up', 'fire'];
                    @endphp
                    
                    @forelse($recentActivities ?? [] as $index => $activity)
                    <div class="item-row" style="animation: fadeInUp 0.5s ease-out {{ $index * 0.1 }}s both;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="activity-icon-wrapper" style="background: {{ $activityGradients[$index % 6] }};">
                                <i class="bi bi-{{ $activityIcons[$index % 6] }} text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold" style="color: #1e293b;">{{ $activity->title ?? $activity->name }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-tag me-1"></i>
                                    {{ $activity->category->name ?? 'Sans catégorie' }}
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge rounded-pill mb-1" style="background: {{ $activityGradients[$index % 6] }}; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                    Nouveau
                                </span>
                                <small class="text-muted d-block fw-medium">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <div class="empty-state-icon">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                        </div>
                        <p class="text-muted mb-0 fw-semibold">Aucune activité récente</p>
                        <small class="text-muted">Les nouvelles activités apparaîtront ici</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="chart-box">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="section-header-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <i class="bi bi-person-plus-fill fs-5 text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1 fw-bold" style="color: #1e293b;">Nouveaux Utilisateurs</h5>
                        <small class="text-muted fw-medium">Dernières inscriptions</small>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);">
                        {{ $recentUsers->count() ?? 0 }}
                    </span>
                </div>
                
                <div class="list-box">
                    @php
                        $userGradients = [
                            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                            'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                            'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                            'linear-gradient(135deg, #ec4899 0%, #db2777 100%)',
                            'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                            'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'
                        ];
                    @endphp
                    
                    @forelse($recentUsers ?? [] as $index => $user)
                    <div class="item-row" style="animation: fadeInUp 0.5s ease-out {{ $index * 0.1 }}s both;">
                        <div class="d-flex align-items-center gap-3">
                            @if($user->profile_image)
                            <div class="user-avatar-wrapper">
                                <img src="{{ asset('storage/' . $user->profile_image) }}" 
                                     alt="{{ $user->name }}" 
                                     class="user-avatar">
                            </div>
                            @else
                            <div class="user-icon-wrapper" style="background: {{ $userGradients[$index % 6] }};">
                                <span class="fw-bold text-white">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold" style="color: #1e293b;">{{ $user->name }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-envelope me-1"></i>
                                    {{ Str::limit($user->email, 25) }}
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary rounded-pill mb-1">
                                    @if($user->role ?? 'user' === 'admin')
                                        <i class="bi bi-shield-check me-1"></i>Admin
                                    @else
                                        <i class="bi bi-person-check me-1"></i>Membre
                                    @endif
                                </span>
                                <small class="text-muted d-block">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <div class="icon-box bg-light mx-auto mb-3" style="width: 64px; height: 64px;">
                            <i class="bi bi-people fs-3 text-muted"></i>
                        </div>
                        <p class="text-muted mb-0 fw-semibold">Aucun nouvel utilisateur</p>
                        <small class="text-muted">Les nouvelles inscriptions apparaîtront ici</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3 col-md-6">
            <div class="mini-box">
                <div class="mini-stat-icon mx-auto" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <i class="bi bi-bullseye fs-3 text-white"></i>
                </div>
                <h3 class="stat-num" style="font-size: 2rem;">24</h3>
                <p class="stat-text mb-0">Objectifs Définis</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="mini-box">
                <div class="mini-stat-icon mx-auto" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                    <i class="bi bi-heart-pulse-fill fs-3 text-white"></i>
                </div>
                <h3 class="stat-num" style="font-size: 2rem;">15</h3>
                <p class="stat-text mb-0">Maladies Répertoriées</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="mini-box">
                <div class="mini-stat-icon mx-auto" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="bi bi-flag-fill fs-3 text-white"></i>
                </div>
                <h3 class="stat-num" style="font-size: 2rem;">89</h3>
                <p class="stat-text mb-0">Objectifs Utilisateurs</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="mini-box">
                <div class="mini-stat-icon mx-auto" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="bi bi-bookmark-star-fill fs-3 text-white"></i>
                </div>
                <h3 class="stat-num" style="font-size: 2rem;">42</h3>
                <p class="stat-text mb-0">Playlists Créées</p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size = 13;
    Chart.defaults.color = '#64748b';

    const userCtx = document.getElementById('userChart');
    if (userCtx) {
        const ctx = userCtx.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
        @php
            $months = $userGrowth->pluck('month')->toArray();
            $counts = $userGrowth->pluck('count')->toArray();
            
            if (empty($months)) {
                $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
                $counts = [0, 0, 0, 0, 0, 0];
            }
        @endphp

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Nouveaux Utilisateurs',
                    data: {!! json_encode($counts) !!},
                    backgroundColor: gradient,
                    borderColor: '#6366f1',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { weight: '600', size: 14 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        padding: 16,
                        titleFont: { size: 15, weight: 'bold' },
                        bodyFont: { size: 14 },
                        cornerRadius: 12
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.06)',
                            drawBorder: false
                        },
                        ticks: { padding: 12 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { padding: 12 }
                    }
                }
            }
        });
    }

    const partnerCtx = document.getElementById('partnerChart');
    if (partnerCtx) {
        @php
            $partnerTypes = $partnersByType->pluck('type')->toArray();
            $partnerCounts = $partnersByType->pluck('count')->toArray();
            
            if (empty($partnerTypes)) {
                $partnerTypes = ['Gym & Fitness', 'Nutrition', 'Coaching', 'Spa & Wellness', 'Autres'];
                $partnerCounts = [0, 0, 0, 0, 0];
            }
        @endphp
        
        new Chart(partnerCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($partnerTypes) !!},
                datasets: [{
                    data: {!! json_encode($partnerCounts) !!},
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.9)',
                        'rgba(16, 185, 129, 0.9)',
                        'rgba(59, 130, 246, 0.9)',
                        'rgba(245, 158, 11, 0.9)',
                        'rgba(139, 92, 246, 0.9)',
                        'rgba(236, 72, 153, 0.9)'
                    ],
                    borderColor: '#fff',
                    borderWidth: 4,
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 18,
                            font: { weight: '600', size: 13 },
                            color: '#1e293b'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        padding: 16,
                        titleFont: { size: 15, weight: 'bold' },
                        bodyFont: { size: 14 },
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                if (total === 0) return ' Aucune donnée';
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return ' ' + context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    setTimeout(function() {
        document.querySelectorAll('.progress-fill').forEach(function(bar) {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(function() {
                bar.style.width = width;
            }, 100);
        });
    }, 500);
});
</script>
@endpush
