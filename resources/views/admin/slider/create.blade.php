@extends('admin.layouts.app')

@section('app.title'){{ __('Slider Manage') }}
@endsection

@section('app.description'){{ __('Slider Manage') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('sliders.index') }}">{{ __('Slider Manage') }}</a></li>
    <li class="active">{{ __('New item') }}</li>
@endsection

@section('titlebar.title')
    {{ __('New item') }}
@endsection

@section('head.css')
@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/js/slider/create.js') }}"></script>
@endsection


@section('action_button')
    <div class="navbar dmovie-fix-top-container">
        <div class="row bg-title" id="dmovie-fix-top-block">
            <a href="javascript:void(0);"
               onclick="event.preventDefault(); $('#create-form').submit();"
               class="btn dmovie-btn dmovie-btn-success dmovie-btn-large m-r-40 pull-right">
                {{ __('Save') }}
            </a>
            <a href="{{ route('sliders.index') }}"
               class="btn dmovie-btn dmovie-btn-default dmovie-btn-large m-r-40 pull-right">
                {{ __('Back') }}
            </a>
        </div>
    </div>
    @endsection





@section('content')
    @include('admin.lang.global_text_lang')



    <div class="row">
        <form id="create-form"
              method="POST"
              action="{{ route('sliders.store') }}"
              class="col-md-12 form-horizontal"
              enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ __('New item') }}</div>
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
                                        value="{{ old('title') }}"/>
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
                                        @error('href') invalid @enderror">{{ old('href') }}</textarea>
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
                                    <input type="file" required name="image" id="image" class="dropify col-md-3" />
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
                                        value="{{ old('order') }}"/>
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
