@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card">

                    <div class="card-body">

                        <h5 class="card-title">{{ __('Send Invitation') }}</h5>
                        <hr>

                        <b-alert
                            @if (session('message')) show @endif
                            variant="{{ session('success') ? 'success' : 'danger' }}"
                            dismissible
                        >{{ session('message') }}</b-alert>

                        <form id="create-invitation" method="POST" action="{{ route('invitations') }}">
                            @csrf

                            <div class="form-group row">
                                <label
                                    for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}
                                </label>

                                <div class="col-md-6">
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        autofocus
                                    >
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <loading-submit-button
                                        bclass="btn btn-primary"
                                        bform="create-invitation"
                                        @loading="handleContentLoading"
                                    >
                                        {{ __('Send') }}
                                    </loading-submit-button>

                                    <loading-link
                                        bclass="btn btn-secondary"
                                        bhref="{{ route('invitations') }}"
                                        @loading="handleContentLoading"
                                    >
                                        {{ __('Cancel') }}
                                    </loading-link>

                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
