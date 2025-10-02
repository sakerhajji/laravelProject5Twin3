@extends('layouts.app')

@section('title', 'Activities')

@push('style')
    <!-- Bootstrap 4 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Activities</h1>
        </div>

        <div class="section-body">
            <a href="{{ route('activities.create') }}" class="btn btn-primary mb-3">+ Add Activity</a>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>User</th>
                            <th>Time</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr>
                            <td>{{ $activity->id }}</td>
                            <td>{{ $activity->title }}</td>
                            <td>{{ $activity->category->title ?? '-' }}</td>
                            <td>{{ $activity->user->name ?? '-' }}</td>
                            <td>{{ $activity->time }}</td>
                            <td>
                                @if($activity->image)
                                    <img src="{{ asset('storage/'.$activity->image) }}" width="80">
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('activities.show', $activity->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <!-- Delete Button -->
                                <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                    data-id="{{ $activity->id }}" 
                                    data-title="{{ $activity->title }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">No activities found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Single Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="deleteForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-header">
          <h5 class="modal-title">Confirm Delete</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete "<strong id="activityName"></strong>"?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <!-- Bootstrap 4 JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.delete-btn').click(function(){
                var id = $(this).data('id');
                var title = $(this).data('title');
                $('#activityName').text(title);
                $('#deleteForm').attr('action', '/activities/' + id);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endpush
