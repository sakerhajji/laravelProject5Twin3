@extends('layouts.front')

@section('title', 'Objectifs disponibles')

@section('content')
<div class="container">
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    @isset($recommended)
    <div class="mb-4">
        <h4 class="fw-bold mb-3">Recommandés pour vous</h4>
        <div id="recCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($recommended as $idx=>$r)
                <div class="carousel-item {{ $idx===0?'active':'' }}">
                    <div class="card border-0 shadow-sm overflow-hidden">
                        @if($r->cover_url)
                            <img src="{{ $r->cover_url }}" class="w-100" style="max-height:240px;object-fit:cover" alt="rec">
                        @endif
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">{{ $r->title }}</h5>
                                <div class="text-secondary small">{{ Str::limit($r->description, 140) }}</div>
                            </div>
                            <form action="{{ route('front.objectives.activate', $r) }}" method="post">
                                @csrf
                                <button class="btn btn-primary"><i class="fa-solid fa-bullseye me-1"></i>Activer</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#recCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#recCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    @endisset
    <div class="row">
        @foreach($objectives as $o)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm overflow-hidden">
                    @if($o->cover_url)
                        <img src="{{ $o->cover_url }}" class="card-img-top" alt="{{ $o->title }}">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="mb-1">{{ $o->title }}</h5>
                            <span class="badge bg-primary text-white">{{ ucfirst($o->category) }}</span>
                        </div>
                        <p class="text-secondary small">{{ Str::limit($o->description, 110) }}</p>
                        <div class="small text-muted">Cible: {{ number_format($o->target_value,2) }} {{ $o->unit }}</div>
                    </div>
                    <div class="card-footer bg-white d-flex gap-2">
                        <form action="{{ route('front.objectives.activate', $o) }}" method="post" class="mr-2">
                            @csrf
                            <button class="btn btn-sm btn-primary"><i class="fa-solid fa-bullseye me-1"></i>Activer</button>
                        </form>
                        <a href="{{ route('front.progress.index') }}" class="btn btn-sm btn-outline-secondary">Mes progrès</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $objectives->links() }}
</div>
@endsection


