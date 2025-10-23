@extends('layouts.front')
@section('content')
<div class="container">
    <h2>Diagnose Maladies by Asymptômes</h2>
    <form method="POST" action="{{ route('front.maladie.match') }}">
        @csrf
        <!-- Asymptomes Section -->
        <div class="form-group mb-2">
            <label>Asymptomes</label>
            <input type="text" class="form-control mb-2" onkeyup="serachFunction()" id="asymptome-search" placeholder="Rechercher un asymptome...">
            <div class="row" id="asymptomes-container">
                @foreach($asymptomes as $asymptome)
                    <div class="col-md-4 col-sm-6 col-12 mb-2 asymptome-item">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="asymptome_{{ $asymptome->id }}"
                                   name="asymptomes[]" value="{{ $asymptome->id }}"
                                   {{ (is_array(request('asymptomes')) && in_array($asymptome->id, request('asymptomes'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="asymptome_{{ $asymptome->id }}">
                                {{ $asymptome->nom }}
                            </label>
                        </div>
                    </div>
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
    @if(isset($results))
        <h3 class="mt-4">Possible Maladies</h3>
        <form method="POST" action="{{ route('front.maladie.save') }}">
            @csrf
            <input type="hidden" name="asymptomes" value="{{ implode(',', request('asymptomes', [])) }}">
            <table class="table">
                <thead>
                    <tr>
                        <th>Maladie</th>
                        <th>Matching %</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                        <tr>
                            <td>{{ $result['maladie']->nom }}</td>
                            <td>{{ $result['percentage'] }}%</td>
                            <td>
                                <input type="radio" name="maladie_id" value="{{ $result['maladie']->id }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-success">Save to My History</button>
        </form>
    @endif
</div>
@endsection
