@extends('layouts.app')

@section('title', 'Mes objectifs')

@section('content')
<div class="main-content">
        <div class="section-header">
        <h1>Mes objectifs</h1>
        <div class="section-header-button">
            @if(auth()->check() && in_array(auth()->user()->role, ['admin','superadmin']))
                <a href="{{ route('back.goals.create') }}" class="btn btn-primary">Nouvel objectif</a>
            @endif
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row">
        @forelse($goals as $goal)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">{{ $goal->title }}</h5>
                                <div class="text-muted small">Cible: {{ number_format($goal->target_value,2) }} {{ $goal->unit }}</div>
                            </div>
                            <span class="badge badge-{{ $goal->status === 'active' ? 'success' : ($goal->status === 'paused' ? 'warning' : 'secondary') }}">{{ ucfirst($goal->status) }}</span>
                        </div>

                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $goal->current_progress }}%" aria-valuenow="{{ $goal->current_progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 small text-muted">
                            <div>Progression</div>
                            <div>{{ $goal->current_progress }}%</div>
                        </div>

                        <form action="{{ route('back.goal-entries.store', $goal) }}" method="post" class="mt-3">
                            @csrf
                            <div class="form-row">
                                <div class="col-5">
                                    <input type="date" name="entry_date" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-4">
                                    <input type="number" step="0.01" min="0" name="value" class="form-control" placeholder="Valeur">
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-sm btn-primary btn-block">Ajouter</button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-3">
                            <a href="{{ route('back.goals.show', $goal) }}" class="btn btn-sm btn-primary">Voir</a>
                            @if(auth()->check() && in_array(auth()->user()->role, ['admin','superadmin']))
                                <a href="{{ route('back.goals.edit', $goal) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                <form action="{{ route('back.goals.destroy', $goal) }}" method="post" class="d-inline" onsubmit="return confirm('Supprimer cet objectif ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Aucun objectif pour le moment. Créez votre premier objectif.</div>
            </div>
        @endforelse
    </div>

    {{ $goals->links() }}
</div>
@endsection


