@extends('layouts.app')

@section('title', 'Modifier objectif')

@section('content')
<div class="main-content">
    <div class="section-header">
        <h1>Modifier objectif</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('back.goals.update', $goal) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Titre</label>
                        <input type="text" name="title" class="form-control" value="{{ $goal->title }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            @foreach(['custom' => 'Personnalisé', 'steps' => 'Pas', 'calories' => 'Calories', 'water' => 'Eau', 'sleep' => 'Sommeil'] as $val => $label)
                                <option value="{{ $val }}" {{ $goal->type === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Unité</label>
                        <input type="text" name="unit" class="form-control" value="{{ $goal->unit }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Valeur cible</label>
                        <input type="number" step="0.01" min="0" name="target_value" class="form-control" value="{{ $goal->target_value }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Début</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $goal->start_date }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Fin</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $goal->end_date }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label>Statut</label>
                        <select name="status" class="form-control">
                            @foreach(['active' => 'Actif', 'paused' => 'En pause', 'completed' => 'Terminé'] as $val => $label)
                                <option value="{{ $val }}" {{ $goal->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('back.goals.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection


