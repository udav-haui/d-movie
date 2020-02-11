@extends('admin.layouts.app')

<?php /** @var \App\Repositories\Interfaces\CinemaInterface $cinema */ ?>

@section('app.title')
    {{ $cinema->getName() }} - {{ __('Edit') }}
@endsection

@section('app.description'){{ $cinema->getName() }} - {{ __('Edit') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('films.index') }}">{{ __('Cinemas Manage') }}</a></li>
    <li class="active">{{ $cinema->getName() }}</li>
@endsection

@section('titlebar.title')
    {{ $cinema->getName() }} - {{ __('Edit') }}
@endsection

@section('head.css')

    <link href="{{ asset('adminhtml/assets/plugins/bower_components/summernote/summernote-lite.css') }}" rel="stylesheet">
@endsection

@section('bottom.js')

    <script src="{{ asset('adminhtml/assets/plugins/bower_components/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/plugins/bower_components/summernote/lang/summernote-vi-VN.js') }}"></script>
    <script src="{{ asset('adminhtml/js/cinema/ce.js') }}"></script>
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
        <form id="update-form"
              method="POST"
              action="{{ route('cinemas.update', ['cinema' => $cinema->getId()]) }}"
              class="col-md-12 form-horizontal"
              enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ $cinema->getName() }} - {{ __('Edit') }}</div>
                        <div class="panel-body">
                            {{-- Cinema status --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="status">
                                    {{ __('Status') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select name="status" id="status"
                                            class="bs-select-hidden"
                                            data-style="form-control">
                                        <option value="1"
                                            {{ old('status', $cinema->getStatus()) !== NULL && (int)old('status', $cinema->getStatus()) === 1 ? 'selected' : '' }}>
                                            {{ __('Enable') }}
                                        </option>
                                        <option value="0"
                                            {{ old('status', $cinema->getStatus()) !== NULL && (int)old('status', $cinema->getStatus()) === 0 ? 'selected' : '' }}>
                                            {{ __('Disable') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Name --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="name">
                                    {{ __('Cinema Name') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input placeholder="{{ __('Type name of cinema') }}"
                                           type="text"
                                           name="name"
                                           id="name"
                                           class="form-control dmovie-border
                                        @error('name') invalid @enderror"
                                           value="{{ old('name', $cinema->getName()) }}"/>
                                    @error('name')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Trailer --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="address">
                                    {{ __('Address') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <textarea placeholder="{{ __('The location of cinema') }}"
                                              name="address"
                                              id="address"
                                              rows="3"
                                              class="form-control dmovie-border
                                        @error('address') invalid @enderror">{{ old('address', $cinema->getAddress()) }}</textarea>
                                    @error('address')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            {{-- Province --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="province">
                                    {{ __('Province') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input placeholder="{{ __('Type name of province where the cinema is place') }}"
                                           type="text"
                                           name="province"
                                           id="province"
                                           class="form-control dmovie-border
                                        @error('province') invalid @enderror"
                                           value="{{ old('province', $cinema->getProvince()) }}"/>
                                    @error('province')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="phone">
                                    {{ __('Phone') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input placeholder="{{ __('Type hotline for this cinema') }}"
                                           type="text"
                                           name="phone"
                                           id="phone"
                                           class="form-control dmovie-border
                                        @error('phone') invalid @enderror"
                                           value="{{ old('phone', $cinema->getPhone()) }}"/>
                                    @error('phone')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Desciption --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="description">
                                    {{ __('Description') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <textarea placeholder="{{ __('Describe this cinema') }}"
                                              name="description"
                                              id="description"
                                              rows="10"
                                              class="form-control dmovie-border
                                        @error('description') invalid @enderror">{{ old('description', $cinema->getDescription()) }}</textarea>
                                    @error('description')
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
