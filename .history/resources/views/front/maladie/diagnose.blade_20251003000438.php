@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Diagnose Maladies by Asymptômes</h2>
    <form method="POST" action="{{ route('front.maladie.match') }}">
        @csrf
        <div class="mb-3">
            <label for="asymptomes" class="form-label">Enter or select Asymptômes:</label>
            <select multiple class="form-control" id="asymptomes" name="asymptomes[]">
                @foreach($asymptomes as $asymptome)
                    <option value="{{ $asymptome->id }}">{{ $asymptome->nom }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Find Maladies</button>
    </form>
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
