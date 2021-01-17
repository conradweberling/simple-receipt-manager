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

                <a href="{{route('receipts.create')}}" class="btn btn-primary w-100">Create Receipt</a>

                <hr class="my-4">

                <receipt-list route="{{route('receipts')}}" base="{{url('/')}}"></receipt-list>

            </div>
        </div>
    </div>
@endsection
