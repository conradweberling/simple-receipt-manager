<div class="card">

    <div class="card-body">

        <h5 class="card-title">{{ __('Send Invitation') }}</h5>
        <hr>

        <b-alert
            @if (session('message')) show @endif
            variant="{{ session('success') ? 'success' : 'danger' }}"
            dismissible
        >{{ session('message') }}</b-alert>

        <form method="POST" action="{{ route('invitations') }}">
            @csrf

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Send') }}
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
