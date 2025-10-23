@extends('layouts.app')

@section('title', 'Modifier objectif type')

@section('content')
<div class="main-content">
    <div class="section-header"><h1>Modifier objectif type</h1></div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.objectives.update', $objective) }}" method="post" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Titre</label>
                        <input type="text" name="title" class="form-control" value="{{ $objective->title }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Unité</label>
                        <input type="text" name="unit" class="form-control" value="{{ $objective->unit }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Valeur cible</label>
                        <input type="number" step="0.01" min="0" name="target_value" class="form-control" value="{{ $objective->target_value }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Catégorie</label>
                        <select name="category" class="form-control" required>
                            @foreach(['activite'=>'Activité','nutrition'=>'Nutrition','sommeil'=>'Sommeil','sante'=>'Santé générale'] as $val=>$label)
                                <option value="{{ $val }}" {{ $objective->category===$val?'selected':'' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-8">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control">{{ $objective->description }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Image de couverture</label>
                        @if($objective->cover_url)
                            <div class="mb-2">
                                <img src="{{ $objective->cover_url }}" alt="Image actuelle" style="max-width: 200px; max-height: 150px; object-fit: cover;" class="img-thumbnail">
                                <div class="small text-muted">Image actuelle</div>
                            </div>
                        @endif
                        <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*" onchange="previewImage(this)">
                        <div id="image_preview" class="mt-2" style="display: none;">
                            <img id="preview_img" src="" alt="Nouvel aperçu" style="max-width: 200px; max-height: 150px; object-fit: cover;" class="img-thumbnail">
                            <div class="small text-muted">Nouvel aperçu</div>
                        </div>
                        <small class="form-text text-muted">Formats acceptés: JPEG, PNG, JPG, GIF (max 2MB)</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label>URL d'image (alternative)</label>
                        <input type="url" name="cover_url" class="form-control" value="{{ $objective->cover_url }}" placeholder="https://example.com/image.jpg">
                        <small class="form-text text-muted">Ou utilisez une URL d'image externe</small>
                    </div>
                </div>
                <button class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.objectives.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview_img').src = e.target.result;
            document.getElementById('image_preview').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush


