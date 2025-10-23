@extends('layouts.front')

@section('title', 'Objectifs disponibles')

@section('content')
<div class="container">
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    @isset($recommended)
    <div class="mb-4">
        <h4 class="fw-bold mb-3">Recommandés pour vous</h4>
        <div id="recCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($recommended as $idx=>$r)
                <div class="carousel-item {{ $idx===0?'active':'' }}">
                    <div class="card border-0 shadow-sm overflow-hidden">
                        @if($r->cover_url)
                            <img src="{{ $r->cover_url }}" class="w-100" style="height: 200px; object-fit: cover;" alt="rec">
                        @endif
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">{{ $r->title }}</h5>
                                <div class="text-secondary small">{{ Str::limit($r->description, 140) }}</div>
                            </div>
                            <form action="{{ route('front.objectives.activate', $r) }}" method="post">
                                @csrf
                                <button class="btn btn-primary"><i class="fa-solid fa-bullseye me-1"></i>Activer</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#recCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#recCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    @endisset
    <div class="row">
        @foreach($objectives as $o)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm overflow-hidden">
                    @if($o->cover_url)
                        <img src="{{ $o->cover_url }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $o->title }}">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="mb-1">{{ $o->title }}</h5>
                            <span class="badge bg-primary text-white">{{ ucfirst($o->category) }}</span>
                        </div>
                        <p class="text-secondary small">{{ Str::limit($o->description, 110) }}</p>
                        <div class="small text-muted">Cible: {{ number_format($o->target_value,2) }} {{ $o->unit }}</div>
                    </div>
                    <div class="card-footer bg-white d-flex gap-2">
                        <form action="{{ route('front.objectives.activate', $o) }}" method="post" class="mr-2">
                            @csrf
                            <button class="btn btn-sm btn-primary"><i class="fa-solid fa-bullseye me-1"></i>Activer</button>
                        </form>
                        <button class="btn btn-sm btn-success" data-bs-target="#progressModal" data-bs-toggle="modal">
                            <i class="fas fa-plus me-1"></i>Progrès
                        </button>
                        <a href="{{ route('front.progress.index') }}" class="btn btn-sm btn-outline-secondary">Mes progrès</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $objectives->links() }}
</div>

<!-- Modale Ajout Progrès -->
<div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="progressModalLabel">
                    <i class="fas fa-chart-line me-2"></i>
                    Ajouter un progrès
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="progressForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="objective_id" class="form-label">Objectif</label>
                                <select class="form-select" id="objective_id" name="objective_id" required>
                                    <option value="">Sélectionner un objectif</option>
                                    @foreach($objectives as $objective)
                                        <option value="{{ $objective->id }}" 
                                                data-unit="{{ $objective->unit }}"
                                                data-target="{{ $objective->target_value }}">
                                            {{ $objective->title }} ({{ $objective->unit }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="entry_date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="entry_date" name="entry_date" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="value" class="form-label">Valeur</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="value" name="value" 
                                           step="0.01" min="0" required>
                                    <span class="input-group-text" id="unitDisplay">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="note" class="form-label">Note (optionnel)</label>
                                <input type="text" class="form-control" id="note" name="note" 
                                       placeholder="Commentaire sur cette entrée...">
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Conseil:</strong> Saisissez régulièrement vos progrès pour un suivi optimal de vos objectifs.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Enregistrer le progrès
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Succès</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Progrès enregistré avec succès !
        </div>
    </div>
    
    <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Erreur</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Une erreur est survenue lors de l'enregistrement.
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du changement d'objectif
    document.getElementById('objective_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const unit = selectedOption.getAttribute('data-unit');
        const target = selectedOption.getAttribute('data-target');
        
        document.getElementById('unitDisplay').textContent = unit || '-';
        
        // Mise à jour du placeholder
        const valueInput = document.getElementById('value');
        if (target) {
            valueInput.placeholder = `Ex: ${target} ${unit}`;
        }
    });
    
    // Gestion du formulaire de progrès
    document.getElementById('progressForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const objectiveId = formData.get('objective_id');
        
        if (!objectiveId) {
            showToast('error', 'Veuillez sélectionner un objectif');
            return;
        }
        
        // Envoi AJAX
        fetch('{{ route("front.progress.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'Progrès enregistré avec succès !');
                document.getElementById('progressForm').reset();
                document.getElementById('unitDisplay').textContent = '-';
                bootstrap.Modal.getInstance(document.getElementById('progressModal')).hide();
            } else {
                showToast('error', data.message || 'Erreur lors de l\'enregistrement');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Erreur de connexion');
        });
    });
    
    // Fonction pour afficher les toasts
    function showToast(type, message) {
        const toastId = type === 'success' ? 'successToast' : 'errorToast';
        const toastElement = document.getElementById(toastId);
        const toastBody = toastElement.querySelector('.toast-body');
        
        toastBody.textContent = message;
        
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
    }
    
    // Bouton pour ouvrir la modale
    document.querySelectorAll('[data-bs-target="#progressModal"]').forEach(button => {
        button.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('progressModal'));
            modal.show();
        });
    });
});
</script>
@endpush


