@extends('layouts/contentNavbarLayout')

@section('title', 'Mon Profil')

@section('content')
<div class="row">
  <div class="col-12 col-lg-6">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Informations du profil</h5>
      </div>
      <div class="card-body">
        @include('profile.partials.update-profile-information-form')
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Mettre à jour le mot de passe</h5>
      </div>
      <div class="card-body">
        @include('profile.partials.update-password-form')
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Supprimer le compte</h5>
      </div>
      <div class="card-body">
        @include('profile.partials.delete-user-form')
      </div>
    </div>
  </div>
</div>
@endsection
