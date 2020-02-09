<?php /** @var \App\Slider $slider */ ?>
@extends('admin.layouts.app')

@section('app.title'){{ __('Edit slide item') }}
@endsection

@section('app.description'){{ __('Edit slide item') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('sliders.index') }}">{{ __('Slider Manage') }}</a></li>
    <li class="active">{{ __('Edit slide item') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Edit slide item') }}
@endsection

@section('head.css')
@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/js/slider/create.js') }}"></script>
@endsection

@section('content')
    @include('admin.lang.global_text_lang')
    <div class="row bg-title" id="dmovie-fix-top-block">
        @can('update', \App\Slider::class)
            <div class="col-lg-3 col-md-4 col-xs-12 pull-right">
                <a href="javascript:void(0);"
                   onclick="event.preventDefault(); $('#create-form').submit();"
                   class="btn btn-block btn-default dmovie-btn dmovie-btn-success">
                    {{ __('Save') }}
                </a>
            </div>
        @endcan
        <div class="col-lg-3 col-md-4 col-xs-12 pull-right">
            <a href="{{ route('sliders.index') }}"
               class="btn btn-block btn-default dmovie-btn dmovie-btn-default">
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <div class="row">
        <form id="create-form"
              method="POST"
              action="{{ route('sliders.update', ['slider' => $slider]) }}"
              class="col-md-12 form-horizontal"
              enctype="multipart/form-data">
            {{ method_field('PUT') }}
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ $slider->getTitle() ??  __('Unnamed') }}</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="status">
                                    {{ __('Status') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select name="status" id="status"
                                            class="bs-select-hidden"
                                            data-style="form-control">
                                        <option value="1"
                                            {{ old('status', $slider->getStatus()) !== NULL && (int)old('status', $slider->getStatus()) === 1 ? 'selected' : '' }}>
                                            {{ __('Enable') }}
                                        </option>
                                        <option value="0"
                                            {{ old('status', $slider->getStatus()) !== NULL && (int)old('status', $slider->getStatus()) === 0 ? 'selected' : '' }}>
                                            {{ __('Disable') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="title">
                                    {{ __('Title') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input
                                        type="text"
                                        name="title"
                                        id="title"
                                        class="form-control dmovie-border
                                        @error('title') invalid @enderror"
                                        value="{{ old('title', $slider->getTitle()) }}"/>
                                    @error('title')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="href">
                                    {{ __('Link') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <textarea
                                        name="href"
                                        id="href"
                                        rows="3"
                                        class="form-control dmovie-border
                                        @error('href') invalid @enderror">{{ old('href', $slider->getHref()) }}</textarea>
                                    @error('href')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="image">
                                    {{ __('Image') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input type="file"
                                           required
                                           name="image"
                                           id="image"
                                           class="dropify col-md-3"
                                           data-default-file="{{ !$slider->getImagePath() ? $slider->getImagePath() : '' }}"
                                    />
                                    @error('image')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="order">
                                    {{ __('Order') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input
                                        type="number"
                                        name="order"
                                        id="order"
                                        class="form-control dmovie-border
                                        @error('order') invalid @enderror"
                                        value="{{ old('order', $slider->getAttribute('order')) }}"/>
                                    @error('order')
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
