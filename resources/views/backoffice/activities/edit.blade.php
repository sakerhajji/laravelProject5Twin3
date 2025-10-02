@extends('layouts.app')

@section('title', isset($activity) ? 'Edit Activity' : 'Create Activity')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ isset($activity) ? 'Edit Activity' : 'Create Activity' }}</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ isset($activity) ? 'Edit Activity' : 'Create Activity' }}</h2>
            <p class="section-lead">
                {{ isset($activity) ? 'Edit the activity details below.' : 'Fill the form to create a new activity.' }}
            </p>

            {{-- Display all errors at the top --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops! There were some problems with your input:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ isset($activity) ? route('admin.activities.update', $activity->id) : route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($activity))
                    @method('PUT')
                @endif

                {{-- Title --}}
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" id="title" placeholder="Enter title" value="{{ old('title', $activity->title ?? '') }}">
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" id="description" placeholder="Enter description">{{ old('description', $activity->description ?? '') }}</textarea>
                </div>

                {{-- Time --}}
                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="number" name="time" class="form-control" id="time" placeholder="Enter time" value="{{ old('time', $activity->time ?? '') }}">
                </div>

                {{-- Category --}}
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" class="form-control" id="category_id">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $activity->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Image --}}
                <div class="form-group">
                    <label for="image">Image (jpg, jpeg, png, gif)</label>
                    <input type="file" name="image" class="form-control" id="image">
                    @if(isset($activity) && $activity->image)
                        <br>
                        <img src="{{ asset('storage/'.$activity->image) }}" width="100">
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">{{ isset($activity) ? 'Update' : 'Save' }}</button>
                <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </section>
</div>
@endsection
