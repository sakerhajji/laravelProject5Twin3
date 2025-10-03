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

        <div id="js-error" class="alert alert-danger d-none"></div>
        <form id="maladieForm" action="{{ route('maladies.store') }}" method="POST" novalidate>
            @csrf
            <div class="form-group mb-2">
                <label for="nom">Nom</label>
                <input type="text" name="nom" class="form-control" id="nom" value="{{ old('nom') }}" required>
            </div>
            <div class="form-group mb-2">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" id="description">{{ old('description') }}</textarea>
            </div>
            <div class="form-group mb-2">
                <label for="traitement">Traitement</label>
                <textarea name="traitement" class="form-control">{{ old('traitement') }}</textarea>
            </div>
            <div class="form-group mb-2">
                <label for="prevention">Prévention</label>
                <textarea name="prevention" class="form-control">{{ old('prevention') }}</textarea>
            </div>

            <!-- Asymptomes Section -->
            <div class="form-group mb-2">
                <label>Asymptomes</label>
                <input type="text" class="form-control mb-2" onkeydown="serachFunction()" id="asymptome-search" placeholder="Rechercher un asymptome...">
                <div class="row" id="asymptomes-container">
                    @foreach($asymptomes as $asymptome)
                        <div class="col-md-4 col-sm-6 col-12 mb-2 asymptome-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="asymptome_{{ $asymptome->id }}"
                                       name="asymptome_ids[]" value="{{ $asymptome->id }}"
                                       {{ (is_array(old('asymptome_ids')) && in_array($asymptome->id, old('asymptome_ids'))) ? 'checked' : '' }}>
                                <label class="form-check-label" for="asymptome_{{ $asymptome->id }}">
                                    {{ $asymptome->nom }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
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
        function serachFunction() {
            let input = document.getElementById('asymptome-search').value.toLowerCase();
            let items = document.getElementsByClassName('asymptome-item');
            Array.from(items).forEach(function(item) {
                let text = item.textContent.toLowerCase();
                if (input === '' || text.includes(input)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        document.getElementById('maladieForm').addEventListener('submit', function(e) {
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
@endsection

