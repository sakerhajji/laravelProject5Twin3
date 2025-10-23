<!-- resources/views/asymptomes/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Ajouter un asymptôme</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('asymptomes.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" value="{{ old('nom') }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description">{{ old('description') }}</textarea>
            </div>

           

            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="{{ route('asymptomes.index') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection
