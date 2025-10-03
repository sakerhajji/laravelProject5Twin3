@extends('layouts.app')

@section('title', isset($category) ? 'Modifier Catégorie' : 'Nouvelle Catégorie')

@section('content')
<div class="section-header" style="margin-top: 40px;">
    <div class="section-header-back">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-icon">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>
    <h1>{{ isset($category) ? 'Modifier Catégorie' : 'Nouvelle Catégorie' }}</h1>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($category))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Titre <span class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title', $category->title ?? '') }}" 
                                           placeholder="Entrer le titre" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Description<span class="text-danger">*</span></label>
                                    <textarea name="description"
                                              class="form-control @error('description') is-invalid @enderror"
                                              rows="3"
                                              placeholder="Entrer une description">{{ old('description', $category->description ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Image (jpg, jpeg, png, gif)</label>
                                    <input type="file" name="image"
                                           class="form-control @error('image') is-invalid @enderror" 
                                           accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    @if(isset($category) && $category->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/'.$category->image) }}" 
                                                 alt="Image actuelle" 
                                                 width="100" 
                                                 class="img-thumbnail">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ isset($category) ? 'Mettre à jour' : 'Enregistrer' }}
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
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
