@extends('layouts.app')

@section('title', 'Nouveau Partenaire')

@section('content')
<div class="main-content">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('admin.partners.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Nouveau Partenaire</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom du partenaire <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type <span class="text-danger">*</span></label>
                                        <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                            <option value="">Choisir un type</option>
                                            @foreach(\App\Models\Partner::getTypes() as $key => $value)
                                                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                                  rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Téléphone</label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                               value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Adresse</label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                                  rows="2">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ville</label>
                                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                               value="{{ old('city') }}">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Code postal</label>
                                        <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" 
                                               value="{{ old('postal_code') }}">
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Professional Information -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Site web</label>
                                        <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" 
                                               value="{{ old('website') }}" placeholder="https://example.com">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Numéro de licence</label>
                                        <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror" 
                                               value="{{ old('license_number') }}">
                                        @error('license_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Spécialisation</label>
                                        <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror" 
                                               value="{{ old('specialization') }}">
                                        @error('specialization')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Personne de contact</label>
                                        <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" 
                                               value="{{ old('contact_person') }}">
                                        @error('contact_person')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Statut <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                            @foreach(\App\Models\Partner::getStatuses() as $key => $value)
                                                <option value="{{ $key }}" {{ old('status', 'pending') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Logo</label>
                                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" 
                                               accept="image/*">
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Services -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Services proposés</label>
                                        <div id="services-container">
                                            @if(old('services'))
                                                @foreach(old('services') as $index => $service)
                                                    <div class="input-group mb-2 service-input">
                                                        <input type="text" name="services[]" class="form-control" value="{{ $service }}">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-danger remove-service" type="button">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="input-group mb-2 service-input">
                                                    <input type="text" name="services[]" class="form-control" placeholder="Ex: Consultation générale">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-danger remove-service" type="button">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-service">
                                            <i class="fas fa-plus"></i> Ajouter un service
                                        </button>
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Latitude</label>
                                        <input type="number" step="any" name="latitude" class="form-control @error('latitude') is-invalid @enderror" 
                                               value="{{ old('latitude') }}">
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Longitude</label>
                                        <input type="number" step="any" name="longitude" class="form-control @error('longitude') is-invalid @enderror" 
                                               value="{{ old('longitude') }}">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addServiceBtn = document.getElementById('add-service');
    const servicesContainer = document.getElementById('services-container');

    addServiceBtn.addEventListener('click', function() {
        const newServiceInput = `
            <div class="input-group mb-2 service-input">
                <input type="text" name="services[]" class="form-control" placeholder="Ex: Nouveau service">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger remove-service" type="button">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
        `;
        servicesContainer.insertAdjacentHTML('beforeend', newServiceInput);
    });

    servicesContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-service') || e.target.parentElement.classList.contains('remove-service')) {
            const serviceInput = e.target.closest('.service-input');
            if (servicesContainer.children.length > 1) {
                serviceInput.remove();
            }
        }
    });
});
</script>
@endpush