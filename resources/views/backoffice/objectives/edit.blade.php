@extends('layouts.app')

@section('title', 'Modifier objectif type')

@section('content')
<div class="main-content">
    <div class="section-header"><h1>Modifier objectif type</h1></div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.objectives.update', $objective) }}" method="post">
                @csrf @method('PUT')
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Titre</label>
                        <input type="text" name="title" class="form-control" value="{{ $objective->title }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Unité</label>
                        <input type="text" name="unit" class="form-control" value="{{ $objective->unit }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Valeur cible</label>
                        <input type="number" step="0.01" min="0" name="target_value" class="form-control" value="{{ $objective->target_value }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Catégorie</label>
                        <select name="category" class="form-control" required>
                            @foreach(['activite'=>'Activité','nutrition'=>'Nutrition','sommeil'=>'Sommeil','sante'=>'Santé générale'] as $val=>$label)
                                <option value="{{ $val }}" {{ $objective->category===$val?'selected':'' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-8">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control">{{ $objective->description }}</textarea>
                    </div>
                </div>
                <button class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.objectives.index) }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection


