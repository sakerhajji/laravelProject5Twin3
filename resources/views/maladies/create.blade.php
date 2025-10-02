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
                <div class="d-flex mb-2">
                    <input
                        type="text"
                        placeholder="Rechercher un asymptome..."
                        class="form-control me-2"
                        id="search-input"
                        oninput="filterOptions()"
                    />
                    <select class="form-control me-2" id="asymptome-select" onchange="addSelectedAsymptome()">
                        <option value="">-- Sélectionner un asymptome --</option>
                        @foreach($asymptomes as $asymptome)
                            <option value="{{ $asymptome->id }}">{{ $asymptome->nom }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-success" id="add-selected" style="display: none;">Ajouter</button>
                </div>

                <!-- Selected asymptomes display -->
                <div id="selected-asymptomes" class="mt-2">
                    <!-- Selected asymptomes will appear here -->
                </div>

                <!-- Hidden inputs for form submission -->
                <div id="hidden-inputs"></div>
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
@endsection

@section('scripts')
    <script>
        // Store all asymptomes in a global variable
        const allAsymptomes = [
                @foreach($asymptomes as $asymptome)
            { id: "{{ $asymptome->id }}", text: "{{ addslashes($asymptome->nom) }}" },
            @endforeach
        ];

        // Empty array for create form (no existing asymptomes)
        let selectedAsymptomes = [];

        function updateSelectedDisplay() {
            const container = document.getElementById('selected-asymptomes');
            const hiddenContainer = document.getElementById('hidden-inputs');

            container.innerHTML = '';
            hiddenContainer.innerHTML = '';

            selectedAsymptomes.forEach((asymptome, index) => {
                const div = document.createElement('div');
                div.className = 'd-inline-block me-2 mb-1';
                div.innerHTML = `
                <span class="badge bg-info">
                    ${asymptome.text}
                    <button type="button" class="btn-close btn-close-white ms-1" onclick="removeAsymptome(${index})" style="font-size: 0.6em;"></button>
                </span>
            `;
                container.appendChild(div);

                // Add hidden input for form submission
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'asymptome_ids[]';
                input.value = asymptome.id;
                hiddenContainer.appendChild(input);
            });

            // Update disabled options in dropdown
            updateDropdownOptions();
        }

        function addSelectedAsymptome() {
            const select = document.getElementById('asymptome-select');
            const searchInput = document.getElementById('search-input');

            if (select.value) {
                const existing = selectedAsymptomes.find(a => a.id === select.value);
                if (!existing) {
                    const text = select.options[select.selectedIndex].text;
                    selectedAsymptomes.push({
                        id: select.value,
                        text: text
                    });
                    updateSelectedDisplay();
                    select.value = ''; // Clear selection
                    searchInput.value = ''; // Clear search
                }
            }
        }

        function removeAsymptome(index) {
            selectedAsymptomes.splice(index, 1);
            updateSelectedDisplay();
        }

        function updateDropdownOptions() {
            const select = document.getElementById('asymptome-select');
            const options = select.querySelectorAll('option');

            // Reset all options
            options.forEach(option => {
                option.disabled = false;
                option.style.display = '';
            });

            // Disable already selected options
            selectedAsymptomes.forEach(asymptome => {
                const option = select.querySelector(`option[value="${asymptome.id}"]`);
                if (option) option.disabled = true;
            });
        }

        // Filter options based on search
        function filterOptions() {
            const searchValue = document.getElementById('search-input').value.toLowerCase();
            const select = document.getElementById('asymptome-select');
            const options = select.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === '') return; // Skip default option

                const text = option.text.toLowerCase();
                if (text.includes(searchValue)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedDisplay();
        });
    </script>
@endsection
