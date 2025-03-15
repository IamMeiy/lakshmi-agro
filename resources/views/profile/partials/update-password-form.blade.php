<section>
    <header>
        <h2 class="h4 font-weight-medium text-dark">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-muted">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
            @if ($errors->updatePassword->get('current_password'))
                <div class="invalid-feedback d-block">
                    @foreach ($errors->updatePassword->get('current_password') as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="update_password_password">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password">
            @if ($errors->updatePassword->get('password'))
                <div class="invalid-feedback d-block">
                    @foreach ($errors->updatePassword->get('password') as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
            @if ($errors->updatePassword->get('password_confirmation'))
                <div class="invalid-feedback d-block">
                    @foreach ($errors->updatePassword->get('password_confirmation') as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p id="savedMessage" class="text-muted mb-0">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

<script>
    // Remove the "Saved" message after 2 seconds
    document.addEventListener('DOMContentLoaded', function() {
        var savedMessage = document.getElementById('savedMessage');
        if (savedMessage) {
            setTimeout(function() {
                savedMessage.style.display = 'none';
            }, 2000);
        }
    });
</script>
