@extends('layouts.app')

@section('title', 'Attributions objectifs')

@section('content')
<div class="section-body">
    <div class="section-header"><h1>Attribuer un objectif à un utilisateur</h1></div>
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.objectives.assign') }}" method="post" class="form-inline">
                @csrf
                <div class="form-group mr-2">
                    <select name="user_id" class="form-control">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2">
                    <select name="objective_id" class="form-control">
                        @foreach($objectives as $o)
                            <option value="{{ $o->id }}">{{ $o->title }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary">Attribuer</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h4>Attributions actuelles</h4></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Objectif</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($links as $link)
                            <tr>
                                <td>{{ $link->user->name }}</td>
                                <td>{{ $link->objective->title }}</td>
                                <td><span class="badge badge-success">{{ ucfirst($link->status) }}</span></td>
                                <td class="text-right">
                                    <form action="{{ route('admin.objectives.unassign', $link) }}" method="post" onsubmit="return confirm('Supprimer l\'attribution ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Retirer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


