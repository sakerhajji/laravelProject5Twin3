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
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">{{ $o->title }}</h5>
                                <div class="small text-secondary">Cible: {{ number_format($o->target_value,2) }} {{ $o->unit }}</div>
                            </div>
                            <span class="badge bg-primary text-white">{{ ucfirst($o->category) }}</span>
                        </div>
                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 small text-secondary">
                            <div>Progression</div><div>{{ $pct }}%</div>
                        </div>

                        <form action="{{ route('front.progress.store') }}" method="post" class="mt-3">
                            @csrf
                            <input type="hidden" name="objective_id" value="{{ $o->id }}">
                            <div class="row g-2">
                                <div class="col-5"><input type="date" name="entry_date" class="form-control" value="{{ date('Y-m-d') }}"></div>
                                <div class="col-4"><input type="number" step="0.01" min="0" name="value" class="form-control" placeholder="Valeur"></div>
                                <div class="col-3"><button class="btn btn-primary btn-block">Ajouter</button></div>
                            </div>
                        </form>

                        @if($userProgress->count())
                            <div class="mt-3 small text-secondary">Dernière mise à jour: {{ optional($userProgress->first())->entry_date?->format('Y-m-d') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info">Aucun objectif actif. Parcourez les <a href="{{ route('front.objectives.index') }}">objectifs disponibles</a>.</div></div>
        @endforelse
    </div>
</div>
@endsection


