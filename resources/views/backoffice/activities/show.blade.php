@extends('layouts.app')

@section('title', 'Activity Details')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Activity Details</h1>
        </div>

        <div class="section-body">
            <p><strong>ID:</strong> {{ $activity->id }}</p>
            <p><strong>Title:</strong> {{ $activity->title }}</p>
            <p><strong>Description:</strong> {{ $activity->description }}</p>
            <p><strong>Time:</strong> {{ $activity->time }}</p>
            <p><strong>Category:</strong> {{ $activity->category->title ?? '-' }}</p>
            <p><strong>User:</strong> {{ $activity->user->name ?? '-' }}</p>
            
            @if($activity->image)
                <p><strong>Image:</strong><br>
                    <img src="{{ asset('storage/'.$activity->image) }}" width="150">
                </p>
            @endif

            <a href="{{ route('activities.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-warning">Edit</a>
        </div>
    </section>
</div>
@endsection
