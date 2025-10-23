@extends('layouts.app')

@section('title', 'Gestion des Repas')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Repas</h1>
        <a href="{{ route('admin.repas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Créer un nouveau repas
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des Repas</h5>
            <form action="{{ route('admin.repas.index') }}" method="GET" class="w-50">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher un repas..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($repas->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fas fa-utensils fa-3x mb-3"></i>
                    <p>Aucun repas trouvé.</p>
                    @if(request('search'))
                        <p>Essayez une autre recherche ou <a href="{{ route('admin.repas.index') }}">effacez le filtre</a>.</p>
                    @else
                        <p>Commencez par <a href="{{ route('admin.repas.create') }}">créer un nouveau repas</a>.</p>
                    @endif
                </div>
            @else
                <div class="row">
                    @foreach ($repas as $repa)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-hover">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $repa->nom }}</h5>
                                    <p class="card-text text-muted flex-grow-1">{{ Str::limit($repa->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="text-muted small">
                                            <i class="fas fa-user mr-1"></i> {{ $repa->user->name }}
                                        </span>
                                        <span class="badge badge-light badge-pill">{{ $repa->aliments->count() }} aliments</span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0 d-flex justify-content-end">
                                    <a href="{{ route('admin.repas.show', $repa->id) }}" class="btn btn-sm btn-outline-info mr-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.repas.edit', $repa->id) }}" class="btn btn-sm btn-outline-primary mr-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.repas.destroy', $repa->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce repas ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $repas->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .shadow-hover {
        transition: box-shadow .3s ease-in-out;
    }
    .shadow-hover:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endpush
