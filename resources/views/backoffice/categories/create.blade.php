@extends('layouts.app')

@section('title', 'Create Category')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Create Category</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Add a New Category</h2>
            <p class="section-lead">Fill the form below to create a new category.</p>

            {{-- Display all validation errors at the top --}}
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

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Title --}}
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" id="title" placeholder="Enter title" value="{{ old('title') }}">
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" id="description" placeholder="Enter description">{{ old('description') }}</textarea>
                </div>

                {{-- Image --}}
                <div class="form-group">
                    <label for="image">Image (jpg, jpeg, png, gif)</label>
                    <input type="file" name="image" class="form-control-file" id="image">
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </section>
</div>
@endsection
