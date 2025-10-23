@extends('layouts.app')
@section('content')
<div class="container">
    <h2>My Maladie History</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($maladies->isEmpty())
        <p>You have not saved any maladies yet.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Maladie</th>
                    <th>Date Saved</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maladies as $maladie)
                    <tr>
                        <td>{{ $maladie->nom }}</td>
                        <td>{{ $maladie->pivot->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
