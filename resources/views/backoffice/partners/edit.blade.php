@extends('layouts.app')

@section('title', 'Modifier le Partenaire')

@section('content')
<div class="section-header">
    <div class="section-header-back">
        <a href="{{ route('admin.partners.show', $partner) }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1>Modifier {{ $partner->name }}</h1>
</div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom du partenaire <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $partner->name) }}" required>
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
                                                <option value="{{ $key }}" {{ old('type', $partner->type) == $key ? 'selected' : '' }}>
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
                                                  rows="3">{{ old('description', $partner->description) }}</textarea>
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
                                               value="{{ old('email', $partner->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Téléphone</label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                               value="{{ old('phone', $partner->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Adresse</label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                                  rows="2">{{ old('address', $partner->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ville</label>
                                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                               value="{{ old('city', $partner->city) }}">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Code postal</label>
                                        <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" 
                                               value="{{ old('postal_code', $partner->postal_code) }}">
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
                                               value="{{ old('website', $partner->website) }}" placeholder="https://example.com">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Numéro de licence</label>
                                        <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror" 
                                               value="{{ old('license_number', $partner->license_number) }}">
                                        @error('license_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Spécialisation</label>
                                        <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror" 
                                               value="{{ old('specialization', $partner->specialization) }}">
                                        @error('specialization')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Personne de contact</label>
                                        <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" 
                                               value="{{ old('contact_person', $partner->contact_person) }}">
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
                                                <option value="{{ $key }}" {{ old('status', $partner->status) == $key ? 'selected' : '' }}>
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
                                        @if($partner->logo)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($partner->logo) }}" alt="Logo actuel" style="max-height: 100px;">
                                                <small class="text-muted d-block">Logo actuel</small>
                                            </div>
                                        @endif
                                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" 
                                               accept="image/*">
                                        <small class="form-text text-muted">Laisser vide pour conserver le logo actuel</small>
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
                                            @if(old('services', $partner->services))
                                                @foreach(old('services', $partner->services) as $index => $service)
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

                                <!-- Opening Hours -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label><i class="fas fa-clock text-primary"></i> Horaires d'ouverture</label>
                                        <div class="card">
                                            <div class="card-body">
                                @php
                                    $days = [
                                        'monday' => 'Lundi',
                                        'tuesday' => 'Mardi',
                                        'wednesday' => 'Mercredi',
                                        'thursday' => 'Jeudi',
                                        'friday' => 'Vendredi',
                                        'saturday' => 'Samedi',
                                        'sunday' => 'Dimanche'
                                    ];
                                    $dayMapping = [
                                        'lundi' => 'monday',
                                        'mardi' => 'tuesday',
                                        'mercredi' => 'wednesday',
                                        'jeudi' => 'thursday',
                                        'vendredi' => 'friday',
                                        'samedi' => 'saturday',
                                        'dimanche' => 'sunday'
                                    ];
                                    
                                    $currentHours = old('opening_hours', $partner->opening_hours ?? []);
                                    
                                    // Convert old format to new format if needed
                                    $processedHours = [];
                                    if (!empty($currentHours)) {
                                        foreach ($days as $englishDay => $frenchLabel) {
                                            // Check both English and French keys
                                            $dayData = null;
                                            $frenchKey = strtolower($frenchLabel);
                                            
                                            if (isset($currentHours[$englishDay])) {
                                                $dayData = $currentHours[$englishDay];
                                            } elseif (isset($currentHours[$frenchKey])) {
                                                $dayData = $currentHours[$frenchKey];
                                            }
                                            
                                            if ($dayData) {
                                                if (is_array($dayData)) {
                                                    // New format - keep as is
                                                    $processedHours[$englishDay] = $dayData;
                                                } else {
                                                    // Old format - convert
                                                    $dayData = trim($dayData);
                                                    if ($dayData === 'Fermé' || $dayData === 'Ferme' || empty($dayData)) {
                                                        $processedHours[$englishDay] = [
                                                            'is_open' => false,
                                                            'open_time' => '09:00',
                                                            'close_time' => '18:00',
                                                            'has_break' => false,
                                                            'break_start' => '12:00',
                                                            'break_end' => '13:00'
                                                        ];
                                                    } elseif (strpos($dayData, '-') !== false) {
                                                        // Format like "10:00-19:00"
                                                        $times = explode('-', $dayData);
                                                        $processedHours[$englishDay] = [
                                                            'is_open' => true,
                                                            'open_time' => trim($times[0]),
                                                            'close_time' => trim($times[1]),
                                                            'has_break' => false,
                                                            'break_start' => '12:00',
                                                            'break_end' => '13:00'
                                                        ];
                                                    }
                                                }
                                            } else {
                                                // Default values
                                                $processedHours[$englishDay] = [
                                                    'is_open' => false,
                                                    'open_time' => '09:00',
                                                    'close_time' => '18:00',
                                                    'has_break' => false,
                                                    'break_start' => '12:00',
                                                    'break_end' => '13:00'
                                                ];
                                            }
                                        }
                                    }
                                @endphp                                                <div class="row">
                                                    @foreach($days as $dayKey => $dayLabel)
                                                        <div class="col-md-6 col-lg-4 mb-3">
                                                            <div class="day-schedule border rounded p-3 bg-light">
                                                                <!-- Hidden inputs to ensure all data is sent -->
                                                                <input type="hidden" name="opening_hours[{{ $dayKey }}][day]" value="{{ $dayKey }}">
                                                                
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <label class="form-check-label font-weight-bold mb-0 mr-2">
                                                                        {{ $dayLabel }}
                                                                    </label>
                                                                    <div class="custom-control custom-switch">
                                                                        <input type="checkbox" class="custom-control-input day-toggle" 
                                                                               id="toggle-{{ $dayKey }}" name="opening_hours[{{ $dayKey }}][is_open]" 
                                                                               value="1" {{ isset($processedHours[$dayKey]['is_open']) && $processedHours[$dayKey]['is_open'] ? 'checked' : '' }}>
                                                                        <label class="custom-control-label" for="toggle-{{ $dayKey }}">
                                                                            <small class="text-muted">Ouvert</small>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="time-inputs" style="{{ isset($processedHours[$dayKey]['is_open']) && $processedHours[$dayKey]['is_open'] ? '' : 'display: none;' }}">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <label class="small text-muted">Ouverture</label>
                                                                            <input type="time" name="opening_hours[{{ $dayKey }}][open_time]" 
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $processedHours[$dayKey]['open_time'] ?? '09:00' }}">
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label class="small text-muted">Fermeture</label>
                                                                            <input type="time" name="opening_hours[{{ $dayKey }}][close_time]" 
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $processedHours[$dayKey]['close_time'] ?? '18:00' }}">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="mt-2">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input break-toggle" 
                                                                                   id="break-{{ $dayKey }}" name="opening_hours[{{ $dayKey }}][has_break]" 
                                                                                   value="1" {{ isset($processedHours[$dayKey]['has_break']) && $processedHours[$dayKey]['has_break'] ? 'checked' : '' }}>
                                                                            <label class="custom-control-label small text-muted" for="break-{{ $dayKey }}">
                                                                                Pause déjeuner
                                                                            </label>
                                                                        </div>
                                                                        
                                                                        <div class="break-times mt-1" style="{{ isset($processedHours[$dayKey]['has_break']) && $processedHours[$dayKey]['has_break'] ? '' : 'display: none;' }}">
                                                                            <div class="row">
                                                                                <div class="col-6">
                                                                                    <input type="time" name="opening_hours[{{ $dayKey }}][break_start]" 
                                                                                           class="form-control form-control-sm" placeholder="Début pause"
                                                                                           value="{{ $processedHours[$dayKey]['break_start'] ?? '12:00' }}">
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <input type="time" name="opening_hours[{{ $dayKey }}][break_end]" 
                                                                                           class="form-control form-control-sm" placeholder="Fin pause"
                                                                                           value="{{ $processedHours[$dayKey]['break_end'] ?? '13:00' }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                
                                                <div class="mt-3 text-center">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" id="copy-hours">
                                                        <i class="fas fa-copy"></i> Copier les horaires du lundi vers tous les jours
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary ml-2" id="weekdays-only">
                                                        <i class="fas fa-business-time"></i> Appliquer uniquement en semaine
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Latitude</label>
                                        <input type="number" step="any" name="latitude" class="form-control @error('latitude') is-invalid @enderror" 
                                               value="{{ old('latitude', $partner->latitude) }}">
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Longitude</label>
                                        <input type="number" step="any" name="longitude" class="form-control @error('longitude') is-invalid @enderror" 
                                               value="{{ old('longitude', $partner->longitude) }}">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                <a href="{{ route('admin.partners.show', $partner) }}" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Services functionality
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

    // Opening Hours functionality
    const dayToggles = document.querySelectorAll('.day-toggle');
    const breakToggles = document.querySelectorAll('.break-toggle');

    // Handle day toggle changes
    dayToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const timeInputs = this.closest('.day-schedule').querySelector('.time-inputs');
            if (this.checked) {
                timeInputs.style.display = '';
                // Enable time inputs
                timeInputs.querySelectorAll('input').forEach(input => input.disabled = false);
            } else {
                timeInputs.style.display = 'none';
                // Don't disable inputs, just hide them so they still submit
                // timeInputs.querySelectorAll('input').forEach(input => input.disabled = true);
            }
        });
    });

    // Handle break toggle changes
    breakToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const breakTimes = this.closest('.day-schedule').querySelector('.break-times');
            if (this.checked) {
                breakTimes.style.display = '';
                breakTimes.querySelectorAll('input').forEach(input => input.disabled = false);
            } else {
                breakTimes.style.display = 'none';
                // Don't disable inputs, just hide them so they still submit
                // breakTimes.querySelectorAll('input').forEach(input => input.disabled = true);
            }
        });
    });

    // Copy Monday's hours to all days
    document.getElementById('copy-hours').addEventListener('click', function() {
        const mondaySchedule = document.querySelector('[id="toggle-monday"]').closest('.day-schedule');
        const mondayData = {
            isOpen: mondaySchedule.querySelector('[name="opening_hours[monday][is_open]"]').checked,
            openTime: mondaySchedule.querySelector('[name="opening_hours[monday][open_time]"]').value,
            closeTime: mondaySchedule.querySelector('[name="opening_hours[monday][close_time]"]').value,
            hasBreak: mondaySchedule.querySelector('[name="opening_hours[monday][has_break]"]').checked,
            breakStart: mondaySchedule.querySelector('[name="opening_hours[monday][break_start]"]').value,
            breakEnd: mondaySchedule.querySelector('[name="opening_hours[monday][break_end]"]').value
        };

        const days = ['tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        days.forEach(day => {
            const daySchedule = document.querySelector(`[id="toggle-${day}"]`).closest('.day-schedule');
            
            // Set main toggle
            const dayToggle = daySchedule.querySelector(`[name="opening_hours[${day}][is_open]"]`);
            dayToggle.checked = mondayData.isOpen;
            dayToggle.dispatchEvent(new Event('change'));
            
            // Set times
            daySchedule.querySelector(`[name="opening_hours[${day}][open_time]"]`).value = mondayData.openTime;
            daySchedule.querySelector(`[name="opening_hours[${day}][close_time]"]`).value = mondayData.closeTime;
            
            // Set break
            const breakToggle = daySchedule.querySelector(`[name="opening_hours[${day}][has_break]"]`);
            breakToggle.checked = mondayData.hasBreak;
            breakToggle.dispatchEvent(new Event('change'));
            
            daySchedule.querySelector(`[name="opening_hours[${day}][break_start]"]`).value = mondayData.breakStart;
            daySchedule.querySelector(`[name="opening_hours[${day}][break_end]"]`).value = mondayData.breakEnd;
        });
        
        // Show success message
        showNotification('Horaires du lundi copiés vers tous les jours', 'success');
    });

    // Apply to weekdays only
    document.getElementById('weekdays-only').addEventListener('click', function() {
        const mondaySchedule = document.querySelector('[id="toggle-monday"]').closest('.day-schedule');
        const mondayData = {
            isOpen: mondaySchedule.querySelector('[name="opening_hours[monday][is_open]"]').checked,
            openTime: mondaySchedule.querySelector('[name="opening_hours[monday][open_time]"]').value,
            closeTime: mondaySchedule.querySelector('[name="opening_hours[monday][close_time]"]').value,
            hasBreak: mondaySchedule.querySelector('[name="opening_hours[monday][has_break]"]').checked,
            breakStart: mondaySchedule.querySelector('[name="opening_hours[monday][break_start]"]').value,
            breakEnd: mondaySchedule.querySelector('[name="opening_hours[monday][break_end]"]').value
        };

        const weekdays = ['tuesday', 'wednesday', 'thursday', 'friday'];
        weekdays.forEach(day => {
            const daySchedule = document.querySelector(`[id="toggle-${day}"]`).closest('.day-schedule');
            
            // Set main toggle
            const dayToggle = daySchedule.querySelector(`[name="opening_hours[${day}][is_open]"]`);
            dayToggle.checked = mondayData.isOpen;
            dayToggle.dispatchEvent(new Event('change'));
            
            // Set times
            daySchedule.querySelector(`[name="opening_hours[${day}][open_time]"]`).value = mondayData.openTime;
            daySchedule.querySelector(`[name="opening_hours[${day}][close_time]"]`).value = mondayData.closeTime;
            
            // Set break
            const breakToggle = daySchedule.querySelector(`[name="opening_hours[${day}][has_break]"]`);
            breakToggle.checked = mondayData.hasBreak;
            breakToggle.dispatchEvent(new Event('change'));
            
            daySchedule.querySelector(`[name="opening_hours[${day}][break_start]"]`).value = mondayData.breakStart;
            daySchedule.querySelector(`[name="opening_hours[${day}][break_end]"]`).value = mondayData.breakEnd;
        });
        
        // Close weekend
        ['saturday', 'sunday'].forEach(day => {
            const dayToggle = document.querySelector(`[name="opening_hours[${day}][is_open]"]`);
            dayToggle.checked = false;
            dayToggle.dispatchEvent(new Event('change'));
        });
        
        // Show success message
        showNotification('Horaires appliqués en semaine uniquement', 'success');
    });

    // Notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }

    // Initialize day toggles state
    dayToggles.forEach(toggle => {
        toggle.dispatchEvent(new Event('change'));
    });

    // Initialize break toggles state
    breakToggles.forEach(toggle => {
        toggle.dispatchEvent(new Event('change'));
    });

    // Form submission handler
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Ensure all hidden time inputs are enabled before submission
            document.querySelectorAll('.time-inputs input, .break-times input').forEach(input => {
                input.disabled = false;
            });
            
            // Debug: log form data before submission
            const formData = new FormData(form);
            console.log('Form data being submitted:');
            for (let [key, value] of formData.entries()) {
                if (key.includes('opening_hours')) {
                    console.log(key, value);
                }
            }
        });
    }
});
</script>

<style>
.day-schedule {
    transition: all 0.3s ease;
    position: relative;
}

.day-schedule:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.custom-switch .custom-control-label::before {
    background-color: #dee2e6;
}

.custom-switch .custom-control-input:checked ~ .custom-control-label::before {
    background-color: #28a745;
    border-color: #28a745;
}

.time-inputs {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 200px;
    }
}

.break-times {
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.form-control-sm {
    font-size: 0.875rem;
}

.alert {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: none;
}

.day-schedule .custom-control-label {
    cursor: pointer;
}

.btn-outline-primary:hover, .btn-outline-secondary:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}
</style>
@endpush