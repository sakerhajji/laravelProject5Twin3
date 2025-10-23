@extends('layouts.front')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Analyze Your Meal</h4>
                </div>

                <div class="card-body p-4">
                    <p class="text-center text-muted">Upload a clear image of your meal to get a nutritional analysis.</p>
                    <form id="analyze-form" enctype="multipart/form-data" class="text-center">
                        @csrf
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" name="image" id="image" class="custom-file-input" required>
                                <label class="custom-file-label" for="image">Choose file...</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 px-5">Analyze</button>
                    </form>

                    <div id="loading" class="text-center mt-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Analyzing your meal, please wait...</p>
                    </div>

                    <div id="error" class="mt-4 alert alert-danger" style="display: none;"></div>

                    <hr class="my-4" id="results-separator" style="display: none;">

                    <div id="results" class="mt-4" style="display: none;">
                        <h3 class="text-center mb-4">Analysis Results</h3>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h4 id="food-title" class="mb-3"></h4>
                                        <img id="image-preview" src="#" alt="Meal Image" class="img-fluid rounded shadow-sm" style="max-height: 300px;"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="text-center mb-3">Nutritional Information</h5>
                                        <canvas id="nutrition-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">My Meals</h4>
                </div>
                <div class="card-body">
                    @if($repas->isEmpty())
                        <p class="text-center text-muted">You have not saved any meals yet.</p>
                    @else
                        <div class="row">
                            @foreach($repas as $rep)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $rep->nom }}</h5>
                                            <p class="card-text">{{ $rep->description }}</p>
                                            
                                            <h6 class="mt-4">Meal Content:</h6>
                                            @if($rep->aliments->count() > 0)
                                                <ul class="list-group list-group-flush">
                                                    @foreach($rep->aliments as $aliment)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $aliment->nom }}
                                                            <span class="badge badge-primary badge-pill">{{ $aliment->pivot->quantite }} g</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted">No food items listed for this meal.</p>
                                            @endif
                                        </div>
                                        <div class="card-footer text-muted">
                                            Saved on: {{ $rep->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $repas->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const nextSibling = e.target.nextElementSibling;
    if (e.target.files.length > 0) {
        nextSibling.innerText = 'Image selected!';
    } else {
        nextSibling.innerText = 'Choose file...';
    }

    const reader = new FileReader();
    reader.onload = function(event) {
        document.getElementById('image-preview').src = event.target.result;
    }
    reader.readAsDataURL(e.target.files[0]);
});

document.getElementById('analyze-form').addEventListener('submit', function(e) {
    e.preventDefault();

    let form = e.target;
    let formData = new FormData(form);
    let resultsDiv = document.getElementById('results');
    let errorDiv = document.getElementById('error');
    let loadingDiv = document.getElementById('loading');
    let separator = document.getElementById('results-separator');
    let chartCanvas = document.getElementById('nutrition-chart');
    let foodTitle = document.getElementById('food-title');

    resultsDiv.style.display = 'none';
    errorDiv.style.display = 'none';
    separator.style.display = 'none';
    loadingDiv.style.display = 'block';

    fetch("{{ route('repas.analyze.image') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        loadingDiv.style.display = 'none';
        if (data.error) {
            errorDiv.style.display = 'block';
            errorDiv.textContent = data.error + (data.details ? ' ' + data.details : '');
        } else {
            resultsDiv.style.display = 'block';
            separator.style.display = 'block';
            foodTitle.textContent = data.food_name;

            const nutritionData = data.nutrition;
            const chartData = {
                labels: ['Calories', 'Protein (g)', 'Carbs (g)', 'Fat (g)'],
                datasets: [{
                    data: [
                        nutritionData.calories,
                        nutritionData.protein,
                        nutritionData.carbohydrates,
                        nutritionData.fat
                    ],
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0'
                    ],
                    hoverBackgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0'
                    ]
                }]
            };

            if (window.nutritionChart) {
                window.nutritionChart.destroy();
            }

            window.nutritionChart = new Chart(chartCanvas, {
                type: 'doughnut',
                data: chartData,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    },
                    title: {
                        display: true,
                        text: 'Nutritional Breakdown'
                    }
                }
            });
        }
    })
    .catch(error => {
        loadingDiv.style.display = 'none';
        errorDiv.style.display = 'block';
        errorDiv.textContent = 'An error occurred while analyzing the image.';
        console.error('Error:', error);
    });
});
</script>
@endsection
