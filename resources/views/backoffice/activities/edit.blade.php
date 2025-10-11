@extends('layouts.app')

@section('title', isset($activity) ? 'Modifier Activité' : 'Nouvelle Activité')

@section('content')
<div class="section-header" style="margin-top: 40px;">
    <div class="section-header-back">
        <a href="{{ route('admin.activities.index') }}" class="btn btn-icon">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>
    <h1>{{ isset($activity) ? 'Modifier Activité' : 'Nouvelle Activité' }}</h1>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ isset($activity) ? route('admin.activities.update', $activity->id) : route('admin.activities.store') }}" 
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($activity))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Titre <span class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title', $activity->title ?? '') }}" 
                                           placeholder="Entrer le titre">
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
                                           value="{{ old('time', $activity->time ?? '') }}"
                                           placeholder="Ex: 30">
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
                                              placeholder="Entrer une description">{{ old('description', $activity->description ?? '') }}</textarea>
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
                                                {{ old('category_id', $activity->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Media Upload -->
                            <div class="col-12 mb-3">
                                <label for="media" class="form-label fw-bold">Upload Image or Video</label>
                                <input type="file" name="media" id="media"
                                       class="form-control @error('media') is-invalid @enderror"
                                       accept="image/*,video/*">
                                @error('media')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Current Media (if editing) -->
                            @if(isset($activity) && $activity->media_url)
                            <div class="col-12 mb-3">
                                <label class="fw-bold">Current Media:</label>
                                <div class="border rounded p-2" style="width: 300px; background: #f8f9fa;">
                                    @if($activity->media_type === 'image')
                                        <img src="{{ $activity->media_url }}" alt="Current Image" style="width: 100%; border-radius: 8px;">
                                    @elseif($activity->media_type === 'video')
                                        <video src="{{ $activity->media_url }}" controls style="width: 100%; border-radius: 8px;"></video>
                                    @endif
                                </div>
                            </div>
                            @endif

                      
                        </div>

                        <!-- Buttons -->
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ isset($activity) ? 'Mettre à jour' : 'Enregistrer' }}
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mediaInput = document.getElementById('media');
    const previewContainer = document.getElementById('preview-container');

    mediaInput.addEventListener('change', function(event) {
        previewContainer.innerHTML = ''; // Clear preview
        const file = event.target.files[0];

        if (!file) {
            previewContainer.textContent = 'No file selected';
            return;
        }

        const url = URL.createObjectURL(file);
        const fileType = file.type;

        if (fileType.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = url;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'contain';
            previewContainer.appendChild(img);
        } else if (fileType.startsWith('video/')) {
            const video = document.createElement('video');
            video.src = url;
            video.controls = true;
            video.style.width = '100%';
            video.style.height = '100%';
            video.style.objectFit = 'contain';
            previewContainer.appendChild(video);
        } else {
            previewContainer.textContent = 'Unsupported file type';
            previewContainer.style.color = 'red';
        }
    });
});
</script>
@endsection
