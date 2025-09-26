@extends('layouts.front')

@section('title', 'Workout editor')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Workout editor</h1>
        <button class="btn btn-primary btn-lg"><i class="fa-solid fa-plus me-2"></i>Create workout</button>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <img src="https://images.unsplash.com/photo-1599050751791-2a85b001f4a2?q=80&w=1400&auto=format&fit=crop" class="img-fluid rounded-4 shadow-sm w-100" alt="workout" />
        </div>
        <div class="col-lg-3">
            <div class="mb-3">
                <label class="form-label fw-semibold">Workout name</label>
                <input type="text" class="form-control form-control-lg" placeholder="Indoor strength workout" />
            </div>
            <div class="">
                <label class="form-label fw-semibold">Difficulty</label>
                <select class="form-select form-select-lg">
                    <option>Beginner</option>
                    <option>Intermediate</option>
                    <option>Advanced</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea class="form-control form-control-lg" rows="5" placeholder="Add description..."></textarea>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Days</h4>
                        <button class="btn btn-sm btn-outline-primary" id="btnAddDay"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    <div id="daysList" class="vstack gap-2">
                        <div class="form-control py-3">Day 1</div>
                        <div class="form-control py-3">Day 2</div>
                        <div class="form-control py-3">Day 3</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Activities</h4>
                        <button class="btn btn-sm btn-outline-primary" id="btnAddActivity"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    <div id="activitiesList" class="vstack gap-2">
                        <div class="border rounded-3 p-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1583454110551-21f2fa2f8261?q=80&w=200&auto=format&fit=crop" class="rounded" width="48" height="48" alt="squat" />
                                <div>
                                    <div class="fw-semibold">Squat</div>
                                    <div class="text-secondary small">5 sets</div>
                                </div>
                            </div>
                            <input type="checkbox" class="form-check-input" checked>
                        </div>
                        <div class="border rounded-3 p-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1583454159594-2b2b1f5d0c71?q=80&w=200&auto=format&fit=crop" class="rounded" width="48" height="48" alt="bicep" />
                                <div>
                                    <div class="fw-semibold">Bicep curl</div>
                                    <div class="text-secondary small">3 sets</div>
                                </div>
                            </div>
                            <input type="checkbox" class="form-check-input">
                        </div>
                        <div class="border rounded-3 p-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1517963628607-235ccdd5476f?q=80&w=200&auto=format&fit=crop" class="rounded" width="48" height="48" alt="run" />
                                <div>
                                    <div class="fw-semibold">Running</div>
                                    <div class="text-secondary small">10 min</div>
                                </div>
                            </div>
                            <input type="checkbox" class="form-check-input">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="https://images.unsplash.com/photo-1599050751791-2a85b001f4a2?q=80&w=400&auto=format&fit=crop" class="rounded" width="84" height="84" alt="preview" />
                        <div>
                            <h5 class="mb-1">Bicep curl</h5>
                            <div class="text-secondary small">3 sets</div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mb-3 flex-wrap">
                        <div class="border rounded-3 px-3 py-2 text-center">
                            <div class="fw-semibold small">12x</div>
                            <div class="text-secondary small">20 kg</div>
                        </div>
                        <div class="border rounded-3 px-3 py-2 text-center">
                            <div class="fw-semibold small">10x</div>
                            <div class="text-secondary small">22 kg</div>
                        </div>
                        <div class="border rounded-3 px-3 py-2 text-center">
                            <div class="fw-semibold small">8x</div>
                            <div class="text-secondary small">24 kg</div>
                        </div>
                        <button class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    <textarea class="form-control" rows="3" placeholder="Add note..."></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Demo only: add simple rows for days and activities
    document.getElementById('btnAddDay')?.addEventListener('click', function(){
        const list = document.getElementById('daysList');
        const count = list.children.length + 1;
        const div = document.createElement('div');
        div.className = 'form-control py-3';
        div.textContent = 'Day ' + count;
        list.appendChild(div);
    });
    document.getElementById('btnAddActivity')?.addEventListener('click', function(){
        const list = document.getElementById('activitiesList');
        const tpl = list.children[0].cloneNode(true);
        list.appendChild(tpl);
    });
</script>
@endpush
@endsection


