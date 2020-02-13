@extends('admin.layouts.app')

<?php
    /** @var \App\Cinema $cinema */
?>

@section('app.title')
    {{ __('New Show For :cName', ['cName' => $cinema->getName()]) }}
@endsection

@section('app.description'){{ __('New Show For :cName', ['cName' => $cinema->getName()]) }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('cinemas.edit', ['cinema' => $cinema]) }}">{{ $cinema->getName() }}</a></li>
    <li class="active">{{ __('New Show') }}</li>
@endsection

@section('titlebar.title')
    {!! __('New Show For <code>:cName</code>', ['cName' => $cinema->getName()]) !!}
@endsection

@section('head.css')

    <link href="{{ asset('adminhtml/assets/plugins/bower_components/summernote/summernote-lite.css') }}" rel="stylesheet">
@endsection

@section('bottom.js')

    <script src="{{ asset('adminhtml/assets/plugins/bower_components/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/plugins/bower_components/summernote/lang/summernote-vi-VN.js') }}"></script>
    <script src="{{ asset('adminhtml/js/show/ce.js') }}"></script>
@endsection

@section('action_button')
    <div class="navbar dmovie-fix-top-container">
        <div class="row bg-title" id="dmovie-fix-top-block">
            <div class="col-md-12">
                <a href="javascript:void(0);"
                   onclick="event.preventDefault(); $('#create-form').submit();"
                   class="btn dmovie-btn dmovie-btn-success dmovie-btn-large pull-right m-r-40">
                    <i class="mdi mdi-content-save"></i>
                    {{ __('Save') }}
                </a>
                <a href="{{ route('cinemas.index') }}"
                   class="btn dmovie-btn m-r-40 dmovie-btn-large pull-right">
                    <i class="mdi mdi-arrow-left"></i>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @include('admin.lang.global_text_lang')

    <div class="row">
        <form id="create-form"
              method="POST"
              action="{{ route('shows.store') }}"
              class="col-md-12 form-horizontal"
              enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ __('New Show') }}</div>
                        <div class="panel-body">
                            {{-- Show status --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="status">
                                    {{ __('Status') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select name="status" id="status"
                                            class="bs-select-hidden"
                                            data-style="form-control"
                                            dmovie-select2>
                                        <option value="1"
                                            {{ old('status') !== NULL && (int)old('status') === 1 ? 'selected' : '' }}>
                                            {{ __('Enable') }}
                                        </option>
                                        <option value="0"
                                            {{ old('status') !== NULL && (int)old('status') === 0 ? 'selected' : '' }}>
                                            {{ __('Disable') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Cinema Name --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="name">
                                    {{ __('Cinema Name') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input type="hidden"
                                           name="cinema_id"
                                           value="{{ $cinema->getId() }}" />
                                    <select id="cinema_id"
                                            class="bs-select-hidden"
                                            data-style="form-control"
                                            dmovie-select2
                                            disabled>
                                        <option value="{{ $cinema->getId() }}"
                                            {{ old('cinema_id', $cinema->getId()) }}>
                                            {{ $cinema->getName() }}
                                        </option>
                                    </select>
                                    @error('cinema_id')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Name --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="name">
                                    {{ __('Show Name') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input placeholder="{{ __('Type a name for new show room') }}"
                                           type="text"
                                           name="name"
                                           id="name"
                                           class="form-control dmovie-border
                                        @error('name') invalid @enderror"
                                           value="{{ old('name') }}"/>
                                    @error('name')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
