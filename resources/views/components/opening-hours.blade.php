@props(['partner', 'compact' => false])

@php
    $formattedHours = $partner->formatted_opening_hours ?? [];
    $currentStatus = $partner->current_day_status ?? ['status' => 'closed', 'message' => 'Fermé'];
@endphp

@if(!empty($formattedHours))
    <div class="opening-hours {{ $compact ? 'opening-hours-compact' : '' }}">
        @if(!$compact)
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0"><i class="fas fa-clock text-primary"></i> Horaires d'ouverture</h6>
                <span class="badge badge-{{ $currentStatus['status'] === 'open' ? 'success' : ($currentStatus['status'] === 'break' ? 'warning' : 'secondary') }}">
                    {{ $currentStatus['message'] }}
                </span>
            </div>
        @endif
        
        <div class="row">
            @foreach($formattedHours as $day => $dayInfo)
                @php
                    $isToday = strtolower(date('l')) === $day;
                @endphp
                <div class="col-{{ $compact ? '12' : 'md-6 col-lg-4' }} mb-{{ $compact ? '1' : '2' }}">
                    <div class="d-flex justify-content-between align-items-center {{ $compact ? 'py-1' : 'p-2' }} {{ $isToday ? 'bg-light border-left border-primary' : '' }} rounded-sm">
                        <div class="d-flex align-items-center">
                            <strong class="mr-2 {{ $isToday ? 'text-primary' : '' }}">
                                {{ $dayInfo['label'] }}:
                            </strong>
                            @if($isToday && !$compact)
                                <small class="badge badge-primary mr-2">Aujourd'hui</small>
                            @endif
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="mr-2 {{ $dayInfo['is_open'] ? 'text-success' : 'text-muted' }} {{ $compact ? 'small' : '' }}">
                                {{ $dayInfo['hours'] }}
                            </span>
                            <i class="fas fa-{{ $dayInfo['is_open'] ? 'check text-success' : 'times text-muted' }} {{ $compact ? 'fa-xs' : '' }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="text-muted">
        <i class="fas fa-info-circle"></i> Aucun horaire défini
    </div>
@endif

@if($compact)
<style>
.opening-hours-compact .row {
    margin: 0;
}
.opening-hours-compact [class*="col-"] {
    padding-left: 0;
    padding-right: 0;
}
</style>
@endif