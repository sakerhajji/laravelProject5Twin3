@extends('layouts.app')

@section('title', 'Edit Activity')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Activity</h1>
        </div>

        <div class="section-body">
            <form action="{{ route('activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" value="{{ old('title', $activity->title) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $activity->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="text" name="time" value="{{ old('time', $activity->time) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" class="form-control">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $activity->category_id==$category->id ? 'selected':'' }}>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Image (optional)</label>
                    @if($activity->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$activity->image) }}" width="100">
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control-file">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('activities.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </section>
</div>
@endsection
    