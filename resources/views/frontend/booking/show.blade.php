@extends('frontend.layouts.app')

@section('head.css')
    <link rel="stylesheet" href="{{asset('frontend/css/booking/index.css')}}">
@endsection

@section('app.title'){{ __('Booking movie tickets') }} - {{ $film->getTitle() }}
@endsection

@section('bottom.js')
    <script src="{{ asset('frontend/js/booking/index.js') }}"></script>
@endsection

<?php
    /** @var \App\Film $film */
    /** @var \App\Time $time */
?>


@section('content')

    <input type="hidden"
           class="lang-text"
           confirm-to-payment-title="{{ __('Confirm to payment?') }}"
           confirm-to-payment-text="{{ __('Please make sure the information you entered is correct before payment.') }}"
           confirm-to-payment-recheck="{{ __('Recheck') }}"
           confirm-to-payment-confirm="{{ __('Confirm') }}"
           unavailable-method="{{ __('This method is unavailable! Please chose other method! Thank you!') }}"
    />


    <div id="app" class="container">

        <select-seats
            user="{{ json_encode(auth()->user()) }}"
            base-url="{{ asset('/') }}"
            film-name="{{ $film->getTitle() }}"
            home-route="{{ route('frontend.home') }}"
            film-id="{{ $film->getId() }}"
            time="{{ json_encode($time) }}"
            show="{{ json_encode($show) }}"
            seats="{{ json_encode($seats) }}"
            combos="{{ json_encode($combos) }}"
            booked-seats="{{ json_encode($bookedSeats) }}"
            mark-alert="{{ json_encode(['ageMark' => $film->getAgeMark(), 'alert' => __('According to the regulations of the Cinema Department, certain films are not for audience under the age of :age.', ['age' => $film->getAgeMark()])]) }}"
        ></select-seats>

    </div>

@endsection
