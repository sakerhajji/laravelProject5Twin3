@extends('layouts.app')

@section('title', 'Activity Details')

@section('content')
<style>
    .activity-media {
        width: 100%;
        max-height: 400px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }

    .activity-media:hover {
        transform: scale(1.01);
    }

    .activity-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .breadcrumb {
        background: transparent;
        font-size: 0.95rem;
    }

    .breadcrumb a {
        color: #007bff;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }
</style>

<div class="container py-5">
    <!-- Header / Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12 d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-primary mb-2">
                <i class="fas fa-arrow-left me-2"></i> Back to Activities
            </a>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.activities.index') }}">Activities</a></li>
                    <li class="breadcrumb-item active">{{ $activity->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Activity Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card activity-card p-4">
                <div class="card-body text-center">
                    
                    {{-- Display Media --}}
                    @if($activity->media_type === 'video')
                        <video class="activity-media" controls preload="metadata">
                            <source src="{{ $activity->media_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @elseif($activity->media_type === 'image')
                        <img src="{{ $activity->media_url }}" alt="{{ $activity->title }}" class="activity-media">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 250px;">
                            <i class="fas fa-file fa-3x text-muted"></i>
                        </div>
                    @endif

                    <h1 class="fw-bold mt-3">{{ $activity->title }}</h1>
                    <span class="badge bg-primary mb-3">{{ $activity->category->title ?? 'Uncategorized' }}</span>

                    <p class="text-muted mb-4">{{ $activity->description ?? 'No description provided.' }}</p>

                    <p><strong>Duration:</strong> {{ $activity->time }} minutes</p>
                    <p><strong>Created by:</strong> {{ $activity->user->name ?? 'N/A' }}</p>

                    <div class="mt-4 text-muted small">
                        <p><strong>Created:</strong> {{ $activity->created_at->format('d M Y, H:i') }}</p>
                        <p><strong>Updated:</strong> {{ $activity->updated_at->format('d M Y, H:i') }}</p>
                    </div>

                    <div class="d-flex justify-content-center gap-2 mt-3">
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
