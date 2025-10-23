@extends('layouts.app')

@section('title', 'Liste des Maladies')

@section('content')
    <div class="container">
        <h1>Liste des Maladies</h1>
        <a href="{{ route('maladies.create') }}" class="btn btn-primary mb-3">Ajouter une Maladie</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Asymptomes</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($maladies as $maladie)
                <tr>
                    <td>{{ $maladie->id }}</td>
                    <td>{{ $maladie->nom }}</td>
                    <td>
                        @if($maladie->asymptomes->count() > 0)
                            <span class="badge bg-secondary">{{ $maladie->asymptomes->count() }} asymptome(s)</span>
                            <div class="mt-1">
                                @foreach($maladie->asymptomes->take(3) as $asymptome) <!-- Show first 3 -->
                                <span class="badge bg-info me-1">{{ $asymptome->nom }}</span>
                                @endforeach
                                @if($maladie->asymptomes->count() > 3)
                                    <small class="text-muted">+{{ $maladie->asymptomes->count() - 3 }} autres</small>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">Aucun asymptome</span>
                        @endif
                    </td>
                    <td>{{ ucfirst($maladie->status) }}</td>
                    <td>
                        <a href="{{ route('maladies.show', $maladie->id) }}" class="btn btn-info btn-sm">Voir</a>
                        <a href="{{ route('maladies.edit', $maladie->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route('maladies.destroy', $maladie->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Êtes-vous sûr de supprimer cette maladie ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $maladies->links() }}
    </div>
@endsection
