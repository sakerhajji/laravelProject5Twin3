@extends('layouts.app')

@section('title', 'Objectifs types')

@section('content')
<div class="main-content">
    <div class="section-header">
        <h1>Objectifs types</h1>
        <div class="section-header-button">
            <a href="{{ route('admin.objectives.create') }}" class="btn btn-primary">Nouvel objectif</a>
        </div>
    </div>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="row">
        @foreach($objectives as $o)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    @if($o->cover_url)
                        <img src="{{ $o->cover_url }}" class="card-img-top" alt="{{ $o->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5>{{ $o->title }}</h5>
                            <span class="badge badge-info">{{ ucfirst($o->category) }}</span>
                        </div>
                        <p class="text-muted">{{ Str::limit($o->description, 120) }}</p>
                        <div class="small text-muted">Cible: {{ number_format($o->target_value,2) }} {{ $o->unit }}</div>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="{{ route('admin.objectives.edit', $o) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                        <form action="{{ route('admin.objectives.destroy', $o) }}" method="post" class="ml-2" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $objectives->links() }}
</div>
@endsection


