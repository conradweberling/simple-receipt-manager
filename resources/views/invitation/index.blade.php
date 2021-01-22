@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <b-alert
                    @if (session('message')) show @endif
                variant="{{ session('success') ? 'success' : 'danger' }}"
                    dismissible
                >{{ session('message') }}</b-alert>

                <loading-link bclass="btn btn-primary w-100" bhref="{{ route('invitations.create') }}">
                    New Invitation
                </loading-link>

                <hr class="my-4">

                <invitation-list route="{{route('invitations')}}"></invitation-list>

            </div>
        </div>
    </div>
@endsection
