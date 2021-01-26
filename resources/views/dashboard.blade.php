@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-7 col-xl-6">

            <b-alert
                @if (session('message')) show @endif
            variant="{{ session('success') ? 'success' : 'danger' }}"
                dismissible
            >{{ session('message') }}</b-alert>

            <dashboard route="{{route('dashboard.chart')}}"></dashboard>

        </div>
    </div>
</div>
@endsection
