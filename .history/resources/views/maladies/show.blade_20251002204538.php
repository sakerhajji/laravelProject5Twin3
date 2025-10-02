@extends('layouts.app')

@section('title', 'Détails Maladie')

@section('content')
    <div class="container">
        <h1>Détails de la Maladie</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $maladie->nom }}</h5>
                <p><strong>Description:</strong> {{ $maladie->description ?? 'Non spécifiée' }}</p>
                <p><strong>Traitement:</strong> {{ $maladie->traitement ?? 'Non spécifié' }}</p>
                <p><strong>Prévention:</strong> {{ $maladie->prevention ?? 'Non spécifiée' }}</p>
                <p><strong>Status:</strong> {{ ucfirst($maladie->status) }}</p>

                <div class="mt-3">
                    <h6><strong>Asymptomes:</strong></h6>
                    @if($maladie->asymptomes->count() > 0)
                        @foreach($maladie->asymptomes as $asymptome)
                            <span class="badge bg-info me-1 mb-1">{{ $asymptome->nom }}</span>
                        @endforeach
                    @else
                        <p class="text-muted">Aucun asymptome associé à cette maladie.</p>
                    @endif
                </div>

                <a href="{{ route('maladies.index') }}" class="btn btn-primary mt-3">Retour à la liste</a>
            </div>
        </div>
    </div>
@endsection
