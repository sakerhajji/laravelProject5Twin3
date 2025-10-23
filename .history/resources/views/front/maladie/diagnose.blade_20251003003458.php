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
                    <form method="POST" action="{{ route('front.maladie.match') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="asymptome-search" class="form-label fw-semibold">
                                <i class="bi bi-search me-2"></i>Rechercher un symptôme
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control form-control-lg" 
                                       onkeyup="searchFunction()" 
                                       id="asymptome-search" 
                                       placeholder="Tapez un symptôme à rechercher..."
                                       style="border-left: none;">
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
                                             style="cursor: pointer; transition: all 0.3s ease;">
                                            <div class="card-body p-3">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           id="asymptome_{{ $asymptome->id }}"
                                                           name="asymptomes[]" 
                                                           value="{{ $asymptome->id }}"
                                                           {{ (is_array(request('asymptomes')) && in_array($asymptome->id, request('asymptomes'))) ? 'checked' : '' }}
                                                           onchange="updateSelectedCount()">
                                                    <label class="form-check-label fw-medium" 
                                                           for="asymptome_{{ $asymptome->id }}">
                                                        <i class="bi bi-thermometer-half me-2 text-warning"></i>
                                                        {{ $asymptome->nom }}
                                                    </label>
                                                </div>
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

            @if(isset($results) && count($results) > 0)
            <div class="mt-5">
                <div class="card shadow-lg border-0">
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
                                        @foreach($results as $result)
                                            <tr class="hover-row">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-virus me-3 text-danger" style="font-size: 1.2rem;"></i>
                                                        <div>
                                                            <h6 class="mb-0 fw-semibold">{{ $result['maladie']->nom }}</h6>
                                                            <small class="text-muted">{{ Str::limit($result['maladie']->description ?? '', 100) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                            <div class="progress-bar bg-info" 
                                                                 role="progressbar" 
                                                                 style="width: {{ $result['percentage'] }}%;"
                                                                 aria-valuenow="{{ $result['percentage'] }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-info text-dark fw-semibold">{{ $result['percentage'] }}%</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check mb-0">
                                                        <input type="radio" 
                                                               class="form-check-input" 
                                                               name="maladie_id" 
                                                               value="{{ $result['maladie']->id }}">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .asymptome-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        border-color: #667eea !important;
    }
    
    .asymptome-card input:checked + label + .form-check-label {
        color: #667eea;
        font-weight: 600;
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
    }

    function updateSelectedCount() {
        let checkedBoxes = document.querySelectorAll('input[name="asymptomes[]"]:checked');
        document.getElementById('selected-count').textContent = checkedBoxes.length;
    }

    // Initialize selected count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedCount();
        
        // Add click handlers for cards to toggle checkboxes
        document.querySelectorAll('.asymptome-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('input[type="checkbox"]')) {
                    let checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    updateSelectedCount();
                }
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