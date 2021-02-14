@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title">{{ __('Create Receipt') }}</h5>
                        <hr>

                        @if (session('message'))
                            <div class="alert alert-{{ session('success') ? 'success' : 'danger' }}" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        <form id="receipt-create" method="POST" action="{{ route('receipts.store') }}" enctype="multipart/form-data">
                            @csrf

                            <store-image
                                route="{{route('images.store')}}"
                                @error('image') error="{{ $message }}" @enderror
                            ></store-image>

                            <div class="form-group row">
                                <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>

                                <div class="col-md-6">

                                    <b-form-datepicker
                                        id="date"
                                        class="mb-2 @error('date') is-invalid @enderror"
                                        name="date"
                                        value="{{ (old('date')) }}"
                                        today-button
                                        reset-button
                                        close-button
                                        required
                                    ></b-form-datepicker>

                                    @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="amount" class="col-md-4 col-form-label text-md-right">{{ __('Amount') }}</label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input
                                            id="amount"
                                            type="number"
                                            min="0.00"
                                            max="1000.00"
                                            step="0.01"
                                            class="form-control @error('amount') is-invalid @enderror"
                                            name="amount"
                                            value="{{ (old('amount')) }}"
                                            required
                                        >
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="test">€</span>
                                        </div>
                                    </div>

                                    @error('amount')
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
                                        bform="receipt-create"
                                        @loading="handleContentLoading"
                                    >
                                        {{ __('Save') }}
                                    </loading-submit-button>

                                    <loading-link
                                        bclass="btn btn-secondary"
                                        bhref="{{ route('receipts') }}"
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
