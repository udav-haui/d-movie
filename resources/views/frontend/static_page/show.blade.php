@extends('frontend.layouts.app')

@section('app.title')
    {{ __('Informations') }}
@endsection

@section('app.descriptions')
    {{ __('Information') }}
@endsection

@section('bottom.js')

    <script src="{{ asset('frontend/js/static_page/index.js') }}"></script>
@endsection

@section('content')

    <div class="container">
        @include('admin.layouts.components.normal_notifications')
    </div>

    <div class="container">
        <!-- TABS -->
        <div class="row margin-bottom-35 margin-top-40">
            <div class="col-lg-3 col-md-3">
                <ul class="nav nav-tabs tabs-left text-uppercase tab-information">
                    <?php /** @var \App\StaticPage $page */ ?>
                    @foreach($activePagesGlobal as $page)
                        <li class="@if ($pageSlug === $page->getSlug()) active @endif border-radius-0">
                            <a class="font-16" href="#{{ $page->getSlug() }}" data-toggle="tab">{{ $page->getName() }}</a>
                        </li>
                    @endforeach
                    <li class="@if ($pageSlug === __('contact')) active @endif border-radius-0"><a class="font-16" href="#contact" data-toggle="tab">{{ __('Contact') }}</a></li>
                </ul>
            </div>
            <div class="col-lg-13 col-md-13">
                <div class="tab-content font-family-san font-16">

                    @foreach($activePagesGlobal as $page)
                        <div class="tab-pane fade @if ($page->getSlug() === $pageSlug) in active @endif" id="{{ $page->getSlug() }}">
                            {!! $page->getContent() !!}
                        </div>
                    @endforeach
                    <div class="tab-pane fade @if ($pageSlug === convert_vi_to_en(__('contact'))) in active @endif " id="contact">
                        <!-- Google Map -->
                        <h2 class="text-uppercase">
                            {{ __('Contact with us') }}
                        </h2>
                        <form action="{{ route('customer.sendFeedback') }}" method="post">
                            @csrf
                            <div class="row margin-bottom-20">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-16">
                                            <div class="form-group">
                                                <h3>{{ __('Cinema name') }}</h3>
                                                <select style="width: 100%;" id="_select-cinema" name="cinema_id" class="form-control" tabindex="1">
                                                </select>
                                                @error('cinema_id')
                                                <span class="text-danger">{!! $message !!}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h3>{{ __('Feedback') }}</h3>
                                    <!-- BEGIN FORM-->
                                    <div class="form-group">
                                        <div class="input-icon">
                                            <i class="fa fa-user"></i>
                                            <input type="text" id="contact_name"
                                                   name="contact_name"
                                                   class="form-control"
                                                   value="{{ old('contact_name') }}"
                                                   placeholder="{{ __('Name') }}">
                                            @error('contact_name')
                                            <span class="text-danger">{!! $message !!}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-icon">
                                            <i class="fa fa-envelope"></i>
                                            <input type="text" id="contact_email" name="contact_email"
                                                   class="form-control"
                                                   value="{{ old('contact_email') }}"
                                                   placeholder="{{ __('Email') }}">
                                            @error('contact_email')
                                            <span class="text-danger">{!! $message !!}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-icon">
                                            <i class="fa fa-phone-square"></i>
                                            <input type="text" id="contact_phone"
                                                   name="contact_phone" class="form-control"
                                                   value="{{ old('contact_phone') }}"
                                                   placeholder="{{ __('Phone') }}">
                                            @error('contact_phone')
                                            <span class="text-danger">{!! $message !!}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <textarea class="form-control" id="contact_content"
                                              name="contact_content" rows="3=6"
                                              placeholder="{{ __('Content') }}">{{ old('contact_content') }}</textarea>
                                        @error('contact_content')
                                        <span class="text-danger">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn green pull-right">
                                        {{ __('Send') }}</button>
                                    <!-- END FORM-->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END TABS -->
    </div>
@endsection
