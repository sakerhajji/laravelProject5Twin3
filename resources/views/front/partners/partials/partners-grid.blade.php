@forelse($partners as $partner)
    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
        <div class="card partner-card h-100 shadow-sm">
            <!-- Partner Image/Logo -->
            <div class="partner-image-container position-relative">
                @if($partner->logo)
                    <img src="{{ asset('storage/' . $partner->logo) }}"
                         class="card-img-top partner-image"
                         alt="{{ $partner->name }}"
                         style="height: 220px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center partner-image-placeholder" style="height: 220px;">
                        @if($partner->type == 'doctor')
                            <i class="fas fa-user-md fa-4x text-white opacity-75"></i>
                        @elseif($partner->type == 'gym')
                            <i class="fas fa-dumbbell fa-4x text-white opacity-75"></i>
                        @elseif($partner->type == 'laboratory')
                            <i class="fas fa-flask fa-4x text-white opacity-75"></i>
                        @elseif($partner->type == 'pharmacy')
                            <i class="fas fa-pills fa-4x text-white opacity-75"></i>
                        @elseif($partner->type == 'nutritionist')
                            <i class="fas fa-apple-alt fa-4x text-white opacity-75"></i>
                        @else
                            <i class="fas fa-brain fa-4x text-white opacity-75"></i>
                        @endif
                    </div>
                @endif
                
                <!-- Favorite Button -->
                @auth
                    @php
                        $isFavorited = in_array($partner->id, $userFavorites);
                    @endphp
                    <button class="btn btn-sm position-absolute favorite-btn toggle-favorite rounded-circle shadow {{ $isFavorited ? 'btn-danger favorited' : 'btn-light' }}"
                            style="top: 15px; right: 15px; width: 45px; height: 45px; transition: all 0.3s ease;"
                            data-partner-id="{{ $partner->id }}"
                            title="{{ $isFavorited ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                        <i class="fas fa-heart {{ $isFavorited ? 'text-white' : 'text-danger' }}" style="font-size: 18px;"></i>
                    </button>
                @endauth
                
                <!-- Type Badge -->
                <div class="position-absolute" style="bottom: 10px; left: 10px;">
                    <span class="badge badge-primary badge-lg px-3 py-2">{{ $partner->type_label }}</span>
                </div>
            </div>
            
            <!-- Card Body -->
            <div class="card-body">
                <!-- Partner Name & Rating -->
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0 font-weight-bold">{{ $partner->name }}</h5>
                    @if($partner->rating > 0)
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $partner->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                           
                           
                        </div>
                    @else
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-muted"></i>
                            @endfor
                            <small class="text-muted">Pas d'avis</small>
                        </div>
                    @endif
                </div>
                
                <!-- Specialization -->
                @if($partner->specialization)
                    <p class="text-primary font-weight-bold mb-2">
                        <i class="fas fa-certificate"></i> {{ $partner->specialization }}
                    </p>
                @endif
                
                <!-- Description -->
                @if($partner->description)
                    <p class="card-text text-muted">{{ Str::limit($partner->description, 120) }}</p>
                @endif
                
                <!-- Location -->
                @if($partner->city)
                    <div class="location mb-3">
                        <i class="fas fa-map-marker-alt text-danger"></i>
                        <span class="text-muted">{{ $partner->city }}</span>
                    </div>
                @endif
                
                <!-- Services Preview -->
                @if($partner->services && count($partner->services) > 0)
                    <div class="services-preview mb-3">
                        <small class="text-muted font-weight-bold">Services:</small>
                        <div class="mt-1">
                            @foreach(array_slice($partner->services, 0, 2) as $service)
                                <span class="badge badge-light text-dark mr-1 mb-1">{{ $service }}</span>
                            @endforeach
                            @if(count($partner->services) > 2)
                                <span class="badge badge-info">+{{ count($partner->services) - 2 }} autres</span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Opening Hours Status -->
                @if($partner->opening_hours && count($partner->opening_hours) > 0)
                    @php $currentStatus = $partner->current_day_status @endphp
                    <div class="opening-status mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-muted mr-2"></i>
                            <span class="badge badge-{{ $currentStatus['status'] === 'open' ? 'success' : ($currentStatus['status'] === 'break' ? 'warning' : 'secondary') }} mr-2">
                                {{ $currentStatus['message'] }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Card Footer -->
            <div class="card-footer bg-transparent border-0">
                <div class="row no-gutters">
                    <div class="col-8">
                        <a href="{{ route('front.partners.show', $partner) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-info-circle"></i> Voir détails
                        </a>
                    </div>
                    <div class="col-4 pl-2">
                        <div class="btn-group btn-block">
                            @if($partner->phone)
                                <a href="tel:{{ $partner->phone }}" class="btn btn-success" title="Appeler">
                                    <i class="fas fa-phone"></i>
                                </a>
                            @endif
                            @if($partner->email)
                                <a href="mailto:{{ $partner->email }}" class="btn btn-info" title="Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="empty-state text-center py-5">
            <div class="card">
                <div class="card-body py-5">
                    <i class="fas fa-search fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">Aucun partenaire trouvé</h4>
                    <p class="text-muted">Aucun partenaire ne correspond à vos critères de recherche.</p>
                    <div class="mt-4">
                        <button type="button" id="reset-filters-empty" class="btn btn-primary me-2">
                            <i class="fas fa-undo"></i> Réinitialiser les filtres
                        </button>
                        <button class="btn btn-outline-primary" onclick="window.scrollTo(0,0)">
                            <i class="fas fa-filter"></i> Modifier les critères
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforelse