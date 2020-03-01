@extends('frontend.layouts.app')

@section('app.title'){{ __('Not Found') }}
@endsection

@section('content')
    <section id="wrapper" class="error-page margin-bottom-30">
        <div class="error-box">
            <div class="error-body text-center " style="font-size: 3em">
                <h1 class="text-danger" style="font-size: 3em">404</h1>
                <h3 class="text-uppercase" style="font-size: 1em">{{ __('Page Not Found !') }}</h3>
                <p class="text-muted m-t-30 m-b-30" style="font-size: .5em">
                    {{ __($exception->getMessage()) }}
                </p>
                <a href="{{ route('frontend.home') }}"
                   class="btn btn-danger btn-rounded waves-effect waves-light m-b-40">{{ __('Back to home') }}</a>
            </div>
        </div>
    </section>
@endsection
