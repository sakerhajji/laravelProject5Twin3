@extends('layouts.front')

@section('title', 'Mes objectifs')

@section('content')
<div class="container">
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    <div class="row">
        @forelse($myObjectives as $o)
            @php
                $userProgress = $o->progresses->where('user_id', auth()->id());
                $sum = $userProgress->sum('value');
                $pct = $o->target_value > 0 ? min(100, round(($sum/$o->target_value)*100)) : 0;
                $trend = method_exists($o, 'trendForUser') ? $o->trendForUser(auth()->id()) : 'flat';
                $lastUpdate = method_exists($o, 'lastUpdateForUser') ? $o->lastUpdateForUser(auth()->id()) : null;
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm overflow-hidden">
                    @if($o->cover_url)
                        <img src="{{ $o->cover_url }}" class="w-100" style="max-height: 140px; object-fit: cover;" alt="cover">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">{{ $o->title }}</h5>
                                <div class="small text-secondary">Cible: {{ number_format($o->target_value,2) }} {{ $o->unit }}</div>
                            </div>
                            <span class="badge bg-primary text-white">{{ ucfirst($o->category) }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-3 mt-3">
                            <div class="ring" style="--val: {{ $pct }}; --color: {{ $trend==='down'?'#dc3545':($trend==='up'?'#28a745':'#0d6efd') }}">
                                <span>{{ $pct }}%</span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 small text-secondary align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span>Progression</span>
                                @if($trend==='up')
                                    <span class="text-success"><i class="fa-solid fa-arrow-trend-up"></i></span>
                                @elseif($trend==='down')
                                    <span class="text-danger"><i class="fa-solid fa-arrow-trend-down"></i></span>
                                @else
                                    <span class="text-muted"><i class="fa-solid fa-minus"></i></span>
                                @endif
                            </div>
                            <div>{{ $pct }}%</div>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary mt-3" data-bs-toggle="modal" data-bs-target="#progressModal" data-obj='{"id":{{ $o->id }},"title":"{{ addslashes($o->title) }}","unit":"{{ $o->unit }}"}'>+ Progrès</button>

                        @if($userProgress->count())
                            <div class="mt-3 small text-secondary">Dernière mise à jour: {{ $lastUpdate ?? optional($userProgress->first())->entry_date?->format('Y-m-d') }}</div>
                        @endif
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="{{ route('front.objectives.show', $o) }}" class="btn btn-sm btn-outline-secondary">Voir</a>
                        <a href="{{ route('front.objectives.index') }}" class="btn btn-sm btn-light">Parcourir</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info">Aucun objectif actif. Parcourez les <a href="{{ route('front.objectives.index') }}">objectifs disponibles</a>.</div></div>
        @endforelse
    </div>
    <!-- Modal Progrès -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un progrès</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('front.progress.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="objective_id" id="pm_objective_id">
                        <div class="mb-3">
                            <label class="form-label">Objectif</label>
                            <input type="text" class="form-control" id="pm_objective_title" disabled>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">Date</label>
                                <input type="date" name="entry_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Valeur <span id="pm_unit"></span></label>
                                <input type="number" step="0.01" min="0" name="value" class="form-control" placeholder="">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Note</label>
                                <textarea name="note" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('progressModal')?.addEventListener('show.bs.modal', function (event) {
  const button = event.relatedTarget;
  try {
    const data = JSON.parse(button.getAttribute('data-obj'));
    document.getElementById('pm_objective_id').value = data.id;
    document.getElementById('pm_objective_title').value = data.title;
    document.getElementById('pm_unit').textContent = '(' + (data.unit||'') + ')';
  } catch(e) {}
});
</script>
@endpush
@endsection
