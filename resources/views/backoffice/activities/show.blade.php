@extends('layouts.app')

@section('title', 'Activity Details')

@section('content')
<div class="container py-5">
    <!-- Header / Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12 d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-primary mb-2">
                <i class="fas fa-arrow-left me-2"></i> Back to Activities
            </a>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.activities.index') }}" class="text-decoration-none">Activities</a></li>
                    <li class="breadcrumb-item active">{{ $activity->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Activity Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center">
                    <!-- Image -->
                    @if($activity->image)
                    <img src="{{ asset('storage/'.$activity->image) }}" class="img-fluid rounded shadow mb-3" style="max-height: 200px; object-fit: cover;" alt="{{ $activity->title }}">
                    @else
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                        <i class="fas fa-image fa-3x text-white"></i>
                    </div>
                    @endif

                    <!-- Title and Category -->
                    <h1 class="fw-bold mb-2">{{ $activity->title }}</h1>
                    <span class="badge bg-primary mb-3">{{ $activity->category->title ?? 'Uncategorized' }}</span>

                    <!-- Description -->
                    <p class="text-muted mb-4">{{ $activity->description ?? 'No description provided.' }}</p>

                    <!-- Time and Creator -->
                    <p class="mb-1"><strong>Time:</strong> {{ $activity->time }}</p>
                    <p class="mb-4"><strong>Created by:</strong> {{ $activity->user->name ?? '-' }}</p>

                    <!-- Created / Updated -->
                    <p class="mb-1"><strong>Created At:</strong> {{ $activity->created_at->format('d M Y, H:i') }}</p>
                    <p class="mb-4"><strong>Updated At:</strong> {{ $activity->updated_at->format('d M Y, H:i') }}</p>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
