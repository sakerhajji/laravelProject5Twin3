@extends('layouts.app')

@section('title', 'Category Details')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Category Details</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ $category->title }}</h2>
            <p class="section-lead">Here are the details of this category.</p>

            <div class="card">
                <div class="card-body">
                    <p><strong>Title:</strong> {{ $category->title }}</p>
                    <p><strong>Description:</strong> {{ $category->description }}</p>
                    @if ($category->image)
                        <p><strong>Image:</strong><br>
                            <img src="{{ asset('storage/'.$category->image) }}" width="150">
                        </p>
                    @endif
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">Edit</a>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
@endpush
