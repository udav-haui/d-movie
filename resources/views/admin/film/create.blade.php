@extends('admin.layouts.app')

@section('app.title')
    {{ __('New Film') }}
@endsection

@section('app.description'){{ __('New Film') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route('films.index') }}">{{ __('Films Manage') }}</a></li>
    <li class="active">{{ __('New film') }}</li>
@endsection

@section('titlebar.title')
    {{ __('New Film') }}
@endsection

@section('head.css')

    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/air-datepicker/css/datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('adminhtml/assets/plugins/bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}">
@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/plugins/air-datepicker/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/plugins/air-datepicker/js/i18n/datepicker.' .
Session::get('locale', config('app.locale')) . '.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('adminhtml/assets/plugins/bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('adminhtml/js/film/create.js') }}"></script>
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
            <a href="{{ route('films.index') }}"
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
              action="{{ route('films.store') }}"
              class="col-md-12 form-horizontal"
              enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ __('New film') }}</div>
                        <div class="panel-body">
{{--                            Fim status --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="status">
                                    {{ __('Status') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select name="status" id="status"
                                            class="bs-select-hidden"
                                            data-style="form-control" dmovie-select2>
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

                            {{-- Title --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="title">
                                    {{ __('Film Title') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input placeholder="{{ __('Type title of film') }}"
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

                            {{-- Poster --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="poster">
                                    {{ __('Poster') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input type="file" required name="poster" id="image" class="dropify col-md-3" />
                                    <label class="help-block">{{ __('Select post (recommend 228p x 360p)') }}</label>
                                    @error('poster')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Director --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="director">
                                    {{ __('Director') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input placeholder="{{ __('Type name of Director') }}"
                                        type="text"
                                        name="director"
                                        id="director"
                                        class="form-control dmovie-border
                                        @error('director') invalid @enderror"
                                        value="{{ old('director') }}"/>
                                    @error('director')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Cast --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="cast">
                                    {{ __('Cast') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <div class="tags-default dmovie-border dmovie-tags
                                        @error('cast') invalid-border @enderror">
                                        <input placeholder="{{ __('Type some name of character') }}"
                                            dmovie-tags
                                            type="text"
                                            name="cast"
                                            id="cast"
                                            class="form-control"
                                            value="{{ old('cast') }}"/>
                                    </div>
                                    @error('order')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Genre --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="genre">
                                    {{ __('Genre') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <div class="tags-default dmovie-border dmovie-tags
                                        @error('genre') invalid-border @enderror">
                                        <input
                                            dmovie-tags
                                            placeholder="{{ __('Type genres of film') }}"
                                            type="text"
                                            name="genre"
                                            id="genre"
                                            class="form-control"
                                            value="{{ old('genre') }}"/>
                                    </div>
                                    @error('genre')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Running time --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer"
                                       for="running_time">
                                    {{ __('Running Time') }} <strong class="text-danger">*</strong>
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <input
                                        type="number"
                                        name="running_time"
                                        id="running_time"
                                        placeholder="{{ __('Length of film') }}"
                                        data-bts-button-down-class="btn btn-default btn-outline"
                                        data-bts-button-up-class="btn btn-default btn-outline"
                                        class="vertical-spin dmovie-border @error('running_time') invalid @enderror"
                                        value="{{ old('running_time') }}"/>
                                    @error('running_time')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Language --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="language">
                                    {{ __('Language') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <div class="tags-default dmovie-border dmovie-tags
                                        @error('language') invalid-border @enderror">
                                        <input
                                            dmovie-tags
                                            type="text"
                                            name="language"
                                            id="language"
                                            class="form-control"
                                            placeholder="{{ __('Type some language of this film') }}"
                                            value="{{ old('language') }}"/>
                                    </div>
                                    @error('language')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Release Date --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12"
                                       for="release_date">
                                    {{ __('Release Date') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <div class="input-group">
                                        <input name="release_date"
                                               type="text"
                                               class="form-control dmovie-border @error('release_date') invalid @enderror"
                                               data-language="{{ \Session::get('locale', config('app.locale')) }}"
                                               id="release_date"
                                               placeholder="dd-mm-yyyy" value="{{ old('release_date') }}"
                                               dmovie-datepicker
                                        />
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                    @error('release_date')
                                    <span class="error text-danger dmovie-error-box">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            {{-- Mark --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer"
                                       for="mark">{{ __('Mark') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select name="mark" id="mark"
                                            class="form-control bs-select-hidden dmovie-border"
                                            data-style="form-control">
                                        <option value="p" {{ old('mark') ? 'p' : 'selected' }}>{{ __('P') }}</option>
                                        <option value="c13" {{ old('mark') != 'c13' ? '' : 'selected' }}>{{ __('C13') }}</option>
                                        <option value="c16" {{ old('mark') != 'c16' ? '' : 'selected' }}>{{ __('C16') }}</option>
                                        <option value="c18" {{ old('mark') != 'c18' ? '' : 'selected' }}>{{ __('C18') }}</option>
                                    </select>
                                    <label class="help-block">{{ __('Select mark of film') }}</label>
                                </div>
                            </div>



                            <!-- Is Coming Soon -->
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="is_coming_soon">
                                    {{ __('Is Coming Soon') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select name="is_coming_soon" id="is_coming_soon"
                                            class="bs-select-hidden"
                                            data-style="form-control"
                                            dmovie-select2>
                                        <option value="1"
                                            {{ old('is_coming_soon') !== NULL && (int)old('is_coming_soon') === \App\Film::YES ? 'selected' : '' }}>
                                            {{ __('Yes') }}
                                        </option>
                                        <option value="0"
                                            {{ old('is_coming_soon') !== NULL && (int)old('is_coming_soon') === \App\Film::NO ? 'selected' : '' }}>
                                            {{ __('No') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Is Open Sale Ticket -->
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="is_open_sale_ticket">
                                    {{ __('Is Open Sale Ticket') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select name="is_open_sale_ticket" id="is_open_sale_ticket"
                                            class="bs-select-hidden"
                                            data-style="form-control"
                                            dmovie-select2>
                                        <option value="1"
                                            {{ old('is_open_sale_ticket') !== NULL && (int)old('is_open_sale_ticket') === \App\Film::YES ? 'selected' : '' }}>
                                            {{ __('Yes') }}
                                        </option>
                                        <option value="0"
                                            {{ old('is_open_sale_ticket') !== NULL && (int)old('is_open_sale_ticket') === \App\Film::NO ? 'selected' : '' }}>
                                            {{ __('No') }}
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <!-- Is Sneak Show -->
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="is_sneak_show">
                                    {{ __('Is Sneak Show') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <select name="is_sneak_show" id="is_sneak_show"
                                            class="bs-select-hidden"
                                            data-style="form-control"
                                            dmovie-select2>
                                        <option value="0"
                                            {{ old('is_sneak_show') !== NULL && (int)old('is_sneak_show') === \App\Film::NO ? 'selected' : '' }}>
                                            {{ __('No') }}
                                        </option>
                                        <option value="1"
                                            {{ old('is_sneak_show') !== NULL && (int)old('is_sneak_show') === \App\Film::YES ? 'selected' : '' }}>
                                            {{ __('Yes') }}
                                        </option>
                                    </select>
                                </div>
                            </div>


                            {{-- Trailer --}}
                            <div class="form-group">
                                <label class="control-label col-md-5 col-xs-12 cursor-pointer" for="trailer">
                                    {{ __('Trailer') }}
                                </label>
                                <div class="col-md-3 col-xs-12">
                                    <textarea placeholder="{{ __('Youtube trailer link for this film') }}"
                                        name="trailer"
                                        id="trailer"
                                        rows="3"
                                        class="form-control dmovie-border
                                        @error('trailer') invalid @enderror">{{ old('trailer') }}</textarea>
                                    <label class="help-block">{{ __('Example: ') }}https://www.youtube.com/watch?v=TcMBFSGVi1c</label>
                                    @error('trailer')
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
                                    <textarea placeholder="{{ __('Sort description for this film') }}"
                                        name="description"
                                        id="description"
                                        rows="5"
                                        class="form-control dmovie-border
                                        @error('description') invalid @enderror">{{ old('description') }}</textarea>
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
