@extends('layouts.app')

@section('title', 'Nouvel objectif')

@section('content')
<div class="main-content">
    <div class="section-header">
        <h1>Nouvel objectif</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('back.goals.store') }}" method="post">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Titre</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option value="custom">Personnalisé</option>
                            <option value="steps">Pas</option>
                            <option value="calories">Calories</option>
                            <option value="water">Eau</option>
                            <option value="sleep">Sommeil</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Unité</label>
                        <input type="text" name="unit" class="form-control" placeholder="ex: pas, kcal, L, h">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Valeur cible</label>
                        <input type="number" step="0.01" min="0" name="target_value" class="form-control" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Début</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Fin</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </div>

                <button class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('back.goals.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection


