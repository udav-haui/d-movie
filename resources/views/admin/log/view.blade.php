@extends('admin.layouts.app')

<?php /** @var \App\Log $log */ ?>

@section('app.title')
    {!! __('Log ID: :id', ['id' => $log->getId()]) !!}
@endsection

@section('app.description'){!! __('Log ID: :id', ['id' => $log->getId()]) !!}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('logs.index') }}">{{ __('System Logs') }}</a></li>
    <li class="active">{!! __('Log ID: <code>:id</code>', ['id' => $log->getId()]) !!}</li>
@endsection

@section('titlebar.title')
    {!! __('Log ID: <code>:id</code>', ['id' => $log->getId()]) !!}
@endsection

@section('head.css')
@endsection

@section('bottom.js')

@endsection

@section('action_button')
    <div class="navbar dmovie-fix-top-container">
        <div class="row bg-title" id="dmovie-fix-top-block">
            <div class="col-md-12">
                <a href="{{ url()->previous() === url()->current() ? route('logs.index') : url()->previous() }}"
                   class="btn dmovie-btn m-r-40 dmovie-btn-large pull-right">
                    <i class="mdi mdi-arrow-left"></i>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">{!! __('Log ID: <code>:id</code>', ['id' => $log->getId()]) !!}</div>
                <div class="panel-body">
                    <h3 class="box-title">{{ __('Log change') }}</h3>
                    {!! echo_log_recursive($log->getTargetModel(), $log->getMessage()) !!}
                    @dump($log->getMessage())
                </div>
            </div>
        </div>
    </div>
@endsection
