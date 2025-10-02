<!-- resources/views/asymptomes/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Liste des Asymptômes</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('asymptomes.create') }}" class="btn btn-primary mb-3">Ajouter un asymptôme</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Maladies associées</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($asymptomes as $asymptome)
                <tr>
                    <td>{{ $asymptome->nom }}</td>
                    <td>{{ $asymptome->description }}</td>
                    <td>
                        @foreach($asymptome->maladies as $maladie)
                            <span class="badge bg-info">{{ $maladie->nom }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('asymptomes.show', $asymptome->id) }}" class="btn btn-sm btn-success">Voir</a>
                        <a href="{{ route('asymptomes.edit', $asymptome->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('asymptomes.destroy', $asymptome->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $asymptomes->links() }}
    </div>
@endsection
