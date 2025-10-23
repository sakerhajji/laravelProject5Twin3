@extends('layouts.app')

@section('title', 'Créer un Repas')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Créer un nouveau repas</h1>
        <a href="{{ route('admin.repas.index') }}" class="btn btn-secondary">
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

    <form action="{{ route('admin.repas.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Left Column: Meal Details -->
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Détails du Repas</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nom">Nom du repas</label>
                            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="user_id">Assigner à l'utilisateur</label>
                            <select name="user_id" id="user_id" class="form-control select2-users" required>
                                <option value="">-- Choisir un utilisateur --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Aliments -->
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Composition du Repas</h5>
                        <button type="button" id="add-aliment" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un aliment
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="aliments-container">
                            <!-- Aliments will be added here dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-end">
                        <a href="{{ route('admin.repas.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer le repas</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let alimentIndex = 0;

        function initializeSelect2() {
            $('.select2-aliments').select2({
                placeholder: '-- Choisir un aliment --',
                width: '100%'
            });
        }

        $('#add-aliment').on('click', function() {
            const alimentRow = `
                <div class="row aliment-row mb-3 align-items-center">
                    <div class="col-6">
                        <select name="aliments[${alimentIndex}][id]" class="form-control select2-aliments" required>
                            <option value=""></option>
                            @foreach ($aliments as $aliment)
                                <option value="{{ $aliment->id }}">{{ $aliment->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <div class="input-group">
                            <input type="number" name="aliments[${alimentIndex}][quantite]" class="form-control" placeholder="Quantité" required min="0.01" step="0.01">
                            <div class="input-group-append">
                                <span class="input-group-text">g</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-right">
                        <button type="button" class="btn btn-danger btn-sm remove-aliment">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#aliments-container').append(alimentRow);
            initializeSelect2();
            alimentIndex++;
        });

        $(document).on('click', '.remove-aliment', function() {
            $(this).closest('.aliment-row').remove();
        });

        $('.select2-users').select2({
            placeholder: '-- Choisir un utilisateur --',
            width: '100%'
        });
    });
</script>
@endpush
