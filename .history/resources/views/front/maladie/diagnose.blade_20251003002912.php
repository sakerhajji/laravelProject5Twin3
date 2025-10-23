@extends('layouts.front')
@section('content')
<div class="container">
    <h2>Diagnose Maladies by Asymptômes</h2>
    <form method="POST" action="{{ route('front.maladie.match') }}">
        @csrf
            <label for="asymptome-search" class="form-label fw-bold">Rechercher un asymptome</label>
            <input type="text" class="form-control mb-3" onkeyup="serachFunction()" id="asymptome-search" placeholder="Rechercher...">
            <div class="row" id="asymptomes-container">
                @foreach($asymptomes as $asymptome)
                    <div class="col-md-4 col-sm-6 col-12 mb-2 asymptome-item">
                        <div class="form-check rounded border p-2 bg-light">
                            <input class="form-check-input" type="checkbox" id="asymptome_{{ $asymptome->id }}"
                                   name="asymptomes[]" value="{{ $asymptome->id }}"
                                   {{ (is_array(request('asymptomes')) && in_array($asymptome->id, request('asymptomes'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="asymptome_{{ $asymptome->id }}">
                                <i class="bi bi-thermometer-half me-1"></i>{{ $asymptome->nom }}
                            </label>
                        </div>
                    </div>
                @endforeach
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Find Maladies</button>
    </form>
    <script>
        function serachFunction() {
            let input = document.getElementById('asymptome-search').value.toLowerCase();
            let items = document.getElementsByClassName('asymptome-item');
            Array.from(items).forEach(function(item) {
                let text = item.textContent.toLowerCase();
                if (input === '' || text.includes(input)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <i class="bi bi-heart-pulse me-2"></i>
                    <h5 class="mb-0">Possible Maladies</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('front.maladie.save') }}">
                        @csrf
                        <input type="hidden" name="asymptomes" value="{{ implode(',', request('asymptomes', [])) }}">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Maladie</th>
                                        <th>Matching %</th>
                                        <th>Select</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $result)
                                        <tr>
                                            <td><i class="bi bi-virus me-1"></i>{{ $result['maladie']->nom }}</td>
                                            <td>
                                                <span class="badge bg-info text-dark">{{ $result['percentage'] }}%</span>
                                            </td>
                                            <td>
                                                <input type="radio" name="maladie_id" value="{{ $result['maladie']->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-save"></i> Save to My History</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        </form>
    @endif
</div>
@endsection
