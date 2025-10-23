<!-- resources/views/asymptomes/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Détails de l'asymptôme</h1>

        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title">{{ $asymptome->nom }}</h4>
                <p class="card-text"><strong>Description:</strong> {{ $asymptome->description ?? 'Aucune description' }}</p>
                <p class="card-text">
                    <strong>Maladies associées:</strong>
                    @if($asymptome->maladies->isEmpty())
                        Aucune
                    @else
                        @foreach($asymptome->maladies as $maladie)
                            <span class="badge bg-info">{{ $maladie->nom }}</span>
                        @endforeach
                    @endif
                </p>
                <a href="{{ route('asymptomes.edit', $asymptome->id) }}" class="btn btn-warning">Modifier</a>
                <a href="{{ route('asymptomes.index') }}" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
@endsection
