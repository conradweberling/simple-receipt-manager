@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card mb-4">

                    <div class="card-body">

                        <h5 class="card-title">{{ __('Change E-Mail') }}</h5>

                        <hr>

                        <account-update-email-form
                            update="{{route('email.update')}}"
                            check="{{route('email.check')}}"
                        ></account-update-email-form>

                    </div>

                </div>


                <div class="card mb-4">

                    <div class="card-body">

                        <h5 class="card-title">{{ __('Change Password') }}</h5>

                        <hr>

                        <account-update-password-form
                            update="{{route('password.update')}}"
                            check="{{route('password.check')}}"
                        ></account-update-password-form>

                    </div>

                </div>

                <div class="card">

                    <div class="card-body">

                        <h5 class="card-title">{{ __('Delete Account') }}</h5>

                        <hr>

                        <p>
                            When an account is deleted, all associated receipts and invitations are
                            <strong>permanently deleted</strong>.
                            It is not possible to restore the data.
                        </p>


                        <hr>

                        <delete-account-form
                            action="{{route('account.destroy')}}"
                            check="{{route('password.check')}}"
                        ></delete-account-form>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
