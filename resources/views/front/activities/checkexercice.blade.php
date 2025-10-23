@extends('layouts.front')

@section('title', 'Vérifier Exercice')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">Vérifier votre Exercice</h2>

    <div class="card p-4 shadow-sm">
        <form id="check-exercise-form" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="exercise-image" class="form-label">Téléchargez votre image</label>
                <input type="file" name="image" class="form-control" id="exercise-image" accept="image/*" required>
            </div>

            <!-- Image Preview -->
            <div id="image-preview" class="text-center my-3" style="display: none;">
                <img id="preview-img" src="" alt="Prévisualisation" class="img-fluid rounded" style="max-height: 400px;">
            </div>

            <button type="submit" class="btn btn-primary w-100">Vérifier</button>
        </form>

        <!-- Loader -->
        <div id="loader" class="text-center my-3" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Analyse de l'image...</p>
        </div>

        <!-- Result -->
        <div id="result" class="mt-4" style="display: none;">
            <h5>Résultat:</h5>
            <p><strong>Exercice détecté:</strong> <span id="exercise-class"></span></p>
            <p><strong>Confiance:</strong> <span id="exercise-confidence"></span>%</p>
        </div>
    </div>
</div>

<script>
const exerciseImage = document.getElementById('exercise-image');
const previewDiv = document.getElementById('image-preview');
const previewImg = document.getElementById('preview-img');

exerciseImage.addEventListener('change', function() {
    const file = this.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(e){
            previewImg.src = e.target.result;
            previewDiv.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        previewDiv.style.display = 'none';
    }
});

document.getElementById('check-exercise-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const loader = document.getElementById('loader');
    const resultDiv = document.getElementById('result');

    // Hide previous result and show loader
    resultDiv.style.display = 'none';
    loader.style.display = 'block';

    try {
        const response = await fetch("{{ route('checkexercice.post') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();

        loader.style.display = 'none';

        if(data.predictions && data.predictions.length > 0){
            document.getElementById('exercise-class').innerText = data.predictions[0].class;
            document.getElementById('exercise-confidence').innerText = (data.predictions[0].confidence * 100).toFixed(2);
            resultDiv.style.display = 'block';
        } else {
            resultDiv.innerHTML = '<p class="text-danger">Aucun résultat détecté.</p>';
            resultDiv.style.display = 'block';
        }
    } catch(err) {
        loader.style.display = 'none';
        resultDiv.innerHTML = '<p class="text-danger">Erreur lors de la vérification. Réessayez.</p>';
        resultDiv.style.display = 'block';
        console.error(err);
    }
});
</script>
@endsection
