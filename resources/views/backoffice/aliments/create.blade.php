@extends('layouts.app')

@section('title', 'Créer un Aliment')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Créer un nouvel aliment</h1>
        <a href="{{ route('admin.aliments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.aliments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Left Column: Main Details -->
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informations Générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nom">Nom de l'aliment</label>
                            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="image_path">Image de l'aliment</label>
                            <div class="custom-file">
                                <input type="file" name="image_path" id="image_path" class="custom-file-input">
                                <label class="custom-file-label" for="image_path">Choisir un fichier...</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Nutritional Values -->
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Valeurs Nutritionnelles (pour 100g)</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="calories">Calories</label>
                            <div class="input-group">
                                <input type="number" name="calories" id="calories" class="form-control" value="{{ old('calories', 0) }}" step="0.01">
                                <div class="input-group-append">
                                    <span class="input-group-text">kcal</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="proteines">Protéines</label>
                            <div class="input-group">
                                <input type="number" name="proteines" id="proteines" class="form-control" value="{{ old('proteines', 0) }}" step="0.01">
                                <div class="input-group-append">
                                    <span class="input-group-text">g</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="glucides">Glucides</label>
                            <div class="input-group">
                                <input type="number" name="glucides" id="glucides" class="form-control" value="{{ old('glucides', 0) }}" step="0.01">
                                <div class="input-group-append">
                                    <span class="input-group-text">g</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lipides">Lipides</label>
                            <div class="input-group">
                                <input type="number" name="lipides" id="lipides" class="form-control" value="{{ old('lipides', 0) }}" step="0.01">
                                <div class="input-group-append">
                                    <span class="input-group-text">g</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-end">
                        <a href="{{ route('admin.aliments.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer l'aliment</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Show the file name in the custom file input
    $('.custom-file-input').on('change', function(event) {
        var inputFile = event.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
    });
</script>
@endpush
