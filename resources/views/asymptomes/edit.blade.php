<!-- resources/views/asymptomes/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Modifier un asymptôme</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="js-error" class="alert alert-danger d-none"></div>
        <form id="asymptomeEditForm" action="{{ route('asymptomes.update', $asymptome->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT') <!-- important for updating -->

            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" id="nom" value="{{ old('nom', $asymptome->nom) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" id="description">{{ old('description', $asymptome->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('asymptomes.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
        <script>
        document.getElementById('asymptomeEditForm').addEventListener('submit', function(e) {
            var nom = document.getElementById('nom').value.trim();
            var description = document.getElementById('description').value.trim();
            var errorDiv = document.getElementById('js-error');
            var errors = [];
            if (nom.length < 2) {
                errors.push('Le nom doit contenir au moins 2 caractères.');
            }
            if (!description) {
                errors.push('La description est obligatoire.');
            }
            if (errors.length > 0) {
                e.preventDefault();
                errorDiv.innerHTML = errors.join('<br>');
                errorDiv.classList.remove('d-none');
            } else {
                errorDiv.classList.add('d-none');
            }
        });
        </script>
    </div>
@endsection
