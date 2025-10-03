@extends('layouts.app')

@section('title', 'Category Details')

@section('content')
<div class="container py-5">
    <!-- Header / Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12 d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary mb-2">
                <i class="fas fa-arrow-left me-2"></i> Back to Categories
            </a>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}" class="text-decoration-none">Categories</a></li>
                    <li class="breadcrumb-item active">{{ $category->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Category Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <!-- Banner Image -->
                @if($category->image)
                <img src="{{ asset('storage/'.$category->image) }}" class="card-img-top rounded-top-4" style="max-height: 300px; object-fit: cover;" alt="{{ $category->title }}">
                @else
                <div class="bg-secondary rounded-top-4 d-flex align-items-center justify-content-center" style="height: 300px;">
                    <i class="fas fa-image fa-5x text-white"></i>
                </div>
                @endif

                <div class="card-body text-center">
                    <h1 class="fw-bold mb-3">{{ $category->title }}</h1>
                    <p class="text-muted mb-4">{{ $category->description ?? 'No description provided.' }}</p>

                    <!-- Created / Updated -->
                    <p class="mb-1"><strong>Created At:</strong> {{ $category->created_at->format('d M Y, H:i') }}</p>
                    <p class="mb-4"><strong>Updated At:</strong> {{ $category->updated_at->format('d M Y, H:i') }}</p>

                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
