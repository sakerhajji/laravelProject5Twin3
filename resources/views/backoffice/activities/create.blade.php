@extends('layouts.app')

@section('title', 'Create Activity')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Create Activity</h1>
        </div>

        <div class="section-body">
            <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                    @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="text" name="time" value="{{ old('time') }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected':'' }}>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Image (optional)</label>
                    <input type="file" name="image" class="form-control-file">
                </div>

                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('activities.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </section>
