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

                        <form method="POST" action="{{ route('receipts.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="image" class="col-md-4 col-form-label text-md-right">{{ __('Receipt') }}</label>

                                <div class="col-md-6">

                                    <b-form-file
                                        id="image"
                                        name="image"
                                        placeholder="Choose a file or drop it here..."
                                        drop-placeholder="Drop file here..."
                                        @if(old('image')) value="{{ old('image') }}" @endif
                                        class="@error('image') is-invalid @enderror"
                                        required
                                        autofocus
                                    ></b-form-file>

                                    @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

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
                                    <input
                                        id="amount"
                                        type="text"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        name="amount"
                                        value="{{ (old('amount')) }}"
                                        required
                                    >

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
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save') }}
                                    </button>
                                    <button class="btn btn-secondary" onclick="window.history.back();">
                                        {{ __('Cancel') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
