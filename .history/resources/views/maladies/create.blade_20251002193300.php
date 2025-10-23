@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Créer une Maladie</h2>

        <form action="{{ route('maladies.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="nom">Nom de la maladie</label>
                <input type="text" name="nom" class="form-control" id="nom" value="{{ old('nom') }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label>Asymptomes</label>
                <div id="asymptome-wrapper">
                    <!-- First select box -->
                    <div class="asymptome-select d-flex mb-2">
                        <select name="asymptome_ids[]" class="form-control asymptome-select2" required>
                            <option value="">-- Choisir un asymptome --</option>
                            @foreach($asymptomes as $asymptome)
                                <option value="{{ $asymptome->id }}">{{ $asymptome->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Add button always below -->
                <button type="button" class="btn btn-success mt-2" id="add-asymptome">Ajouter un asymptome</button>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection

@section('scripts')
    <!-- Select2 CSS & JS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 for all current select boxes
            $('.asymptome-select2').select2({
                width: '100%',
                placeholder: '-- Choisir un asymptome --',
                allowClear: true
            });

            const wrapper = document.getElementById('asymptome-wrapper');
            const addBtn = document.getElementById('add-asymptome');

            addBtn.addEventListener('click', function() {
                // Create new div for select + remove button
                const div = document.createElement('div');
                div.classList.add('asymptome-select', 'd-flex', 'mb-2');

                // Options HTML
                let options = `<option value="">-- Choisir un asymptome --</option>`;
                @foreach($asymptomes as $asymptome)
                    options += `<option value=\"{{ $asymptome->id }}\">{{ $asymptome->nom }}</option>`;
                @endforeach

                // Add select + remove button
                div.innerHTML = `
                    <select name=\"asymptome_ids[]\" class=\"form-control asymptome-select2\" required>
                        ${options}
                    </select>
                    <button type=\"button\" class=\"btn btn-danger ms-2 remove-btn\">Supprimer</button>
                `;

                wrapper.appendChild(div);
                // Initialize Select2 for the new select
                $(div).find('.asymptome-select2').select2({
                    width: '100%',
                    placeholder: '-- Choisir un asymptome --',
                    allowClear: true
                });
            });

            // Remove select
            wrapper.addEventListener('click', function(e) {
                if(e.target && e.target.classList.contains('remove-btn')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
@endsection
