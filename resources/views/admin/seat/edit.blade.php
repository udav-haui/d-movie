@extends('admin.layouts.app')

<?php /** @var \App\Seat $seat */ ?>

@section('app.title')
    {{ __('Edit') }} - {{ $seat->getRow().$seat->getNumber() }}
@endsection

@section('app.description')
    {{ __('Edit') }} - {{ $seat->getRow().$seat->getNumber() }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('shows.getSeats', ['show' => $seat->getShow()->getId()]) }}">{{ __('Seats list') }}</a></li>
    <li class="active">{{ $seat->getRow().$seat->getNumber() }}</li>
@endsection

@section('titlebar.title')
    {{ __('Edit Seat') }} - {{ $seat->getRow().$seat->getNumber() }}
@endsection

@section('bottom.js')
    <script
        src="{{ asset('adminhtml/assets/plugins/select2/i18n/' . Session::get('locale', config('app.locale')) . '.js') }}"></script>
    <script src="{{ asset('adminhtml/js/seat/ce.js') }}"></script>
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
                <a href="{{ route('shows.getSeats', ['show' => $seat->getShow()->getId()]) }}"
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
              action="{{ route('seats.update', ['seat' => $seat]) }}"
              class="col-md-12 form-horizontal"
              enctype="multipart/form-data">
            @method('PUT')
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
                                            {{ old('status', $seat->getStatus()) !== NULL && (int)old('status', $seat->getStatus()) === 1 ? 'selected' : '' }}>
                                            {{ __('Enable') }}
                                        </option>
                                        <option value="0"
                                            {{ old('status', $seat->getStatus()) !== NULL && (int)old('status', $seat->getStatus()) === 0 ? 'selected' : '' }}>
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
                                           value="{{ old('show_id', $seat->getShow()->getId()) }}">
                                    <select id="show_id"
                                            class="form-control bs-select-hidden @error('cinema_id') invalid @enderror"
                                            style="width: 100%"
                                            dmovie-select2 disabled>
                                        <option value="{{ $seat->getShow()->getId() }}"
                                            {{ old('show_id', $seat->getShow()->getId()) }}>
                                            {{ $seat->getShow()->getName() }}
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
                                           value="{{ old('row', $seat->getRow()) }}"/>
                                    @error('row')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
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
                                           value="{{ old('number', $seat->getNumber()) }}"/>
                                    @error('number')
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
