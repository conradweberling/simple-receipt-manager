@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="pb-4">
                    @include('invitation.create')
                </div>

                <div class="card">
                    <div class="card-header">{{ __('Pending Invitations') }}</div>

                    <div class="card-body">
                        <invitation-list route="{{route('invitations')}}"></invitation-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
