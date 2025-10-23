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

            @if(isset($results))
            <div class="mt-5">
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
                        <form method="POST" action="{{ route('front.maladie.save') }}">
                            @csrf
                            <input type="hidden" name="asymptomes" value="{{ implode(',', request('asymptomes', [])) }}">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60%;">Maladie</th>
                                            <th style="width: 25%;">Correspondance</th>
                                            <th style="width: 15%;">Sélection</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($results as $result)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-virus me-3 text-danger" style="font-size: 1.2rem;"></i>
                                                        <div>
                                                            <h6 class="mb-0 fw-semibold">{{ $result['maladie']->nom }}</h6>
                                                            @if(!empty($result['maladie']->description))
                                                                <small class="text-muted">{{ Str::limit($result['maladie']->description, 100) }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $result['percentage'] }}%;" aria-valuenow="{{ $result['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="badge bg-info text-dark fw-semibold">{{ $result['percentage'] }}%</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check mb-0">
                                                        <input type="radio" class="form-check-input" name="maladie_id" value="{{ $result['maladie']->id }}">
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-4">
                                                    <i class="bi bi-emoji-frown text-muted" style="font-size: 3rem;"></i>
                                                    <h5 class="mt-3 text-muted">Aucun résultat trouvé</h5>
                                                    <p class="text-muted">Veuillez sélectionner plus de symptômes ou modifier votre recherche.</p>
                                                </td>
                                            </tr>
                                        @endforelse
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
            @endif
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
        
        // Simulate AJAX call with actual data
        const selectedData = selectedSymptoms.map(id => {
            const card = document.querySelector(`[data-asymptome-id="${id}"]`);
            const name = card.querySelector('.card-label span').textContent;
            return { id: id, name: name };
        });
        
        // Simulate API response with mock data
        setTimeout(() => {
            // Mock results - replace this with actual API call
            const mockResults = [
                {
                    maladie: { id: 1, nom: 'Grippe', description: 'Maladie virale courante affectant le système respiratoire' },
                    percentage: 85
                },
                {
                    maladie: { id: 2, nom: 'Rhume', description: 'Infection virale des voies respiratoires supérieures' },
                    percentage: 72
                },
                {
                    maladie: { id: 3, nom: 'Migraine', description: 'Douleur intense au niveau de la tête' },
                    percentage: 65
                }
            ];
            
            populateResultsTable(mockResults);
            loadingSpinner.style.display = 'none';
            resultsContent.style.display = 'block';
            
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 1500);
    });

    function populateResultsTable(results) {
        const tbody = document.getElementById('results-table-body');
        tbody.innerHTML = '';
        
        if (results.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center py-4">
                        <i class="bi bi-emoji-frown text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">Aucun résultat trouvé</h5>
                        <p class="text-muted">Veuillez sélectionner plus de symptômes ou modifier votre recherche.</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        results.forEach((result, index) => {
            const row = document.createElement('tr');
            row.className = 'result-row hover-row';
            row.style.animationDelay = `${index * 0.1}s`;
            
            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-virus me-3 text-danger" style="font-size: 1.2rem;"></i>
                        <div>
                            <h6 class="mb-0 fw-semibold">${result.maladie.nom}</h6>
                            <small class="text-muted">${result.maladie.description ? result.maladie.description.substring(0, 100) + '...' : ''}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                            <div class="progress-bar bg-info" 
                                 role="progressbar" 
                                 style="width: ${result.percentage}%;"
                                 aria-valuenow="${result.percentage}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <span class="badge bg-info text-dark fw-semibold">${result.percentage}%</span>
                    </div>
                </td>
                <td>
                    <div class="form-check mb-0">
                        <input type="radio" 
                               class="form-check-input" 
                               name="maladie_id" 
                               value="${result.maladie.id}">
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