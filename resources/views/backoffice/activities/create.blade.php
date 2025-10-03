@extends('layouts.app')

@section('title', 'Nouvelle Activité')

@section('content')
<div class="section-header" style="margin-top: 40px;">
    <div class="section-header-back">
        <a href="{{ route('admin.activities.index') }}" class="btn btn-icon">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>
    <h1>Nouvelle Activité</h1>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Titre <span class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}" placeholder="Entrer le titre">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Time -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Durée (minutes) <span class="text-danger">*</span></label>
                                    <input type="number" name="time"
                                           class="form-control @error('time') is-invalid @enderror"
                                           value="{{ old('time') }}" placeholder="Ex: 30">
                                    @error('time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Description <span class="text-danger">*</span></label>
                                    <textarea name="description"
                                              class="form-control @error('description') is-invalid @enderror"
                                              rows="3"
                                              placeholder="Entrer une description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Catégorie <span class="text-danger">*</span></label>
                                    <select name="category_id"
                                            class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">-- Sélectionner une catégorie --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Image (jpg, jpeg, png, gif)</label>
                                    <input type="file" name="image"
                                           class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit buttons -->
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
