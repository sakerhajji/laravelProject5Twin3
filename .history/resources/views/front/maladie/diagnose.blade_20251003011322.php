@extends('layouts.front')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clipboard-pulse me-3" style="font-size: 1.8rem;"></i>
                        <div>
                            <h2 class="mb-0 fw-bold">Diagnose Maladies</h2>
                            <p class="mb-0 opacity-75">Sélectionnez vos symptômes pour une analyse précise</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form id="diagnose-form" method="POST" action="{{ route('front.maladie.match') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="asymptome-search" class="form-label fw-semibold">
                                <i class="bi bi-search me-2"></i>Rechercher un symptôme
                            </label>
                            <div class="position-relative">
                                <input type="text" 
                                       class="form-control form-control-lg ps-5" 
                                       onkeyup="searchFunction()" 
                                       id="asymptome-search" 
                                       placeholder="🔍 Tapez un symptôme à rechercher..."
                                       style="border-radius: 50px; border: 2px solid #e9ecef; padding-left: 2rem;">
                                <div class="position-absolute start-0 top-50 translate-middle-y ms-4">
                                    <i class="bi bi-search text-muted" style="font-size: 1.2rem;"></i>
                                </div>
                                <button type="button" 
                                        class="position-absolute end-0 top-50 translate-middle-y me-3 btn btn-outline-secondary btn-sm"
                                        onclick="clearSearch()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="bi bi-list-check me-2"></i>Symptômes Disponibles
                                </h5>
                                <div class="text-muted small">
                                    <span id="selected-count">0</span> sélectionné(s)
                                </div>
                            </div>
                            
                            <div class="row g-3" id="asymptomes-container">
                                @foreach($asymptomes as $asymptome)
                                    <div class="col-md-4 col-sm-6 col-12 asymptome-item">
                                        <div class="card h-100 border-0 shadow-sm asymptome-card" 
                                             style="cursor: pointer; transition: all 0.3s ease;"
                                             data-asymptome-id="{{ $asymptome->id }}">
                                            <div class="card-body p-3">
                                                <input type="checkbox" 
                                                       class="d-none" 
                                                       id="asymptome_{{ $asymptome->id }}"
                                                       name="asymptomes[]" 
                                                       value="{{ $asymptome->id }}"
                                                       {{ (is_array(request('asymptomes')) && in_array($asymptome->id, request('asymptomes'))) ? 'checked' : '' }}>
                                                <label class="card-label mb-0" for="asymptome_{{ $asymptome->id }}">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-thermometer-half me-2 text-warning"></i>
                                                        <span class="fw-medium">{{ $asymptome->nom }}</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-search-heart me-2"></i>Trouver les Maladies
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results container -->
            <div id="results-container" class="mt-5" style="display: none;">
                <div class="card shadow-lg border-0 animate__animated animate__fadeInDown">
                    <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-heart-pulse me-3" style="font-size: 1.8rem;"></i>
                            <div>
                                <h3 class="mb-0 fw-bold">Résultats de Diagnostic</h3>
                                <p class="mb-0 opacity-75">Maladies possibles basées sur vos symptômes</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="loading-spinner text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Analyse en cours...</p>
                        </div>
                        
                        <div id="results-content" style="display: none;">
                            <form method="POST" action="{{ route('front.maladie.save') }}">
                                @csrf
                                <input type="hidden" name="asymptomes" id="asymptomes-input" value="">
                                
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 60%;">Maladie</th>
                                                <th style="width: 25%;">Correspondance</th>
                                                <th style="width: 15%;">Sélection</th>
                                            </tr>
                                        </thead>
                                        <tbody id="results-table-body">
                                            <!-- Results will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="bi bi-save me-2"></i>Enregistrer dans l'Histoire
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .asymptome-card {
        border: 2px solid #e9ecef !important;
        transition: all 0.3s ease;
    }
    
    .asymptome-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        border-color: #667eea !important;
    }
    
    .asymptome-card.selected {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border-color: #667eea !important;
    }
    
    .asymptome-card.selected .bi-thermometer-half {
        color: white !important;
    }
    
    .asymptome-card.selected .card-label {
        color: white !important;
    }
    
    .hover-row:hover {
        background-color: #f8f9fa !important;
    }
    
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 500;
    }
    
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .card-header {
        border-radius: 15px 15px 0 0 !important;
    }
    
    .card-label {
        cursor: pointer;
        margin-bottom: 0 !important;
    }
    
    /* Animation styles */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate__animated {
        animation-duration: 0.5s;
        animation-fill-mode: both;
    }
    
    .animate__fadeInDown {
        animation-name: fadeInDown;
    }
    
    .result-row {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .result-row:nth-child(1) { animation-delay: 0.1s; }
    .result-row:nth-child(2) { animation-delay: 0.2s; }
    .result-row:nth-child(3) { animation-delay: 0.3s; }
    .result-row:nth-child(4) { animation-delay: 0.4s; }
    .result-row:nth-child(5) { animation-delay: 0.5s; }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
        100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
    }
    
    /* Enhanced search bar styles */
    #asymptome-search {
        transition: all 0.3s ease;
        background-color: #fff;
    }
    
    #asymptome-search:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        background-color: #fff;
    }
    
    .search-container {
        position: relative;
    }
    
    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .clear-btn {
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
    }
    
    .clear-btn:hover {
        color: #dc3545;
    }
    
    /* Glassmorphism effect for search bar */
    .search-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
        border-radius: 50px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: -1;
    }
