@extends('admin.layouts.app')

<?php /** @var \App\FilmSchedule $schedule */ ?>

@section('app.title')
    {{ __('Edit :filmName\'s schedule', ['filmName' => $schedule->getFilm()->getTitle()]) }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('fs.index') }}">{{ __('Schedule manage') }}</a></li>
    <li class="active">{{ $schedule->getFilm()->getTitle() }}</li>
@endsection

@section('titlebar.title')
    {!!  __('Edit <code>:filmName</code>\'s schedule', ['filmName' => $schedule->getFilm()->getTitle()])  !!}
@endsection

@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/air-datepicker/css/datepicker.min.css') }}">
@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/plugins/air-datepicker/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/plugins/air-datepicker/js/i18n/datepicker.' . Session::get('locale', config('app.locale')) . '.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/plugins/select2/i18n/' . Session::get('locale', config('app.locale')) . '.js') }}"></script>
    <script src="{{ asset('adminhtml/js/schedule/ce.js') }}"></script>
@endsection


@section('action_button')
    <div class="navbar dmovie-fix-top-container">
        <div class="row bg-title" id="dmovie-fix-top-block">
            <div class="col-md-12">
                <a href="javascript:void(0);"
                   onclick="event.preventDefault(); $('#edit-form').submit();"
                   class="btn dmovie-btn dmovie-btn-success dmovie-btn-large pull-right m-r-40">
                    <i class="mdi mdi-content-save"></i>
                    {{ __('Save') }}
                </a>
                <a href="{{ route('fs.index') }}"
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
        <form id="edit-form"
              method="POST"
              action="{{ route('fs.update', ['f' => $schedule]) }}"
              class="col-md-12 form-horizontal"
              enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">{!!  '<code>'.$schedule->getFilm()->getTitle().'</code>'  !!}</div>
                        <div class="panel-body">
                            <!-- Show status -->
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
                                            {{ old('status', $schedule->getStatus()) !== NULL && (int)old('status', $schedule->getStatus()) === 1 ? 'selected' : '' }}>
                                            {{ __('Enable') }}
                                        </option>
                                        <option value="0"
                                            {{ old('status', $schedule->getStatus()) !== NULL && (int)old('status', $schedule->getStatus()) === 0 ? 'selected' : '' }}>
                                            {{ __('Disable') }}
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <!-- Cinema Name -->
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="cinema_id">
                                    {{ __('Cinema Name') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select id="cinema"
                                            name="cinema_id"
                                            swl-must-select-error-text="{{ __('Please select a cinema') }}"
                                            sl2-select-cinema-placeholder-text="{{ __('Select a cinema') }}"
                                            class="form-control bs-select-hidden @error('cinema_id') invalid @enderror"
                                            style="width: 100%"
                                            old-value="{{ old('cinema_id', $schedule->getShow()->getCinema()->getId()) }}">
                                    </select>
                                    @error('cinema_id')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Show Name -->
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="show_id">
                                    {{ __('Show Name') }} <strong class="text-danger">*</strong>
                                </label>
                                <label class="control-label col-md-3 col-xs-12 _notifier select-none cursor-pointer text-left text-danger" for="cinema_id">
                                    {{ __('Please select a cinema first!') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select id="show"
                                            name="show_id"
                                            sl2-select-show-placeholder-text="{{ __('Select a show') }}"
                                            class="form-control bs-select-hidden @error('show_id') invalid @enderror"
                                            style="width: 100%"
                                            old-value="{{ old('show_id', $schedule->getShow()->getId()) }}">
                                    </select>
                                    @error('show_id')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Film Name -->
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="film_id">
                                    {{ __('Film Name') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select id="film"
                                            name="film_id"
                                            sl2-select-film-placeholder-text="{{ __('Select a film') }}"
                                            class="form-control bs-select-hidden @error('film_id') invalid @enderror"
                                            style="width: 100%"
                                            old-value="{{ old('film_id', $schedule->getFilm()->getId()) }}">
                                    </select>
                                    @error('film_id')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4 col-xs-12 m-b-0">
                                    <label for="film_duration" class="control-label cursor-pointer col-lg-3 col-md-5 col-xs-12">
                                        {{ __('Film Duration') }}
                                    </label>
                                    <div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="input-group">
                                            <input type="text" disabled class="form-control dmovie-border film_duration" />
                                            <span class="input-group-addon">{{ __('Minutes') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Start date -->
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="start_date">
                                    {{ __('Start Date') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input placeholder="{{ __('Select start date') }}"
                                           type="text"
                                           name="start_date"
                                           id="start_date"
                                           class="form-control dmovie-border
                                        @error('start_date') invalid @enderror"
                                           value="{{ old('start_date', \Carbon\Carbon::make($schedule->getStartDate())->format('d-m-Y')) }}"
                                           dmovie-datepicker
                                           data-language="{{ Session::get('locale', config('app.locale')) }}"
                                    />
                                    @error('start_date')
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
