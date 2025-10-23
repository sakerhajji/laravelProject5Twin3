@extends('layouts.app')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Utilisateur: {{ $user->name }}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="{{ route('admin.progress.index') }}">Progrès</a></div>
      <div class="breadcrumb-item active">{{ $user->name }}</div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-2">Profil</h5>
            <div class="mb-1"><strong>Nom:</strong> {{ $user->name }}</div>
            <div class="mb-1"><strong>Email:</strong> {{ $user->email }}</div>
            <div class="mb-1"><strong>Rôle:</strong> {{ $user->role }}</div>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><h5 class="mb-0">Badges</h5></div>
          <div class="card-body">
            <div class="row">
              @forelse($badges as $badge)
                <div class="col-6 mb-3">
                  <div class="text-center">
                    <div class="mb-1"><i class="{{ $badge->icon }}" style="color: {{ $badge->color }}; font-size: 24px;"></i></div>
                    <div class="small font-weight-bold">{{ $badge->title }}</div>
                    <div class="small text-muted">{{ optional($badge->earned_at)->format('Y-m-d') }}</div>
                  </div>
                </div>
              @empty
                <div class="col-12 text-muted">Aucun badge</div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        @if(auth()->check() && in_array(auth()->user()->role, ['admin','superadmin']))
        <div class="card">
          <div class="card-header"><h5 class="mb-0">Objectifs attribués</h5></div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped mb-0">
                <thead>
                  <tr>
                    <th>Objectif</th>
                    <th>Catégorie</th>
                    <th>Cible</th>
                    <th>Dernière MAJ</th>
                    <th>%</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($assignedObjectives as $o)
                    @php
                      $sum = $o->progresses->sum('value');
                      $pct = $o->target_value > 0 ? min(100, round(($sum/$o->target_value)*100)) : 0;
                      $last = optional($o->progresses->first())->entry_date?->format('Y-m-d');
                    @endphp
                    <tr>
                      <td>{{ $o->title }}</td>
                      <td>{{ ucfirst($o->category) }}</td>
                      <td>{{ number_format($o->target_value,2) }} {{ $o->unit }}</td>
                      <td>{{ $last ?: '-' }}</td>
                      <td>
                        <div class="progress" style="height: 8px;">
                          <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">{{ $pct }}%</small>
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="5" class="text-center text-muted">Aucun objectif attribué</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @endif

        <div class="card">
          <div class="card-header"><h5 class="mb-0">Historique des progrès</h5></div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped mb-0">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Objectif</th>
                    <th>Valeur</th>
                    <th>Note</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($recentProgress as $p)
                    <tr>
                      <td>{{ $p->entry_date?->format('Y-m-d') }}</td>
                      <td>{{ optional($p->objective)->title }}</td>
                      <td>{{ number_format($p->value, 2) }}</td>
                      <td class="text-muted small">{{ Str::limit($p->note, 80) }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="4" class="text-center text-muted">Aucun progrès</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection


