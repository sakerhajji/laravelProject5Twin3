@extends('layouts.app')

@section('title', 'Modifier Maladie')

@section('content')
    <div class="container">
        <h1>Modifier Maladie</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('maladies.update', $maladie->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-2">
                <label for="nom">Nom</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom', $maladie->nom) }}" required>
            </div>
            <div class="form-group mb-2">
                <label for="description">Description</label>
                <textarea name="description" class="form-control">{{ old('description', $maladie->description) }}</textarea>
            </div>

            <div class="form-group mb-2">
                <label for="traitement">Traitement</label>
                <textarea name="traitement" class="form-control">{{ old('traitement', $maladie->traitement) }}</textarea>
            </div>
            <div class="form-group mb-2">
                <label for="prevention">Prévention</label>
                <textarea name="prevention" class="form-control">{{ old('prevention', $maladie->prevention) }}</textarea>
            </div>

            <!-- Asymptomes Section -->
            <div class="form-group mb-2">
                <label>Asymptomes</label>
                <div id="asymptome-wrapper">
                    <!-- Existing asymptomes -->
                    @forelse($maladie->asymptomes as $asymptome)
                        <div class="asymptome-row d-flex mb-2">
                            <input
                                type="text"
                                placeholder="Rechercher un asymptome..."
                                class="form-control search-input me-2"
                                oninput="filterAsymptomeOptions(this)"
                                value="{{ $asymptome->nom }}"
                            />
                            <select name="asymptome_ids[]" class="form-control asymptome-select">
                                <option value="">-- Sélectionner un asymptome --</option>
                                @foreach($asymptomes as $a)
                                    <option value="{{ $a->id }}"
                                        {{ $a->id == $asymptome->id ? 'selected' : '' }}>
                                        {{ $a->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-danger ms-2 remove-btn">Supprimer</button>
                        </div>
                    @empty
                        <!-- First asymptome row (empty) -->
                        <div class="asymptome-row d-flex mb-2">
                            <input
                                type="text"
                                placeholder="Rechercher un asymptome..."
                                class="form-control search-input me-2"
                                oninput="filterAsymptomeOptions(this)"
                            />
                            <select name="asymptome_ids[]" class="form-control asymptome-select">
                                <option value="">-- Sélectionner un asymptome --</option>
                                @foreach($asymptomes as $a)
                                    <option value="{{ $a->id }}">{{ $a->nom }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-danger ms-2 remove-btn d-none">Supprimer</button>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-success mt-2" id="add-asymptome">Ajouter un asymptome</button>
            </div>

            <div class="form-group mb-2">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="active" {{ $maladie->status == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ $maladie->status == 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Mettre à jour</button>
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

        // Track selected asymptome IDs to prevent duplicates
        let selectedAsymptomeIds = [];

        // Update selected IDs array
        function updateSelectedAsymptomeIds() {
            const selects = document.querySelectorAll('select[name="asymptome_ids[]"]');
            selectedAsymptomeIds = Array.from(selects)
                .map(select => select.value)
                .filter(id => id !== '');
        }

        // Filter asymptome options based on search input
        function filterAsymptomeOptions(input) {
            const searchValue = input.value.toLowerCase();
            const select = input.nextElementSibling; // Get the associated select element

            // Show/hide options based on search
            const options = select.querySelectorAll('option[data-original]');
            options.forEach(option => {
                const text = option.text.toLowerCase();
                if (text.includes(searchValue)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        // Create a new asymptome row
        function createAsymptomeRow() {
            const rowDiv = document.createElement('div');
            rowDiv.className = 'asymptome-row d-flex mb-2';

            // Search input
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Rechercher un asymptome...';
            searchInput.className = 'form-control search-input me-2';
            searchInput.oninput = function() {
                filterAsymptomeOptions(this);
            };

            // Select dropdown
            const select = document.createElement('select');
            select.name = 'asymptome_ids[]';
            select.className = 'form-control asymptome-select';

            // Default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '-- Sélectionner un asymptome --';
            select.appendChild(defaultOption);

            // Add all asymptomes
            allAsymptomes.forEach(asymptome => {
                const option = document.createElement('option');
                option.value = asymptome.id;
                option.textContent = asymptome.text;
                option.setAttribute('data-original', 'true');
                select.appendChild(option);
            });

            // Remove button
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-danger ms-2 remove-btn';
            removeBtn.textContent = 'Supprimer';
            removeBtn.onclick = function() {
                this.parentElement.remove();
                updateSelectedAsymptomeIds();
            };

            // Append elements
            rowDiv.appendChild(searchInput);
            rowDiv.appendChild(select);
            rowDiv.appendChild(removeBtn);

            // Add change listener to handle duplicate prevention
            select.onchange = function() {
                updateSelectedAsymptomeIds();
                updateAsymptomeOptions();
            };

            return rowDiv;
        }

        // Update options in all select dropdowns (disable selected ones)
        function updateAsymptomeOptions() {
            const allSelects = document.querySelectorAll('select[name="asymptome_ids[]"]');

            allSelects.forEach(select => {
                const options = select.querySelectorAll('option[data-original]');
                options.forEach(option => {
                    if (selectedAsymptomeIds.includes(option.value) && option.value !== select.value) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            });
        }

        // Add asymptome button functionality
        document.getElementById('add-asymptome').addEventListener('click', function() {
            const wrapper = document.getElementById('asymptome-wrapper');
            const newRow = createAsymptomeRow();
            wrapper.appendChild(newRow);

            // Update selected IDs
            updateSelectedAsymptomeIds();
        });

        // Remove button functionality (for existing rows)
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-btn')) {
                e.target.parentElement.remove();
                updateSelectedAsymptomeIds();
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize existing rows
            const existingSelects = document.querySelectorAll('select[name="asymptome_ids[]"]');
            existingSelects.forEach(select => {
                // Mark original options
                const options = select.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value !== '') {
                        option.setAttribute('data-original', 'true');
                    }
                });

                // Add change listener
                select.onchange = function() {
                    updateSelectedAsymptomeIds();
                    updateAsymptomeOptions();
                };
            });

            // Update initial selected IDs
            updateSelectedAsymptomeIds();

            // Enable remove button for existing rows (except the first one if only one)
            const rows = document.querySelectorAll('.asymptome-row');
            if (rows.length > 1) {
                rows.forEach(row => {
                    const removeBtn = row.querySelector('.remove-btn');
                    removeBtn.classList.remove('d-none');
                });
            } else if (rows.length === 1) {
                const removeBtn = document.querySelector('.remove-btn');
                if (removeBtn) {
                    removeBtn.classList.add('d-none');
                }
            }
        });
    </script>

    <style>
        .search-input {
            width: 300px !important;
        }

        .asymptome-select {
            width: 300px !important;
        }

        .asymptome-row {
            position: relative;
        }
    </style>
@endsection
