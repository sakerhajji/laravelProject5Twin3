<!-- resources/views/asymptomes/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Modifier un asymptôme</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('asymptomes.update', $asymptome->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- important for updating -->

            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" id="nom" value="{{ old('nom', $asymptome->nom) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" id="description">{{ old('description', $asymptome->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="maladies" class="form-label">Maladies associées</label>
                <select name="maladie_ids[]" id="maladies" class="form-select" multiple>
                    @foreach($maladies as $maladie)
                        <option value="{{ $maladie->id }}"
                                @if($asymptome->maladies->contains($maladie->id)) selected @endif>
                            {{ $maladie->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('asymptomes.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
