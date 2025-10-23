@extends('layouts.app')

@section('title', 'Gestion des Partenaires')

@section('content')
<div class="section-header">
    <h1>Gestion des Partenaires</h1>
    <div class="section-header-button">
        <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">Nouveau Partenaire</a>
    </div>
</div>
    
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <div class="alert-body">
                <button class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
            </div>
        </div>
    @endif

    <div class="section-body">
        <!-- Filters -->
        <div class="card shadow-sm rounded-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.partners.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">Tous les types</option>
                                @foreach(\App\Models\Partner::getTypes() as $key => $value)
                                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status" class="form-label">Statut</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                @foreach(\App\Models\Partner::getStatuses() as $key => $value)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Nom, email, ville..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div style="height: 32px;"></div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i>Filtrer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Partners list -->
        <div class="row">
            @forelse($partners as $partner)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($partner->logo)
                            <img src="{{ Storage::url($partner->logo) }}" alt="Logo {{ $partner->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-building fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $partner->name }}</h5>
                                <span class="badge bg-{{ $partner->status === 'active' ? 'success' : ($partner->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ $partner->status_label }}
                                </span>
                            </div>
                            
                            <span class="badge bg-info mb-2">{{ $partner->type_label }}</span>
                            
                            @if($partner->specialization)
                                <p class="text-muted small mb-2">{{ $partner->specialization }}</p>
                            @endif
                            
                            @if($partner->description)
                                <p class="card-text small">{{ Str::limit($partner->description, 100) }}</p>
                            @endif
                            
                            <div class="small text-muted">
                                @if($partner->city)
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $partner->city }}
                                @endif
                                
                                @if($partner->rating > 0)
                                    <br><i class="fas fa-star text-warning me-1"></i> {{ number_format($partner->rating, 1) }}/5
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <div class="btn-group w-100">
                                <a href="{{ route('admin.partners.show', $partner) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-{{ $partner->status === 'active' ? 'warning' : 'success' }}"
                                        onclick="toggleStatus({{ $partner->id }})">
                                    <i class="fas fa-{{ $partner->status === 'active' ? 'pause' : 'play' }}"></i>
                                </button>
                                <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce partenaire ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun partenaire trouvé</h5>
                            <p class="text-muted">Commencez par ajouter votre premier partenaire.</p>
                            <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">Ajouter un partenaire</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($partners->hasPages())
            <div class="d-flex justify-content-center">
                {{ $partners->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
function toggleStatus(partnerId) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut de ce partenaire ?')) {
        fetch(`/admin/partenaires/${partnerId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert('Erreur lors de la modification du statut');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la modification du statut');
        });
    }
}
</script>
@endpush