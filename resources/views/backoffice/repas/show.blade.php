@extends('layouts.app')

@section('title', 'Détails du Repas')

@section('content')
<div class="container-fluid mt-5">
    @php
        $totalCalories = 0;
        $totalProteines = 0;
        $totalGlucides = 0;
        $totalLipides = 0;
        
        foreach ($repas->aliments as $aliment) {
            $quantite = $aliment->pivot->quantite;
            $totalCalories += $aliment->calories * ($quantite / 100);
            $totalProteines += $aliment->proteines * ($quantite / 100);
            $totalGlucides += $aliment->glucides * ($quantite / 100);
            $totalLipides += $aliment->lipides * ($quantite / 100);
        }
    @endphp

    <div id="repas-content">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center bg-light">
                        <h1 class="h3 mb-2 font-weight-bold">{{ $repas->nom }}</h1>
                        @if($repas->description)
                            <p class="text-muted mb-0">{{ $repas->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Macronutrients and Chart -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Résumé Nutritionnel</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Total Calories</h6>
                            <span class="badge badge-danger badge-pill px-3 py-2">{{ number_format($totalCalories, 0) }} kcal</span>
                        </div>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Protéines
                                <span class="font-weight-bold">{{ number_format($totalProteines, 1) }}g</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Glucides
                                <span class="font-weight-bold">{{ number_format($totalGlucides, 1) }}g</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Lipides
                                <span class="font-weight-bold">{{ number_format($totalLipides, 1) }}g</span>
                            </li>
                        </ul>
                        <div class="text-center">
                            <canvas id="nutritionChart" style="max-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Aliments Table -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Composition du Repas</h5>
                        <button id="download-pdf" class="btn btn-sm btn-outline-secondary no-print">
                            <i class="fas fa-download mr-1"></i> Telecharger
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Aliment</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-center">Calories</th>
                                    <th class="text-center d-none d-md-table-cell">Protéines</th>
                                    <th class="text-center d-none d-md-table-cell">Glucides</th>
                                    <th class="text-center d-none d-md-table-cell">Lipides</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($repas->aliments as $aliment)
                                    @php
                                        $quantite = $aliment->pivot->quantite;
                                        $calories = $aliment->calories * ($quantite / 100);
                                        $proteines = $aliment->proteines * ($quantite / 100);
                                        $glucides = $aliment->glucides * ($quantite / 100);
                                        $lipides = $aliment->lipides * ($quantite / 100);
                                    @endphp
                                    <tr>
                                        <td>{{ $aliment->nom }}</td>
                                        <td class="text-center">{{ $quantite }}g</td>
                                        <td class="text-center">{{ number_format($calories, 0) }}</td>
                                        <td class="text-center d-none d-md-table-cell">{{ number_format($proteines, 1) }}g</td>
                                        <td class="text-center d-none d-md-table-cell">{{ number_format($glucides, 1) }}g</td>
                                        <td class="text-center d-none d-md-table-cell">{{ number_format($lipides, 1) }}g</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Aucun aliment dans ce repas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($repas->aliments->count() > 0)
                                <tfoot class="bg-light font-weight-bold">
                                    <tr>
                                        <td>TOTAL</td>
                                        <td class="text-center">{{ $repas->aliments->sum('pivot.quantite') }}g</td>
                                        <td class="text-center">{{ number_format($totalCalories, 0) }}</td>
                                        <td class="text-center d-none d-md-table-cell">{{ number_format($totalProteines, 1) }}g</td>
                                        <td class="text-center d-none d-md-table-cell">{{ number_format($totalGlucides, 1) }}g</td>
                                        <td class="text-center d-none d-md-table-cell">{{ number_format($totalLipides, 1) }}g</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-2 no-print">
        <div class="col-12 d-flex justify-content-between">
            <a href="{{ route('admin.repas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
            </a>
            <div>
                <a href="{{ route('admin.repas.edit', $repas) }}" class="btn btn-primary">
                    <i class="fas fa-edit mr-1"></i> Modifier
                </a>
                <form action="{{ route('admin.repas.destroy', $repas) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce repas ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('nutritionChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Protéines', 'Glucides', 'Lipides'],
            datasets: [{
                data: [
                    {{ number_format($totalProteines, 2, '.', '') }},
                    {{ number_format($totalGlucides, 2, '.', '') }},
                    {{ number_format($totalLipides, 2, '.', '') }}
                ],
                backgroundColor: [
                    '#28a745', // green
                    '#ffc107', // yellow
                    '#17a2b8'  // cyan
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutoutPercentage: 70,
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 20
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        const dataset = data.datasets[tooltipItem.datasetIndex];
                        const total = dataset.data.reduce((previousValue, currentValue) => previousValue + currentValue);
                        const currentValue = dataset.data[tooltipItem.index];
                        const percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                        return data.labels[tooltipItem.index] + ': ' + currentValue.toFixed(1) + 'g (' + percentage + '%)';
                    }
                }
            }
        }
    });

    document.getElementById('download-pdf').addEventListener('click', function() {
        const content = document.getElementById('repas-content');
        html2canvas(content).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            pdf.save("repas-{{ $repas->nom }}.pdf");
        });
    });
});
</script>
@endpush

@push('styles')
<style>
    @media print {
        .no-print, .btn, footer, .navbar, .main-sidebar {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6;
        }
    }
</style>
@endpush
