@extends('admin.layouts.app')

@section('app.title')
    {{ __('New Page') }}
@endsection

@section('app.description'){{ __('New Page') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('static-pages.index') }}">{{ __('Static Pages Manage') }}</a></li>
    <li class="active">{{ __('New Page') }}</li>
@endsection

@section('titlebar.title')
    {{ __('New Page') }}
@endsection

@section('head.css')

<link href="{{ asset('adminhtml/assets/plugins/bower_components/summernote/summernote-lite.css') }}" rel="stylesheet">
@endsection

@section('bottom.js')

<script src="{{ asset('adminhtml/assets/plugins/bower_components/summernote/summernote-lite.min.js') }}"></script>
<script src="{{ asset('adminhtml/assets/plugins/bower_components/summernote/lang/summernote-vi-VN.js') }}"></script>
<script src="{{ asset('adminhtml/js/static_page/ce.js') }}"></script>
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
                <a href="{{ route('static-pages.index') }}"
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
              action="{{ route('static-pages.store') }}"
              class="col-md-12 form-horizontal"
              enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ __('New Page') }}</div>
                        <div class="panel-body">
                            <!-- Page status -->
                            <div class="form-group">
                                <label class="control-label col-lg-5 col-md-4 col-xs-12 cursor-pointer" for="status">
                                    {{ __('Status') }}
                                </label>
                                <div class="col-lg-3 col-md-5 col-xs-12">
                                    <select name="status" id="status"
                                            class="bs-select-hidden"
                                            dmovie-select2
                                            data-style="form-control"
                                    >
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

                            <!-- Page Name -->
                            <div class="form-group">
                                <label class="control-label col-lg-5 col-md-4 col-xs-12 cursor-pointer" for="name">
                                    {{ __('Page Name') }}
                                </label>
                                <div class="col-lg-3 col-md-5 col-xs-12">
                                    <input placeholder="{{ __('Provide :name', ['name' => __('page name')]) }}"
                                           type="text"
                                           name="name"
                                           id="name"
                                           class="form-control dmovie-border
                                        @error('name') invalid @enderror"
                                           value="{{ old('name') }}"/>
                                    @error('name')
                                    <span class="error text-danger dmovie-error-box">{!! $message !!}</span>
                                    @enderror
                                </div>
                            </div>


                            <!-- Page Slug -->
                            <div class="form-group">
                                <label class="control-label col-lg-5 col-md-4 col-xs-12 cursor-pointer" for="slug">
                                    {{ __('Page Slug') }}
                                </label>
                                <div class="col-lg-3 col-md-5 col-xs-12">
                                    <input placeholder="{{ __('Provide :name', ['name' => __('page slug')]) }}"
                                           type="text"
                                           name="slug"
                                           id="slug"
                                           class="form-control dmovie-border
                                        @error('slug') invalid @enderror"
                                           value="{{ old('slug') }}"/>
                                    @error('slug')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <!-- Page status -->
                            <div class="form-group">
                                <label class="control-label col-lg-5 col-md-4 col-xs-12 cursor-pointer" for="language">
                                    {{ __('Page Language') }}
                                </label>
                                <div class="col-lg-3 col-md-5 col-xs-12">
                                    <select name="language" id="language"
                                            class="bs-select-hidden"
                                            dmovie-select2
                                            data-style="form-control">
                                        <option value="en"
                                            {{ old('language', app()->getLocale()) === \App\StaticPage::ENGLISH ? 'selected' : '' }}>
                                            {{ __('English') }}
                                        </option>
                                        <option value="vi"
                                            {{ old('language', app()->getLocale()) === \App\StaticPage::VIETNAM ? 'selected' : '' }}>
                                            {{ __('Vietnamese') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Page content -->
                            <div class="form-group">
                                <label class="control-label col-lg-5 col-md-4 col-xs-12 cursor-pointer" for="content">
                                    {{ __('Page Content') }}
                                </label>
                                <div class="col-lg-3 col-md-5 col-xs-12">
                                    <textarea placeholder="{{ __('Provide :name', ['name' => __('page content')]) }}"
                                              name="content"
                                              id="content"
                                              rows="10"
                                              dmovie-editor
                                              class="form-control dmovie-border
                                        @error('content') invalid @enderror">{{ old('content') }}</textarea>
                                    @error('content')
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
