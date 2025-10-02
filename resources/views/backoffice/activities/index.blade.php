@extends('layouts.app')

@section('title', 'Activities')

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
                                <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">
                                        Delete
                                    </button>
                                </form>
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
@endsection
