@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title">{{ __('Dashboard') }}</h5>
                    <hr>

                    <b-alert
                        @if (session('message')) show @endif
                        variant="{{ session('success') ? 'success' : 'danger' }}"
                        dismissible
                    >{{ session('message') }}</b-alert>

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
