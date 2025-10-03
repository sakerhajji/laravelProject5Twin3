<!-- resources/views/asymptomes/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Ajouter un asymptôme</h1>

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
        <form id="asymptomeForm" action="{{ route('asymptomes.store') }}" method="POST" novalidate>
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" id="nom" value="{{ old('nom') }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="{{ route('asymptomes.index') }}" class="btn btn-secondary">Retour</a>
        </form>
        <script>
        document.getElementById('asymptomeForm').addEventListener('submit', function(e) {
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