</style>

<script>
    function searchFunction() {
        let input = document.getElementById('asymptome-search').value.toLowerCase();
        let items = document.getElementsByClassName('asymptome-item');
        
        Array.from(items).forEach(function(item) {
            let text = item.textContent.toLowerCase();
            if (input === '' || text.includes(input)) {
                item.style.display = 'block';
                item.classList.remove('d-none');
            } else {
                item.style.display = 'none';
                item.classList.add('d-none');
            }
        });
        
        updateSelectedCount();
        
        // Show/hide clear button
        const clearBtn = document.querySelector('.clear-btn');
        if (input) {
            clearBtn.style.display = 'inline-block';
        } else {
            clearBtn.style.display = 'none';
        }
    }

    function clearSearch() {
        document.getElementById('asymptome-search').value = '';
        searchFunction();
        document.getElementById('asymptome-search').focus();
    }

    function updateSelectedCount() {
        let selectedCards = document.querySelectorAll('.asymptome-card.selected');
        document.getElementById('selected-count').textContent = selectedCards.length;
    }

    // Handle form submission with AJAX
    document.getElementById('diagnose-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = e.target;
        const selectedSymptoms = Array.from(document.querySelectorAll('input[name="asymptomes[]"]:checked'))
                                    .map(checkbox => checkbox.value);
        
        if (selectedSymptoms.length === 0) {
            alert('Veuillez sélectionner au moins un symptôme.');
            return;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Analyse...';
        submitBtn.disabled = true;
        
        const loadingSpinner = document.querySelector('.loading-spinner');
        const resultsContainer = document.getElementById('results-container');
        const resultsContent = document.getElementById('results-content');
        const resultsTableBody = document.getElementById('results-table-body');
        
        // Show results container
        resultsContainer.style.display = 'block';
        resultsContent.style.display = 'none';
        loadingSpinner.style.display = 'block';
        
        // Scroll to results
        resultsContainer.scrollIntoView({ behavior: 'smooth' });
        
        // Get selected symptoms for the input
        const asymptomesInput = document.getElementById('asymptomes-input');
        asymptomesInput.value = selectedSymptoms.join(',');
        
        // Create JSON payload
        const payload = {
            _token: document.querySelector('input[name="_token"]').value,
            asymptomes: selectedSymptoms
        };
        
        // Make actual AJAX call with JSON
        fetch(form.action, {
            method: 'POST',
            body: JSON.stringify(payload),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('API Response:', data); // Debug log
            if (data.results && data.results.length > 0) {
                populateResultsTable(data.results);
            } else {
                <!-- Real-time Results Table -->
                <div id="results-table-container" class="mt-5" style="display:none;">
                    <div class="card shadow-lg border-0 animate__animated animate__fadeInDown">
                        <div class="card-header bg-gradient text-white">
                            <h3 class="mb-0 fw-bold">Résultats de Diagnostic</h3>
                        </div>
                        <div class="card-body p-4">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Maladie</th>
                                        <th>Correspondance</th>
                                    </tr>
                                </thead>
                                <tbody id="results-table-body">
                                    <!-- JS will fill this -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

    <script>
    function getSelectedAsymptomes() {
        return Array.from(document.querySelectorAll('input[name="asymptomes[]"]:checked')).map(cb => cb.value);
    }

    function updateResultsTable(results) {
        const container = document.getElementById('results-table-container');
        const tbody = document.getElementById('results-table-body');
        tbody.innerHTML = '';
        if (results.length === 0) {
            container.style.display = 'none';
            return;
        }
        container.style.display = 'block';
        results.forEach(result => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${result.maladie.nom}</td>
                <td><span class="badge bg-info text-dark fw-semibold">${result.percentage}%</span></td>
            `;
            tbody.appendChild(row);
        });
    }

    function fetchResults() {
        const asymptomes = getSelectedAsymptomes();
        if (asymptomes.length === 0) {
            updateResultsTable([]);
            return;
        }
        fetch("{{ route('front.maladie.apiMatch') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ asymptomes })
        })
        .then(response => response.json())
        .then(data => {
            updateResultsTable(data.results);
        })
        .catch(() => {
            updateResultsTable([]);
        });
    }

    // Listen for changes
    document.querySelectorAll('input[name="asymptomes[]"]').forEach(cb => {
        cb.addEventListener('change', fetchResults);
    });

    // Optionally, fetch on page load if any are checked
    document.addEventListener('DOMContentLoaded', fetchResults);
    </script>
            return;
        }
        fetch("{{ route('front.maladie.apiMatch') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ asymptomes })
        })
        .then(response => response.json())
        .then(data => {
            updateResultsTable(data.results);
        })
        .catch(() => {
            updateResultsTable([]);
        });
    }

    // Listen for changes
    document.querySelectorAll('input[name="asymptomes[]"]').forEach(cb => {
        cb.addEventListener('change', fetchResults);
    });

    // Optionally, fetch on page load if any are checked
    document.addEventListener('DOMContentLoaded', fetchResults);
    </script>
            const maladie = result.maladie || result['maladie'];
            const percentage = result.percentage || result['percentage'];
            
            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-virus me-3 text-danger" style="font-size: 1.2rem;"></i>
                        <div>
                            <h6 class="mb-0 fw-semibold">${maladie.nom}</h6>
                            <small class="text-muted">${maladie.description ? maladie.description.substring(0, 100) + '...' : ''}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                            <div class="progress-bar bg-info" 
                                 role="progressbar" 
                                 style="width: ${percentage}%;"
                                 aria-valuenow="${percentage}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <span class="badge bg-info text-dark fw-semibold">${percentage}%</span>
                    </div>
                </td>
                <td>
                    <div class="form-check mb-0">
                        <input type="radio" 
                               class="form-check-input" 
                               name="maladie_id" 
                               value="${maladie.id}">
                    </div>
                </td>
            `;
            
            tbody.appendChild(row);
        });
    }

    // Initialize selected count and card states on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial selected states
        document.querySelectorAll('.asymptome-card').forEach(card => {
            let checkbox = card.querySelector('input[type="checkbox"]');
            if (checkbox.checked) {
                card.classList.add('selected');
            }
        });
        
        updateSelectedCount();
        
        // Add click handlers for cards
        document.querySelectorAll('.asymptome-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('input[type="checkbox"]')) {
                    let checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    
                    if (checkbox.checked) {
                        this.classList.add('selected');
                    } else {
                        this.classList.remove('selected');
                    }
                    
                    updateSelectedCount();
                }
            });
        });
        
        // Handle checkbox changes (for programmatic changes)
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let card = this.closest('.asymptome-card');
                if (this.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
                updateSelectedCount();
            });
        });
    });

    // Add smooth scrolling for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>
@endsection