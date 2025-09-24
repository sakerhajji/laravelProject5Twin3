@extends('layouts/blankLayout')

@section('title', 'Créer un compte')

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card">
        <div class="card-body">
          <h4 class="mb-2">Inscription</h4>
          <p class="mb-4">Créez votre compte Health Tracker</p>

          <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
              <label for="name" class="form-label">Nom</label>
              <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus autocomplete="name">
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3 form-password-toggle">
              <label for="password" class="form-label">Mot de passe</label>
              <div class="input-group input-group-merge">
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
              </div>
              @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3 form-password-toggle">
              <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
              <div class="input-group input-group-merge">
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
              </div>
            </div>

            <div class="mb-3 d-flex justify-content-between align-items-center">
              <a href="{{ route('login') }}" class="small">Déjà inscrit ?</a>
              <button type="submit" class="btn btn-primary">Créer le compte</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
