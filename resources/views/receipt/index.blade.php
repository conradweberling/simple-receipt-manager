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

                <receipt-list
                    route="{{route('receipts')}}"
                    base="{{url('/')}}"
                    create="{{route('receipts.create')}}"
                    destroy="{{route('receipts.destroy', 'replaceid')}}"
                    @loading="handleContentLoading"
                ></receipt-list>

            </div>
        </div>
    </div>
@endsection
