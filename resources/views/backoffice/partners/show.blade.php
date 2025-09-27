@extends('layouts.app')

@section('title', 'Détails du Partenaire')

@section('content')
<div class="main-content">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('admin.partners.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{ $partner->name }}</h1>
        <div class="section-header-button">
            <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-warning">Modifier</a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if($partner->logo)
                                    <img src="{{ Storage::url($partner->logo) }}" class="img-fluid rounded mb-3" alt="{{ $partner->name }}">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                                        <i class="fas fa-building fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4>{{ $partner->name }}</h4>
                                <p class="text-muted">{{ $partner->type_label }}</p>
                                
                                @if($partner->specialization)
                                    <p><strong>Spécialisation:</strong> {{ $partner->specialization }}</p>
                                @endif

                                @if($partner->description)
                                    <p><strong>Description:</strong></p>
                                    <p>{{ $partner->description }}</p>
                                @endif

                                <div class="mb-3">
                                    <span class="badge badge-{{ $partner->status === 'active' ? 'success' : ($partner->status === 'pending' ? 'warning' : 'danger') }} p-2">
                                        {{ $partner->status_label }}
                                    </span>
                                </div>

                                @if($partner->rating > 0)
                                    <div class="mb-2">
                                        <strong>Note:</strong>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $partner->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ml-1">{{ number_format($partner->rating, 1) }}/5</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($partner->services && count($partner->services) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h4>Services proposés</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($partner->services as $service)
                                    <div class="col-md-6 mb-2">
                                        <span class="badge badge-outline-primary">{{ $service }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if($partner->opening_hours && count($partner->opening_hours) > 0)
                    <div class="card">
                        <div class="card-header">
                            <h4>Horaires d'ouverture</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($partner->opening_hours as $day => $hours)
                                    <div class="col-md-6 mb-2">
                                        <strong>{{ ucfirst($day) }}:</strong> {{ $hours }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations de contact</h4>
                    </div>
                    <div class="card-body">
                        @if($partner->email)
                            <div class="mb-3">
                                <strong>Email:</strong><br>
                                <a href="mailto:{{ $partner->email }}">{{ $partner->email }}</a>
                            </div>
                        @endif

                        @if($partner->phone)
                            <div class="mb-3">
                                <strong>Téléphone:</strong><br>
                                <a href="tel:{{ $partner->phone }}">{{ $partner->phone }}</a>
                            </div>
                        @endif

                        @if($partner->website)
                            <div class="mb-3">
                                <strong>Site web:</strong><br>
                                <a href="{{ $partner->website }}" target="_blank">{{ $partner->website }}</a>
                            </div>
                        @endif

                        @if($partner->address)
                            <div class="mb-3">
                                <strong>Adresse:</strong><br>
                                {{ $partner->address }}
                                @if($partner->city)
                                    <br>{{ $partner->city }}
                                    @if($partner->postal_code)
                                        {{ $partner->postal_code }}
                                    @endif
                                @endif
                            </div>
                        @endif

                        @if($partner->contact_person)
                            <div class="mb-3">
                                <strong>Personne de contact:</strong><br>
                                {{ $partner->contact_person }}
                            </div>
                        @endif

                        @if($partner->license_number)
                            <div class="mb-3">
                                <strong>Numéro de licence:</strong><br>
                                {{ $partner->license_number }}
                            </div>
                        @endif
                    </div>
                </div>

                @if($partner->latitude && $partner->longitude)
                    <div class="card">
                        <div class="card-header">
                            <h4>Localisation</h4>
                        </div>
                        <div class="card-body">
                            <p><strong>Coordonnées:</strong></p>
                            <p>Latitude: {{ $partner->latitude }}</p>
                            <p>Longitude: {{ $partner->longitude }}</p>
                            
                            <div id="map" style="height: 200px; width: 100%;"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($partner->latitude && $partner->longitude)
<script>
// Simple map placeholder - vous pouvez intégrer Google Maps ou OpenStreetMap ici
document.addEventListener('DOMContentLoaded', function() {
    const mapElement = document.getElementById('map');
    if (mapElement) {
        mapElement.innerHTML = `
            <div class="bg-light rounded d-flex align-items-center justify-content-center h-100">
                <div class="text-center">
                    <i class="fas fa-map-marked-alt fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Carte disponible</p>
                    <small class="text-muted">{{ $partner->latitude }}, {{ $partner->longitude }}</small>
                </div>
            </div>
        `;
    }
});
</script>
@endif
@endpush