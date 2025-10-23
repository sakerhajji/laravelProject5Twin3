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
                <input type="text" class="form-control mb-2" onkeydown="serachFunction()" id="asymptome-search" placeholder="Rechercher un asymptome...">
                <div class="row" id="asymptomes-container">
                    @foreach($asymptomes as $asymptome)
                        <div class="col-md-4 col-sm-6 col-12 mb-2 asymptome-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="asymptome_{{ $asymptome->id }}"
                                       name="asymptome_ids[]" value="{{ $asymptome->id }}"
                                       {{ (is_array(old('asymptome_ids', $maladie->asymptomes->pluck('id')->toArray())) && in_array($asymptome->id, old('asymptome_ids', $maladie->asymptomes->pluck('id')->toArray()))) ? 'checked' : '' }}>
                                <label class="form-check-label" for="asymptome_{{ $asymptome->id }}">
                                    {{ $asymptome->nom }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
@section('scripts')
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
    </script>
@endsection
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
