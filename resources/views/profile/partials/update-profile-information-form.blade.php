<section>
    <header>
        <h2 class="h4 font-weight-medium text-dark">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-muted">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @if ($errors->get('name'))
                <div class="invalid-feedback d-block">
                    @foreach ($errors->get('name') as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @if ($errors->get('email'))
                <div class="invalid-feedback d-block">
                    @foreach ($errors->get('email') as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-muted">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link p-0">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-success font-weight-medium">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
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
