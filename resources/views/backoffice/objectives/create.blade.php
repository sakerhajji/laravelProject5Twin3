@extends('layouts.app')

@section('title', 'Nouvel objectif type')

@section('content')
<div class="main-content">
    <div class="section-header"><h1>Nouvel objectif type</h1></div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.objectives.store') }}" method="post">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Titre</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Unité</label>
                        <input type="text" name="unit" class="form-control" placeholder="kg, h, km, kcal" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Valeur cible</label>
                        <input type="number" step="0.01" min="0" name="target_value" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Catégorie</label>
                        <select name="category" class="form-control" required>
                            <option value="activite">Activité</option>
                            <option value="nutrition">Nutrition</option>
                            <option value="sommeil">Sommeil</option>
                            <option value="sante">Santé générale</option>
                        </select>
                    </div>
                    <div class="form-group col-md-8">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <button class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('admin.objectives.index) }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection


