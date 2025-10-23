@extends('layouts.app')

@section('title', 'Gestion des Aliments')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Aliments</h1>
        <a href="{{ route('admin.aliments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Créer un nouvel aliment
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
            <h5 class="mb-0">Liste des Aliments</h5>
            <form action="{{ route('admin.aliments.index') }}" method="GET" class="w-50">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher un aliment..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($aliments->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fas fa-carrot fa-3x mb-3"></i>
                    <p>Aucun aliment trouvé.</p>
                    @if(request('search'))
                        <p>Essayez une autre recherche ou <a href="{{ route('admin.aliments.index') }}">effacez le filtre</a>.</p>
                    @else
                        <p>Commencez par <a href="{{ route('admin.aliments.create') }}">créer un nouvel aliment</a>.</p>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th></th>
                                <th>Nom</th>
                                <th class="text-center">Calories</th>
                                <th class="text-center">Protéines</th>
                                <th class="text-center">Glucides</th>
                                <th class="text-center">Lipides</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aliments as $aliment)
                                <tr>
                                    <td>
                                        @if ($aliment->image_path)
                                            <img src="{{ asset('storage/' . $aliment->image_path) }}" alt="{{ $aliment->nom }}" class="rounded-circle" width="40" height="40">
                                        @else
                                            <div class="rounded-circle bg-secondary" style="width: 40px; height: 40px;"></div>
                                        @endif
                                    </td>
                                    <td>{{ $aliment->nom }}</td>
                                    <td class="text-center">{{ $aliment->calories }}</td>
                                    <td class="text-center">{{ $aliment->proteines }}g</td>
                                    <td class="text-center">{{ $aliment->glucides }}g</td>
                                    <td class="text-center">{{ $aliment->lipides }}g</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.aliments.edit', $aliment->id) }}" class="btn btn-sm btn-outline-primary mr-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.aliments.destroy', $aliment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet aliment ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $aliments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
