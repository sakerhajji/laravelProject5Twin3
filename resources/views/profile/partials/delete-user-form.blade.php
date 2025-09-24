<section>
  <header class="mb-3">
    <h5 class="mb-1">{{ __('Delete Account') }}</h5>
    <p class="text-muted mb-0">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
  </header>

  <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
    {{ __('Delete Account') }}
  </button>

  <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('Are you sure you want to delete your account?') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="{{ route('profile.destroy') }}">
          @csrf
          @method('delete')
          <div class="modal-body">
            <p class="text-muted">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>
            <div class="mb-3">
              <label for="delete_password" class="form-label">{{ __('Password') }}</label>
              <input id="delete_password" name="password" type="password" class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif" placeholder="{{ __('Password') }}" />
              @if($errors->userDeletion->has('password'))<div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>@endif
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
