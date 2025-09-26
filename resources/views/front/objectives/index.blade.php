@extends('layouts.front')

@section('title', 'Objectifs disponibles')

@section('content')
<div class="container">
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    <div class="row">
        @foreach($objectives as $o)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">{{ $o->title }}</h5>
                            <span class="badge bg-primary text-white">{{ ucfirst($o->category) }}</span>
                        </div>
                        <p class="text-secondary small">{{ Str::limit($o->description, 120) }}</p>
                        <div class="small text-muted">Cible: {{ number_format($o->target_value,2) }} {{ $o->unit }}</div>
                    </div>
                    <div class="card-footer bg-white">
                        <form action="{{ route('front.objectives.activate', $o) }}" method="post">
                            @csrf
                            <button class="btn btn-sm btn-primary">Activer</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $objectives->links() }}
</div>
@endsection


