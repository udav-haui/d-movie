@extends('admin.layouts.app')

<?php
 /** @var \App\Combo $combo */
?>

@section('app.title')
    {{ __('Edit - :name', ['name' => $combo->getName()]) }}
@endsection

@section('app.description'){{ __('Edit - :name', ['name' => $combo->getName()]) }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('combos.index') }}">{{ __('Combos Manage') }}</a></li>
    <li class="active">{{ __('Edit - :name', ['name' => $combo->getName()]) }}</li>
@endsection

@section('titlebar.title')
    {{ __('Edit - :name', ['name' => $combo->getName()]) }}
@endsection

@section('head.css')
@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/js/combo/ce.js') }}"></script>
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
                <a href="{{ url()->previous() === url()->current() ? route('combos.index') : url()->previous() }}"
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
        <form id="update-form"
              method="POST"
              action="{{ route('combos.update', ['combo' => $combo]) }}"
              class="col-md-12 form-horizontal"
              enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ __('Edit - :name', ['name' => $combo->getName()]) }}</div>
                        <div class="panel-body">
                            <!-- Combo status -->
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
                                            {{ old('status', $combo->getStatus()) !== NULL && (int)old('status', $combo->getStatus()) === \App\Combo::ENABLE ? 'selected' : '' }}>
                                            {{ __('Enable') }}
                                        </option>
                                        <option value="0"
                                            {{ old('status', $combo->getStatus()) !== NULL && (int)old('status', $combo->getStatus()) === \App\Combo::DISABLE ? 'selected' : '' }}>
                                            {{ __('Disable') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Combo Name -->
                            <div class="form-group">
                                <label class="control-label col-lg-5 col-md-4 col-xs-12 cursor-pointer" for="name">
                                    {{ __('Combo Name') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-lg-3 col-md-5 col-xs-12">
                                    <input placeholder="{{ __('Provide :name', ['name' => __('combo name')]) }}"
                                           type="text"
                                           name="name"
                                           id="name"
                                           class="form-control dmovie-border
                                        @error('name') invalid @enderror"
                                           value="{{ old('name', $combo->getName()) }}"/>

                                    @error('name')
                                    <span class="error text-danger dmovie-error-box">{!! $message !!}</span>
                                    @enderror
                                    <span class="help-block">{{ __('Provide a :name within 255 character.', ['name' => __('combo name')]) }}</span>
                                </div>
                            </div>


                            <!-- Combo price -->
                            <div class="form-group">
                                <label for="price" class="control-label col-lg-5 col-md-4 col-xs-12 cursor-pointer">
                                    {{ __('Combo Price') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-lg-3 col-md-5 col-xs-12">
                                    <div class="input-group">
                                        <input type="number"
                                               placeholder="{{ __('Provide :name', ['name' => __('price')]) }}"
                                               class="form-control dmovie-border @error('name') invalid @enderror"
                                               name="price"
                                               id="price"
                                               value="{{ old('price', $combo->getPrice()) }}"
                                               min="0">
                                        <label for="price" class="input-group-addon">{{ __('VNĐ') }}</label>
                                    </div>
                                    @error('price')
                                    <span class="error text-danger dmovie-error-box">{!! $message !!}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Combo description -->
                            <div class="form-group">
                                <label class="control-label col-lg-5 col-md-4 col-xs-12 cursor-pointer" for="description">
                                    {{ __('Description') }}
                                </label>
                                <div class="col-lg-3 col-md-5 col-xs-12">
                                    <textarea placeholder="{{ __('Provide :name', ['name' => __('description')]) }}"
                                              name="description"
                                              id="description"
                                              rows="10"
                                              dmovie-editor
                                              class="form-control dmovie-border
                                        @error('description') invalid @enderror">{{ old('description', $combo->getDescription()) }}</textarea>
                                    @error('description')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                    <span class="help-block">{{ __('Provide a :name within 255 character.', ['name' => __('description')]) }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
