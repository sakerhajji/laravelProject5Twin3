@extends('layouts.app')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Progrès (Front Office)</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
      <div class="breadcrumb-item active">Progrès</div>
    </div>
  </div>

  <div class="section-body">
    @if(session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
      <div class="card-header">
        <h4>Filtres</h4>
      </div>
      <div class="card-body">
        <form method="GET" action="{{ route('admin.progress.index') }}" class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Utilisateur</label>
            <select name="user_id" class="form-control select2">
              <option value="">Tous</option>
              @foreach($users as $u)
                <option value="{{ $u->id }}" {{ request('user_id')==$u->id?'selected':'' }}>{{ $u->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Objectif</label>
            <select name="objective_id" class="form-control select2">
              <option value="">Tous</option>
              @foreach($objectives as $o)
                <option value="{{ $o->id }}" {{ request('objective_id')==$o->id?'selected':'' }}>{{ $o->title }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Du</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control"/>
          </div>
          <div class="col-md-2">
            <label class="form-label">Au</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control"/>
          </div>
          <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-primary"><i class="fas fa-filter mr-1"></i>Filtrer</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.progress.export', request()->all()) }}">
              <i class="fas fa-download mr-1"></i>CSV
            </a>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h4>Résultats</h4>
        <div class="card-header-action">
          <a class="btn btn-outline-secondary" href="{{ route('admin.progress.export', request()->all()) }}">
            <i class="fas fa-download mr-1"></i>Exporter CSV
          </a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <thead>
              <tr>
                <th>Utilisateur</th>
                <th>Objectif</th>
                <th>Date</th>
                <th>Valeur</th>
                <th>Note</th>
                <th class="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($progresses as $p)
                <tr>
                  <td>{{ optional($p->user)->name }}</td>
                  <td>{{ optional($p->objective)->title }}</td>
                  <td>{{ $p->entry_date?->format('Y-m-d') }}</td>
                  <td>{{ number_format($p->value, 2) }}</td>
                  <td class="text-muted small">{{ Str::limit($p->note, 80) }}</td>
                  <td class="text-right">
                    <form method="POST" action="{{ route('admin.progress.destroy', $p) }}" onsubmit="return confirm('Supprimer cette entrée ?')" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">Aucune donnée</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer text-right">
        {{ $progresses->links() }}
      </div>
    </div>
  </div>
</section>
@endsection


