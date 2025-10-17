@extends('layouts.app')

@section('title', 'Profil Utilisateur')

@section('content')
<section class="section">
    <div class="section-header">
        <h1><i class="fas fa-user"></i> Profil de {{ $user->name }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></div>
            <div class="breadcrumb-item">Profil</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <!-- Informations de base -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations personnelles</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="avatar avatar-xl bg-primary text-white mb-3">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <h5>{{ $user->name }}</h5>
                            @if($user->role == 'superadmin')
                                <span class="badge badge-danger">Super Admin</span>
                            @elseif($user->role == 'admin')
                                <span class="badge badge-warning">Admin</span>
                            @else
                                <span class="badge badge-info">Utilisateur</span>
                            @endif
                            
                            @if(isset($user->status) && $user->status == 'active')
                                <span class="badge badge-success ml-1">Actif</span>
                            @elseif(isset($user->status) && $user->status == 'inactive')
                                <span class="badge badge-secondary ml-1">Inactif</span>
                            @endif
                        </div>

                        <table class="table table-bordered table-sm">
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            @if($user->phone)
                            <tr>
                                <td><strong>Téléphone</strong></td>
                                <td>{{ $user->phone }}</td>
                            </tr>
                            @endif
                            @if($user->birth_date)
                            <tr>
                                <td><strong>Date de naissance</strong></td>
                                <td>{{ $user->birth_date->format('d/m/Y') }}</td>
                            </tr>
                            @endif
                            @if($user->address)
                            <tr>
                                <td><strong>Adresse</strong></td>
                                <td>{{ $user->address }}</td>
                            </tr>
                            @endif
                            @if($user->city)
                            <tr>
                                <td><strong>Ville</strong></td>
                                <td>{{ $user->city }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Inscription</strong></td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dernière maj</strong></td>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Statistiques et activités -->
            <div class="col-md-8">
                <!-- Statistiques -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Repas</h4>
                                </div>
                                <div class="card-body">
                                    {{ isset($user->repas) ? $user->repas->count() : 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Badges</h4>
                                </div>
                                <div class="card-body">
                                    {{ isset($user->badges) ? $user->badges->count() : 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Favoris</h4>
                                </div>
                                <div class="card-body">
                                    {{ isset($user->favoritedPartners) ? $user->favoritedPartners->count() : 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Objectifs</h4>
                                </div>
                                <div class="card-body">
                                    {{ isset($user->objectives) ? $user->objectives->count() : 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Partenaires favoris -->
                @if(isset($user->favoritedPartners) && $user->favoritedPartners->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h4>Partenaires Favoris</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($user->favoritedPartners->take(6) as $partner)
                            <div class="col-md-4 mb-3">
                                <div class="border rounded p-2">
                                    <h6 class="mb-1">{{ $partner->name }}</h6>
                                    <small class="text-muted">{{ $partner->type ?? 'Type non défini' }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($user->favoritedPartners->count() > 6)
                        <p class="text-muted text-center">
                            Et {{ $user->favoritedPartners->count() - 6 }} autres...
                        </p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Objectifs -->
                @if(isset($user->objectives) && $user->objectives->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h4>Objectifs Assignés</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Objectif</th>
                                        <th>Status</th>
                                        <th>Assigné le</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->objectives->take(5) as $objective)
                                    <tr>
                                        <td>{{ $objective->title ?? 'Titre non défini' }}</td>
                                        <td>
                                            @if(isset($objective->pivot->status) && $objective->pivot->status == 'completed')
                                                <span class="badge badge-success">Terminé</span>
                                            @elseif(isset($objective->pivot->status) && $objective->pivot->status == 'in_progress')
                                                <span class="badge badge-warning">En cours</span>
                                            @else
                                                <span class="badge badge-secondary">Pas commencé</span>
                                            @endif
                                        </td>
                                        <td>{{ isset($objective->pivot->created_at) ? $objective->pivot->created_at->format('d/m/Y') : 'Date inconnue' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary mr-2">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning mr-2">
                                    @if(isset($user->status) && $user->status == 'active')
                                        <i class="fas fa-ban"></i> Désactiver
                                    @else
                                        <i class="fas fa-check"></i> Activer
                                    @endif
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


