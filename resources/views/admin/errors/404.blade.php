@extends('admin.layouts.app')

@section('app.title', __('Not Found'))

@section('content')


    <section id="wrapper" class="error-page">
        <div class="error-box">
            <div class="error-body text-center">
                <h1 class="text-danger">404</h1>
                <h3 class="text-uppercase">{{ __('Page Not Found !') }}</h3>
                <p class="text-muted m-t-30 m-b-30">{{ __($exception->getMessage()) }}</p>
                <a href="{{ url()->previous() }}"
                   class="btn btn-info btn-rounded waves-effect waves-light m-b-40">{{ __('Back to previous') }}</a>
            </div>
        </div>
    </section>

@endsection
