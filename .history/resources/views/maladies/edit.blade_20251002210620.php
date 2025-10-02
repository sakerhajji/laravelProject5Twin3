@extends('layouts.app')

@section('title', 'Ajouter une Maladie')

@section('content')
<div class="container">
    <h1>Ajouter une Maladie</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('maladies.store') }}" method="POST">
        @csrf
        <div class="form-group mb-2">
            <label for="nom">Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
        </div>
        <div class="form-group mb-2">
            <label for="description">Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="form-group mb-2">
            <label for="traitement">Traitement</label>
            <textarea name="traitement" class="form-control">{{ old('traitement') }}</textarea>
        </div>
        <div class="form-group mb-2">
            <label for="prevention">Prévention</label>
            <textarea name="prevention" class="form-control">{{ old('prevention') }}</textarea>
        </div>

        <!-- Asymptomes Dynamic Section -->
        <div class="form-group mb-2">
            <label>Asymptomes</label>
            <div id="asymptomes-wrapper">
                <div class="input-group mb-2 asymptome-input">
                    <input type="text" name="asymptomes[]" class="form-control" placeholder="Entrez un asymptome" required>
                    <button type="button" class="btn btn-danger remove-asymptome">Supprimer</button>
                </div>
            </div>
            <button type="button" id="add-asymptome" class="btn btn-primary">Ajouter un autre asymptome</button>
        </div>

        <div class="form-group mb-2">
            <label for="status">Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="{{ route('maladies.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<script>
    document.getElementById('add-asymptome').addEventListener('click', function() {
        let wrapper = document.getElementById('asymptomes-wrapper');
        let newInput = document.createElement('div');
        newInput.classList.add('input-group', 'mb-2', 'asymptome-input');
        newInput.innerHTML = `
            <input type="text" name="asymptomes[]" class="form-control" placeholder="Entrez un asymptome" required>
            <button type="button" class="btn btn-danger remove-asymptome">Supprimer</button>
        `;
        wrapper.appendChild(newInput);
    });

    document.addEventListener('click', function(e) {
        if(e.target && e.target.classList.contains('remove-asymptome')) {
            e.target.parentElement.remove();
        }
    });
</script>
@endsection
