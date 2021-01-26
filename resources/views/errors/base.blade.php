@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title mt-3 text-center">@yield('code') | @yield('message')</h5>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
