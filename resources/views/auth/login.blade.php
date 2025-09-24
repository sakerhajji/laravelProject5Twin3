@extends('layouts/blankLayout')

@section('title', 'Connexion')

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card">
        <div class="card-body">
          <h4 class="mb-2">Bienvenue 👋</h4>
          <p class="mb-4">Connectez-vous à votre compte</p>

          @if (session('status'))
            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
          @endif

          <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">Mot de passe</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" />
              </div>
              @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                <label class="form-check-label" for="remember"> Se souvenir de moi </label>
              </div>
            </div>

            <div class="mb-3 d-flex justify-content-between align-items-center">
              @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="small">Mot de passe oublié ?</a>
              @endif
              <button class="btn btn-primary" type="submit">Se connecter</button>
            </div>
          </form>

          <p class="text-center">
            <span>Nouveau ici?</span>
            <a href="{{ route('register') }}"> Créer un compte </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
