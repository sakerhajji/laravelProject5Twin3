@extends('layouts.app')

@section('title', isset($category) ? 'Edit Category' : 'Create Category')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ isset($category) ? 'Edit Category' : 'Create Category' }}</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ isset($category) ? 'Edit Category' : 'Create Category' }}</h2>
            <p class="section-lead">
                {{ isset($category) ? 'Edit the category details below.' : 'Fill the form to create a new category.' }}
            </p>

            <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($category))
                    @method('PUT')
                @endif

                {{-- Title --}}
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" id="title" placeholder="Enter title" value="{{ old('title', $category->title ?? '') }}">
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" id="description" placeholder="Enter description">{{ old('description', $category->description ?? '') }}</textarea>
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Image --}}
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" class="form-control" id="image">
                    @if(isset($category) && $category->image)
                        <br>
                        <img src="{{ asset('storage/'.$category->image) }}" width="100">
                    @endif
                    @error('image')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Update' : 'Save' }}</button>
            </form>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
@endpush
