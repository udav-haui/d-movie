@extends('admin.layouts.app')

<?php
    /**
     * @var \App\Show $show
     */
?>

@section('app.title')
    {{ __('New seat for :show', ['show' => $show->getName()]) }}
@endsection

@section('app.description'){{ __('New seat for :show', ['show' => $show->getName()]) }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('shows.getSeats', ['show' => $show]) }}">{{ __('Seats list') }}</a></li>
    <li class="active">{{ __('New Seat') }}</li>
@endsection

@section('titlebar.title')
    {{ __('New seat for :show', ['show' => $show->getName()]) }}
@endsection

@section('head.css')


@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/plugins/select2/i18n/' . Session::get('locale', config('app.locale')) . '.js') }}"></script>
    <script src="{{ asset('adminhtml/js/seat/ce.js') }}"></script>
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
                <a href="{{ route('shows.getSeats', ['show' => $show]) }}"
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
              action="{{ route('seats.store') }}"
              class="col-md-12 form-horizontal"
              enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ __('New seat') }}</div>
                        <div class="panel-body">
                            {{-- Seat status --}}
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
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="show_id">
                                    {{ __('Show Name') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input type="text" name="show_id" class="display-none"
                                           value="{{ old('show_id', $show->getId()) }}">
                                    <select id="show_id"
                                            class="form-control bs-select-hidden @error('cinema_id') invalid @enderror"
                                            style="width: 100%"
                                            dmovie-select2 disabled>
                                        <option value="{{ $show->getId() }}"
                                            {{ old('show_id', $show->getId()) }}>
                                            {{ $show->getName() }}
                                        </option>
                                    </select>

                                    @error('show_id')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            {{-- Seat type --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="type">
                                    {{ __('Seat type') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select name="type" id="type"
                                            class="bs-select-hidden"
                                            data-style="form-control"
                                            dmovie-select2>
                                        <option value="0"
                                            {{ old('type') !== NULL && (int)old('type') === 0 ? 'selected' : '' }}>
                                            {{ __('Normal seat') }}
                                        </option>
                                        <option value="1"
                                            {{ old('type') !== NULL && (int)old('type') === 1 ? 'selected' : '' }}>
                                            {{ __('VIP seat') }}
                                        </option>
                                        <option value="2"
                                            {{ old('type') !== NULL && (int)old('type') === 2 ? 'selected' : '' }}>
                                            {{ __('Double seat') }}
                                        </option>
                                    </select>
                                </div>
                            </div>



                            {{-- Row --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="row">
                                    {{ __('Seat row') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input placeholder="{{ __('Type a seat row name') }}"
                                           type="text"
                                           name="row"
                                           id="row"
                                           class="form-control dmovie-border
                                        @error('row') invalid @enderror"
                                           value="{{ old('row') }}"/>
                                    @error('row')
                                        <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <div class="checkbox checkbox-custom dmovie-checkbox-override">
                                        <input id="_quickmake" type="checkbox" name="quick_make" value="1" />
                                        <label for="_quickmake" class="select-none"> {{ __('Quick make') }} </label>
                                    </div>
                                </div>
                            </div>


                            {{-- Number --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="number">
                                    {{ __('Row number') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input placeholder="{{ __('Type seat number') }}"
                                        type="number"
                                        name="number"
                                        id="number"
                                        class="form-control dmovie-border
                                        @error('number') invalid @enderror"
                                        value="{{ old('number') }}"/>
                                    @error('number')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>




                            {{-- Start at --}}
                            <div class="form-group" style="display: none;">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="start_at">
                                    {{ __('Start at') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input placeholder="{{ __('Type start point') }}"
                                           type="text"
                                           name="start_at"
                                           id="start_at"
                                           class="form-control dmovie-border
                                        @error('start_at') invalid @enderror"
                                           value="{{ old('start_at') }}" disabled/>
                                    @error('start_at')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- count --}}
                            <div class="form-group" style="display: none;">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="count">
                                    {{ __('Seat count') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input placeholder="{{ __('Type number of increment') }}"
                                        type="text"
                                        name="count"
                                        id="count"
                                        class="form-control dmovie-border
                                        @error('count') invalid @enderror"
                                        value="{{ old('count') }}" disabled/>
                                    @error('count')
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
