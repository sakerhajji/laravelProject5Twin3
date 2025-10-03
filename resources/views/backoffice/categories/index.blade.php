@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="py-4 bg-primary text-white rounded-4 p-4 shadow-lg text-center">
                <h2 class="fw-bold mb-2">Manage Categories</h2>
                <p class="mb-0">Create, update and organize all categories easily</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-folder"></i> Categories List</h4>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Add Category
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Title</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by title...">
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/'.$category->image) }}" class="rounded" width="60" height="60" style="object-fit:cover;">
                                @else
                                    <span class="badge bg-secondary">No Image</span>
                                @endif
                            </td>
                            <td>{{ $category->title }}</td>
                            <td>{{ $category->description }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                    data-id="{{ $category->id }}" 
                                    data-title="{{ $category->title }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No categories found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $categories->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="deleteForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="categoryTitle"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
            </div>
        </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    const deleteForm = document.getElementById("deleteForm");
    const categoryTitle = document.getElementById("categoryTitle");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function () {
            const id = this.dataset.id;
            const title = this.dataset.title;
            deleteForm.action = `/admin/categories/${id}`;
            categoryTitle.textContent = title;
            const modal = new bootstrap.Modal(document.getElementById("deleteModal"));
            modal.show();
        });
    });
});
</script>
@endpush
