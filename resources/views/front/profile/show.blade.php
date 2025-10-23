@extends('layouts.front')

@section('title', 'Mon Profil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-user-circle text-primary me-2"></i>Mon Profil
                </h2>
                <a href="{{ route('front.profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>Modifier
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Informations personnelles -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Nom complet</label>
                            <p class="fs-5 mb-0">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="fs-5 mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Téléphone</label>
                            <p class="fs-5 mb-0">{{ $user->phone ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Date de naissance</label>
                            <p class="fs-5 mb-0">
                                @if($user->birth_date)
                                    {{ \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') }}
                                @else
                                    Non renseigné
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Ville</label>
                            <p class="fs-5 mb-0">{{ $user->city ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Rôle</label>
                            <span class="badge bg-info fs-6">{{ ucfirst($user->role) }}</span>
                        </div>
                        @if($user->address)
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted">Adresse</label>
                                <p class="fs-5 mb-0">{{ $user->address }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('front.profile.edit') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-edit me-2"></i>Modifier mes informations
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('front.profile.change-password') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-key me-2"></i>Changer le mot de passe
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('front.partners.favorites') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-heart me-2"></i>Mes partenaires favoris
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('front.objectives.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-target me-2"></i>Mes objectifs
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card text-center bg-light">
                        <div class="card-body">
                            <h4 class="text-primary">{{ $user->created_at->format('d/m/Y') }}</h4>
                            <p class="text-muted mb-0">Membre depuis</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center bg-light">
                        <div class="card-body">
                            <h4 class="text-success">{{ $user->updated_at->format('d/m/Y') }}</h4>
                            <p class="text-muted mb-0">Dernière mise à jour</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center bg-light">
                        <div class="card-body">
                            <h4 class="text-info">{{ ucfirst($user->role) }}</h4>
                            <p class="text-muted mb-0">Type de compte</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection