@extends('admin.layouts.app')

<?php /** @var \App\StaticPage $static_page */ ?>

@section('app.title')
    {{ __('Edit - :name', ['name' => $static_page->getName()]) }}
@endsection

@section('app.description'){{ __('Edit - :name', ['name' => $static_page->getName()]) }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('static-pages.index') }}">{{ __('Static Pages Manage') }}</a></li>
    <li class="active">{{ $static_page->getName() }}</li>
@endsection

@section('titlebar.title')
    {{ __('Edit - :name', ['name' => $static_page->getName()]) }}
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
                   onclick="event.preventDefault(); $('#update-form').submit();"
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

    <div class="row">
        <form id="update-form"
              method="POST"
              action="{{ route('static-pages.update', ['static_page' => $static_page]) }}"
              class="col-md-12 form-horizontal"
              enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ __('Edit - :name', ['name' => $static_page->getName()]) }}</div>
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
                                            data-style="form-control">
                                        <option value="1"
                                            {{ old('status', $static_page->getStatus()) !== NULL && (int)old('status', $static_page->getStatus()) === 1 ? 'selected' : '' }}>
                                            {{ __('Enable') }}
                                        </option>
                                        <option value="0"
                                            {{ old('status', $static_page->getStatus()) !== NULL && (int)old('status', $static_page->getStatus()) === 0 ? 'selected' : '' }}>
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
                                           value="{{ old('name', $static_page->getName()) }}"/>
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
                                           value="{{ old('slug', $static_page->getSlug()) }}"/>
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
                                            {{ old('language', $static_page->getLanguage()) === \App\StaticPage::ENGLISH ? 'selected' : '' }}>
                                            {{ __('English') }}
                                        </option>
                                        <option value="vi"
                                            {{ old('language', $static_page->getLanguage()) === \App\StaticPage::VIETNAM ? 'selected' : '' }}>
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
                                              class="form-control dmovie-border"
                                    >{{ old('content', $static_page->getContent()) }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
