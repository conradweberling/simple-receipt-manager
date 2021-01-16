@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <receipt-list route="{{route('receipts')}}" base="{{url('/')}}"></receipt-list>

                <?php
                /**
                <hr>

                <div class="pb-4">
                    @include('receipt.create')
                </div>
                */
                ?>


            </div>
        </div>
    </div>
@endsection
