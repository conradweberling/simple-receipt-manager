@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card">

                    <div class="card-body">

                        <h5 class="card-title">{{ __('Delete Account') }}</h5>

                        <hr>

                        {{ \Faker\Factory::create()->text }}

                        <hr>

                        <delete-account-form
                            action="{{route('account.destroy')}}"
                            check="{{route('password.check')}}">
                        </delete-account-form>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
