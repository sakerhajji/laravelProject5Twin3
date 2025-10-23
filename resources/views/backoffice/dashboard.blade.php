@extends('layouts.app')

@section('title', 'Backoffice')

@section('content')
<div class="main-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard Backoffice</div>
                <div class="card-body">
                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <h5>Hello admin</h5>
                    @else
                        <h5>Hello user</h5>
                    @endif
                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


