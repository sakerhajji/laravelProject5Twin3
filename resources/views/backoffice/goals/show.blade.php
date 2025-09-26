@extends('layouts.app')

@section('title', 'Objectif: ' . $goal->title)

@section('content')
<div class="main-content">
    <div class="section-header">
        <h1>{{ $goal->title }}</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('back.goals.index') }}" class="btn btn-light">Retour</a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header"><h4>Progression</h4></div>
                <div class="card-body">
                    <canvas id="progressChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header"><h4>Ajouter une entrée</h4></div>
                <div class="card-body">
                    <form action="{{ route('back.goal-entries.store', $goal) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="entry_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label>Valeur ({{ $goal->unit }})</label>
                            <input type="number" step="0.01" min="0" name="value" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Note</label>
                            <textarea name="note" class="form-control" rows="2"></textarea>
                        </div>
                        <button class="btn btn-primary">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h4>Historique</h4></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Valeur</th>
                            <th>Note</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $entry)
                            <tr>
                                <td>{{ $entry->entry_date->format('Y-m-d') }}</td>
                                <td>{{ number_format($entry->value,2) }} {{ $goal->unit }}</td>
                                <td>{{ $entry->note }}</td>
                                <td class="text-right">
                                    <form action="{{ route('back.goal-entries.destroy', [$goal, $entry]) }}" method="post" onsubmit="return confirm('Supprimer cette ligne ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Aucune entrée</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function(){
        const ctx = document.getElementById('progressChart');
        if(!ctx) return;
        const labels = @json($entries->pluck('entry_date')->map->format('Y-m-d'));
        const dataVals = @json($entries->pluck('value'));
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Progression ({{ $goal->unit }})',
                    data: dataVals,
                    borderColor: '#6777ef',
                    backgroundColor: 'rgba(103,119,239,.15)',
                    fill: true,
                    tension: .35,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true }},
                scales: { y: { beginAtZero: true } }
            }
        });
    })();
</script>
@endpush
@endsection


