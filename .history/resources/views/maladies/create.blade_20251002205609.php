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

            <!-- Asymptomes Section -->
            <div class="form-group mb-2">
                <label>Asymptomes</label>
                <input type="text" class="form-control mb-2" id="asymptome-search" placeholder="Rechercher un asymptome...">
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
        <button onclick="printheloo()">hello</button>
    </div>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('asymptome-search');
            searchInput.addEventListener('keydown', function() {
                // Use setTimeout to get the updated value after keydown
                setTimeout(() => {
                    const val = this.value.toLowerCase();
                    console.log('Search value:', val);
                    document.querySelectorAll('.asymptome-item').forEach(function(item) {
                        const label = item.querySelector('.form-check-label').textContent.toLowerCase();
                        console.log('Checking label:', label);
                        if(label.includes(val)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }, 0);
            });
        });
    </script>
@endsection

