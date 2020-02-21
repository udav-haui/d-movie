@extends('admin.layouts.app')

<?php
/**
 * @var \App\Time $time
 */
/** @var \App\FilmSchedule $schedule */
$schedule = $time->getSchedule();
?>

@section('app.title')
    {{ __('Edit showtime for :filmName', ['filmName' => $schedule->getFilm()->getTitle()]) }}
@endsection

@section('app.description'){{ __('Edit showtime for :filmName', ['filmName' => $schedule->getFilm()->getTitle()]) }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('fs.index') }}">{{ __('Schedule manage') }}</a></li>
    <li><a href="{{ route('fs.edit', ['f' => $schedule]) }}">{{ $schedule->getFilm()->getTitle() }}</a></li>
    <li class="active">{{ __('Edit Showtime') }}</li>
@endsection

@section('titlebar.title')
    {!! __('Edit showtime for <code>:filmName</code>', ['filmName' => $schedule->getFilm()->getTitle()]) !!}
@endsection

@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/clockpicker/jquery-clockpicker.min.css') }}">
@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/plugins/clockpicker/jquery-clockpicker.min.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/plugins/select2/i18n/' . Session::get('locale', config('app.locale')) . '.js') }}"></script>
    <script src="{{ asset('adminhtml/js/time/ce.js') }}"></script>
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
                <a href="{{ route('fs.getShowtime', ['schedule' => $schedule]) }}"
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
              action="{{ route('times.update', ['time' => $time]) }}"
              class="col-md-12 form-horizontal">
            @method('PUT')
            @csrf
            <input type="hidden" name="film_show_id" value="{{ $schedule->getId() }}">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            {!! __('Edit showtime for <code>:filmName</code>', ['filmName' => $schedule->getFilm()->getTitle()]) !!}
                        </div>
                        <div class="panel-body">


                            <!-- Film Name -->
                            <div class="form-group">
                                <label class="control-label col-lg-5 col-md-6 col-xs-12 cursor-pointer" for="film_id">
                                    {{ __('Film Name') }}
                                </label>
                                <div class="col-lg-3 col-md-4">
                                    <input type="text"
                                           class="form-control dmovie-border"
                                           id="film_id"
                                           title="{{ $schedule->getFilm()->getTitle() }}"
                                           value="{{ $schedule->getFilm()->getTitle() }}"
                                           disabled
                                    />
                                </div>

                            </div>

                            <!-- Film duration -->
                            <div class="form-group">
                                <label for="film_duration" class="control-label cursor-pointer col-lg-5 col-md-6 col-xs-12">
                                    {{ __('Film Duration') }}
                                </label>
                                <div class="col-lg-3 col-md-4 col-xs-12">
                                    <div class="input-group">
                                        <input type="text"
                                               value="{{ $schedule->getFilm()->getRunningTime() }}"
                                               disabled
                                               class="form-control dmovie-border film_duration" />
                                        <span class="input-group-addon">{{ __('Minutes') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Prepare time -->
                            <div class="form-group">
                                <label for="prepare_time" class="control-label col-lg-5 col-md-6 col-xs-12 cursor-pointer">
                                    {{ __('Prepare Time') }}
                                </label>
                                <div class="col-lg-3 col-md-4 col-xs-12">
                                    <div class="input-group">
                                        <input type="number"
                                               placeholder="{{ __('Input more time') }}"
                                               class="form-control dmovie-border"
                                               name="prepare_time"
                                               id="prepare_time"
                                               value="{{ old('prepare_time', $time->getTotalTime() - $schedule->getFilm()->getRunningTime()) }}"
                                               min="0">
                                        <label for="prepare_time" class="input-group-addon">{{ __('Minutes') }}</label>
                                    </div>
                                </div>
                            </div>


                            <!-- Start time -->
                            <div class="form-group">
                                <label class="control-label col-lg-5 col-md-6 cursor-pointer" for="start_time">
                                    {{ __('Start Time') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-lg-3 col-md-4">
                                    <div class="input-group">
                                        <input placeholder="{{ __('Select start time') }}"
                                               type="text"
                                               name="start_time"
                                               id="start_time"
                                               class="form-control dmovie-border
                                        @error('start_time') invalid @enderror"
                                               value="{{ old('start_time', $time->getFormatStartTime()) }}"
                                               dmovie-clockpicker
                                               data-donetext="{{ __('Done') }}"
                                               data-twelvehour="false"
                                        />
                                        <label class="input-group-addon" for="start_time">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </label>
                                    </div>
                                    @error('start_time')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <!-- Stop time -->
                            <div class="form-group">
                                <label for="stop_time" class="control-label cursor-pointer col-lg-5 col-md-6 col-xs-12">
                                    {{ __('Stop Time') }}
                                </label>
                                <div class="col-lg-3 col-md-4 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" name="stop_time"
                                               class="form-control dmovie-border stop_time disabled"
                                               value="{{ old('stop_time', $time->getFormatStopTime()) }}" />
                                        <label class="input-group-addon" for="start_time">
                                            <span class="glyphicon glyphicon-time"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="total_time" id="total_time" value="{{ old('total_time', $time->getTotalTime()) }}">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
