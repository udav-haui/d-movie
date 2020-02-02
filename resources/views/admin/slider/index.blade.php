@extends('admin.layouts.app')

@section('app.title'){{ __('Slider Manage') }}
@endsection

@section('app.description'){{ __('Slider Manage') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('Slider Manage') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Slider Manage') }}
@endsection

@section('head.css')
    <link rel="stylesheet" href="{{ asset('adminhtml/css/slider/index.css') }}">
@endsection

@section('bottom.js')
    <script src="{{ asset('adminhtml/js/slider/index.js') }}"></script>
@endsection

@section('content')
    @can('create', \App\Slider::class)
        <div class="row bg-title" id="dmovie-fix-top-block">
            <div class="col-lg-3 col-md-4 col-xs-12 pull-right">
                <a href="{{ route('sliders.create') }}"
                   class="btn btn-block btn-default dmovie-btn dmovie-btn-success">
                    {{ __('New item') }}
                </a>
            </div>
        </div>
    @endcan
    {{--  include lang text for js select  --}}
    @include('admin.lang.global_text_lang')

    <div class="row m-b-15">
        <div class="col-md-1">
            <div class="btn-group">
                <button
                    aria-expanded="false"
                    data-toggle="dropdown"
                    class="btn btn-default btn-outline
                    dropdown-toggle waves-effect waves-light border-radius-0 dmovie-textbox-border"
                    type="button"> {{ __('Action') }}
                    <span class="caret"></span>
                </button>
                <ul role="menu" class="dropdown-menu border-radius-0 dmovie-border">
                    @if (!auth()->user()->can('delete', \App\Slider::class) && !auth()->user()->can('update',
                    \App\Slider::class))
                        <li><a href="javascript:void(0);">{{ __('Not action available for you') }}</a></li>
                    @endif
                    @can('delete', \App\Slider::class)
                        <li>
                            <a href="javascript:void(0);"
                               class="_delete-sliders"
                               swl-text="{{ __('Do you want to destroy this slide item?') }}">
                                {{ __('Delete') }}
                            </a>
                        </li>
                    @endcan
                    @can('update', \App\Slider::class)
                        <li>
                            <a href="javascript:void(0);"
                               class="_change-state-sliders"
                               swl-text="{{ __('Do you want change all state of this slide items?') }}"
                               swl-state-alert-title="{{ __('Select state') }}"
                               swl-select-disable-item="{{ __('Disable') }}"
                               swl-select-enable-item="{{ __('Enable') }}"
                               swl-cancel-btn-text="{{ __('Cancel') }}" >
                                {{ __('Set state') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>
        <div class="col-md-2 selected-rows-label-container m-l-10">
            {{ __('Selected') }}&nbsp;<span class="selected-rows-label">0</span>&nbsp;{{ __('rows') }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 table-responsive">
            <table id="sliders_data" class="display nowrap dmovie-table"
                   cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th class="no-sort">
                        <div class="dmovie-checkbox dmovie-checkbox-custom">
                            <input value="0" id="checkbox-all" type="checkbox"
                                   class="display-none user-checkbox">
                            <label for="checkbox-all" class="cursor-pointer background-fff"></label>
                        </div>
                    </th>
                    <th>#</th>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('Href') }}</th>
                    <th>{{ __('Order') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="no-sort">{{ __('Task') }}</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('Href') }}</th>
                    <th>{{ __('Order') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="no-sort">{{ __('Task') }}</th>
                </tr>
                </tfoot>
                <tbody>
                <?php /** @var $item \App\Slider */ ?>
                @foreach($sliders as $item)
                    <tr>
                        <td scope="checkbox">
                            <div class="dmovie-checkbox dmovie-checkbox-custom">
                                <input value="{{ $item->id }}" id="checkbox-{{ $item->id }}" type="checkbox"
                                       class="display-none user-checkbox">
                                <label for="checkbox-{{ $item->id }}" class="cursor-pointer"></label>
                            </div>
                        </td>
                        <td scope="id" data-toggle="tooltip" title="Default tooltip">{{ $item->id }}</td>
                        <td scope="image">
                            <a href="{{ $item->getImage() }}"
                               class="slide-item"
                               data-fancybox >
                                <img src="{{ $item->getImage() }}"
                                    class="slide-item-image" />
                            </a>
                        </td>
                        <td scope="href">{!! $item->getHref() !!}</td>
                        <td scope="Order">{{ $item->getAttribute('order') }}</td>
                        <td scope="status">
                            <div class="pretty p-switch p-fill">
                                <input type="checkbox"
                                       {{ (int)$item->getAttribute('status') === 1 ? 'checked' : '' }}
                                       class="status-checkbox"
                                       value="{{ $item->getAttribute('status') }}"
                                       data-id="{{ $item->getAttribute('id') }}"
                                       @cannot('update', \App\Slider::class) disabled @endcannot
                                />
                                <div class="state p-success">
                                    <label class="status-text select-none">
                                        {{ $item->getStatusLabel() }}
                                    </label>
                                </div>
                            </div>
                        </td>
                        <td scope="task">
{{--                            @can('view', $item)--}}
{{--                                <a href="{{ route('users.edit', ['user' => $user->id]) }}"--}}
{{--                                   type="button"--}}
{{--                                   class=" @if (auth()->user()->id === $user->id || !auth()->user()->can('delete', $user)) col-md-12 @else col-md-6 @endif--}}
{{--                                       col-xs-12 btn dmovie-btn dmovie-btn-success"--}}
{{--                                   title="{{ __('Detail') }}">--}}
{{--                                    <i class="mdi mdi-account-edit"></i>--}}
{{--                                </a>--}}
{{--                            @endcan--}}
{{--                            @can('delete', $user)--}}
{{--                                @if (auth()->user()->id !== $user->id)--}}
{{--                                    <button id="deleteUserBtn" type="button"--}}
{{--                                            class="col-md-6 col-xs-12 btn dmovie-btn btn-danger"--}}
{{--                                            title="{{ __('Delete') }}"--}}
{{--                                            data-id="{{ $user->id }}"--}}
{{--                                            url="{{ route('users.destroy', ['user' => $user->id]) }}">--}}
{{--                                        <i class="mdi mdi-account-minus"></i>--}}
{{--                                    </button>--}}
{{--                                @endif--}}
{{--                            @endcan--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
