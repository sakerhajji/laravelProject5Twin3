@extends('layouts.front')

@section('title', $objective->title)

@section('content')
<div class="container">
    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        @if($objective->cover_url)
            <img src="{{ $objective->cover_url }}" class="w-100" style="max-height: 280px; object-fit: cover;" alt="cover">
        @endif
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h2 class="mb-1">{{ $objective->title }}</h2>
                    <div class="text-secondary">{{ $objective->description }}</div>
                    <div class="mt-2">
                        <span class="badge bg-primary">{{ ucfirst($objective->category) }}</span>
                        <span class="badge bg-light text-dark">Cible: {{ number_format($objective->target_value,2) }} {{ $objective->unit }}</span>
                        <span class="badge bg-light text-dark">Mode: {{ $objective->mode ?? 'cumulative' }}</span>
                    </div>
                </div>
                <div class="text-end">
                    <div class="mb-2"><span class="h4 mb-0">{{ $percent }}%</span> atteint</div>
                    <form action="{{ route('front.objectives.activate', $objective) }}" method="post" class="d-inline">
                        @csrf
                        <button class="btn btn-primary"><i class="fa-solid fa-bullseye me-1"></i>Activer</button>
                    </form>
                    <a href="{{ route('front.progress.index') }}" class="btn btn-outline-secondary">Ajouter un progrès</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Progression</h5>
                <div>
                    <a href="?days=7" class="btn btn-sm {{ $days==7?'btn-primary':'btn-light' }}">7j</a>
                    <a href="?days=30" class="btn btn-sm {{ $days==30?'btn-primary':'btn-light' }}">30j</a>
                    <a href="?days=90" class="btn btn-sm {{ $days==90?'btn-primary':'btn-light' }}">90j</a>
                </div>
            </div>
            <canvas id="objChart" height="120"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function(){
        const ctx = document.getElementById('objChart');
        if(!ctx) return;
        const labels = @json($series['labels']);
        const daily = @json($series['daily']);
        const cum = @json($series['cumulative']);
        const target = @json($series['target']);
        new Chart(ctx, {
            data: {
                labels: labels,
                datasets: [
                    { type: 'bar', label: 'Journalier ({{ $series['unit'] }})', data: daily, backgroundColor: 'rgba(13,110,253,.35)' },
                    { type: 'line', label: 'Cumul ({{ $series['unit'] }})', data: cum, borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,.15)', fill: true, tension: .35 },
                    { type: 'line', label: 'Objectif', data: labels.map(_=> target), borderColor: '#20c997', borderDash: [6,6], pointRadius: 0 }
                ]
            },
            options: { responsive: true, plugins: { legend: { display: true } }, scales: { y: { beginAtZero: true } } }
        });
    })();
</script>
@endpush
@endsection


