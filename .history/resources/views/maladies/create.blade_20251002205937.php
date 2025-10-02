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
    // Helper function to calculate similarity
function similarity(s1, s2) {
    let longer = s1.length > s2.length ? s1 : s2;
    let shorter = s1.length > s2.length ? s2 : s1;
    let longerLength = longer.length;
    if (longerLength === 0) return 1.0;
    let distance = editDistance(longer, shorter);
    return (longerLength - distance) / longerLength;
}

// Levenshtein distance
function editDistance(s1, s2) {
    s1 = s1.toLowerCase();
    s2 = s2.toLowerCase();

    let costs = new Array();
    for (let i = 0; i <= s1.length; i++) {
        let lastValue = i;
        for (let j = 0; j <= s2.length; j++) {
            if (i === 0)
                costs[j] = j;
            else {
                if (j > 0) {
                    let newValue = costs[j - 1];
                    if (s1.charAt(i - 1) !== s2.charAt(j - 1))
                        newValue = Math.min(Math.min(newValue, lastValue), costs[j]) + 1;
                    costs[j - 1] = lastValue;
                    lastValue = newValue;
                }
            }
        }
        if (i > 0) costs[s2.length] = lastValue;
    }
    return costs[s2.length];
}

// Main search function
function searchFunction() {
    let input = document.getElementById('asymptome-search').value.toLowerCase();
    let items = document.getElementsByClassName('asymptome-item');
    const threshold = 0.5; // similarity threshold (0.0 to 1.0)

    Array.from(items).forEach(function(item) {
        let text = item.textContent.toLowerCase();
        if (text.includes(input) || similarity(text, input) >= threshold) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}
       
    </script>
@endsection

