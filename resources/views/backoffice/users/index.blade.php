@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<section class="section">
    <div class="section-header">
        <h1><i class="fas fa-users"></i> Gestion des Utilisateurs</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Utilisateurs</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Filtres et Recherche -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Filtres</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Nouvel Utilisateur
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.users.index') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Recherche</label>
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Nom ou email..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Rôle</label>
                                        <select name="role" class="form-control">
                                            <option value="">Tous les rôles</option>
                                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                            <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i> Filtrer
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">
                                            <i class="fas fa-times"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des Utilisateurs -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Liste des Utilisateurs ({{ $users->total() }})</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-md">
                                <thead>
                                    <tr>
                                        <th>Avatar</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Status</th>
                                        <th>Inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="avatar bg-primary text-white">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="font-weight-600">{{ $user->name }}</div>
                                            @if($user->city)
                                                <div class="text-muted small">{{ $user->city }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->role == 'superadmin')
                                                <span class="badge badge-danger">Super Admin</span>
                                            @elseif($user->role == 'admin')
                                                <span class="badge badge-warning">Admin</span>
                                            @else
                                                <span class="badge badge-info">Utilisateur</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->status == 'active')
                                                <span class="badge badge-success">Actif</span>
                                            @else
                                                <span class="badge badge-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">
                                                        <i class="fas fa-eye"></i> Voir
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">
                                                        <i class="fas fa-edit"></i> Modifier
                                                    </a>
                                                    @if($user->id !== auth()->id())
                                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item">
                                                                @if($user->status == 'active')
                                                                    <i class="fas fa-ban"></i> Désactiver
                                                                @else
                                                                    <i class="fas fa-check"></i> Activer
                                                                @endif
                                                            </button>
                                                        </form>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Aucun utilisateur trouvé</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($users->hasPages())
                    <div class="card-footer">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection