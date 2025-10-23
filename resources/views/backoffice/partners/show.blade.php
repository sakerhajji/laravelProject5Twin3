@extends('layouts.app')

@section('title', 'Détails du Partenaire')

@section('content')
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
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                @if($partner->logo)
                                    <img src="{{ Storage::url($partner->logo) }}" class="img-fluid rounded-3 mb-3" alt="Logo {{ $partner->name }}">
                                @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
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
                                    <span class="badge bg-{{ $partner->status === 'active' ? 'success' : ($partner->status === 'pending' ? 'warning' : 'danger') }} p-2">
                                        {{ $partner->status_label }}
                                    </span>
                                </div>

                                @if($partner->rating > 0)
                                    <div class="mb-2">
                                        <strong>Note:</strong>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $partner->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ms-1">{{ number_format($partner->rating, 1) }}/5</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($partner->services && count($partner->services) > 0)
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h4 class="mb-0"><i class="fas fa-list-ul text-primary me-2"></i>Services proposés</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                @foreach($partner->services as $service)
                                    <div class="col-md-6 mb-2">
                                        <span class="badge bg-primary text-white p-2">
                                            <i class="fas fa-medical-kit me-1"></i>{{ $service }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if($partner->opening_hours && count($partner->opening_hours) > 0)
                    <div class="card shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><i class="fas fa-clock text-primary me-2"></i>Horaires d'ouverture</h4>
                            @php $currentStatus = $partner->current_day_status @endphp
                            <span class="badge bg-{{ $currentStatus['status'] === 'open' ? 'success' : ($currentStatus['status'] === 'break' ? 'warning' : 'secondary') }}">
                                {{ $currentStatus['message'] }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @php $formattedHours = $partner->formatted_opening_hours @endphp
                                @foreach($formattedHours as $day => $dayInfo)
                                    @php
                                        $isToday = strtolower(date('l')) === $day;
                                        $cardClass = $isToday ? 'border-primary bg-light' : '';
                                    @endphp
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card {{ $cardClass }} mb-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-1 {{ $isToday ? 'text-primary font-weight-bold' : '' }}">
                                                        {{ $dayInfo['label'] }}
                                                        @if($isToday)
                                                            <small class="badge badge-primary ml-1">Aujourd'hui</small>
                                                        @endif
                                                    </h6>
                                                    <span class="badge badge-{{ $dayInfo['is_open'] ? 'success' : 'secondary' }} badge-sm">
                                                        <i class="fas fa-{{ $dayInfo['is_open'] ? 'check' : 'times' }}"></i>
                                                    </span>
                                                </div>
                                                <p class="mb-0 small {{ $dayInfo['is_open'] ? 'text-success' : 'text-muted' }}">
                                                    {{ $dayInfo['hours'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="mb-0"><i class="fas fa-address-book text-primary me-2"></i>Informations de contact</h4>
                    </div>
                    <div class="card-body">
                        @if($partner->email)
                            <div class="mb-3">
                                <strong><i class="fas fa-envelope text-muted me-1"></i>Email:</strong><br>
                                <a href="mailto:{{ $partner->email }}" class="text-decoration-none">{{ $partner->email }}</a>
                            </div>
                        @endif

                        @if($partner->phone)
                            <div class="mb-3">
                                <strong><i class="fas fa-phone text-muted me-1"></i>Téléphone:</strong><br>
                                <a href="tel:{{ $partner->phone }}" class="text-decoration-none">{{ $partner->phone }}</a>
                            </div>
                        @endif

                        @if($partner->website)
                            <div class="mb-3">
                                <strong><i class="fas fa-globe text-muted me-1"></i>Site web:</strong><br>
                                <a href="{{ $partner->website }}" target="_blank" class="text-decoration-none">
                                    {{ $partner->website }} <i class="fas fa-external-link-alt ms-1 small"></i>
                                </a>
                            </div>
                        @endif

                        @if($partner->address)
                            <div class="mb-3">
                                <strong><i class="fas fa-map-marker-alt text-muted me-1"></i>Adresse:</strong><br>
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
                                <strong><i class="fas fa-user text-muted me-1"></i>Personne de contact:</strong><br>
                                {{ $partner->contact_person }}
                            </div>
                        @endif

                        @if($partner->license_number)
                            <div class="mb-3">
                                <strong><i class="fas fa-id-card text-muted me-1"></i>Numéro de licence:</strong><br>
                                {{ $partner->license_number }}
                            </div>
                        @endif
                    </div>
                </div>

                @if($partner->latitude && $partner->longitude)
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h4 class="mb-0"><i class="fas fa-map text-primary me-2"></i>Localisation</h4>
                        </div>
                        <div class="card-body">
                            <p><strong>Coordonnées:</strong></p>
                            <p class="small text-muted mb-1">Latitude: {{ $partner->latitude }}</p>
                            <p class="small text-muted mb-3">Longitude: {{ $partner->longitude }}</p>
                            
                            <div id="map" class="rounded" style="height: 200px; width: 100%;"></div>
                        </div>
                    </div>
                @endif
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