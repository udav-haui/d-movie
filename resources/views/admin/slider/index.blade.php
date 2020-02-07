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
    <link rel="stylesheet" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css">
    <link rel="stylesheet" href="{{ asset('adminhtml/css/slider/index.css') }}">
@endsection

@section('bottom.js')
    <script>
        let columnDefs = [],
            colOrder = [],
            aoColumns = [];
    @cannot('canEditDelete', \App\Slider::class)
        aoColumns = [
        {
            data: 'id',
            name: 'id'
        },
        {
            data: 'title',
            name: 'title'
        },
        {
            data: 'image',
            name: 'image',
            orderable: false,
            sortable: false
        },
        {
            data: 'href',
            name: 'href'
        },
        {
            data: 'status',
            name: 'status'
        },
        {
            data: 'order',
            name: 'order'
        }
    ];

        columnDefs= [
            {
                targets: 1,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'id');
                }
            },
            {
                targets: 5,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'status');
                }
            },
            {
                targets: ['no-sort'],
                orderable: false
            },
        ];
        colOrder = [[0, 'desc']];
    @else
        aoColumns = [
        {
            targets: 0,
            data: 'id',
            render: function (data, type, full, meta) {
                return `<div class="dmovie-checkbox dmovie-checkbox-custom">
                                    <input value="${data}" id="checkbox-${data}"
                                           type="checkbox"
                                           grid-item-checkbox
                                           class="dt-checkboxes display-none user-checkbox">
                                    <label for="checkbox-${data}" class="cursor-pointer"></label>
                                </div>`;
            }
        },
        {
            data: 'id',
            name: 'id'
        },
        {
            data: 'title',
            name: 'title'
        },
        {
            data: 'image',
            name: 'image',
            orderable: false,
            sortable: false
        },
        {
            data: 'href',
            name: 'href'
        },
        {
            data: 'status',
            name: 'status'
        },
        {
            data: 'order',
            name: 'order'
        },
        {
            data: 'task',
            sortable: false,
            orderable: false
        }
    ];
        columnDefs = [
            {
                targets: 0,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'checkbox');
                },
                checkboxes: {
                    selectRow: true,
                    selectAllRender: `<input type="checkbox" id="checkbox-all" />`
                }
            },
            {
                targets: 1,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'id');
                }
            },
            {
                targets: 5,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'status');
                }
            },
            {
                targets: ['no-sort', 0],
                orderable: false
            },
        ];
        colOrder = [[1, 'desc']];
    @endcannot
    </script>

    <script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>
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

    @can('canEditDelete', \App\Slider::class)
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
                                   swl-text="{{ __('Do you want to destroy this selected slide items?') }}">
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                        @can('update', \App\Slider::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_change-status-sliders"
                                   swl-text="{{ __('Do you want change all status of this slide items?') }}"
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
    @endcan

    <div class="row">
        <div class="col-md-12 table-responsive">
{{--            <table id="sliders_data"--}}
{{--                   class="display nowrap dmovie-table"--}}
{{--                   cellspacing="0"--}}
{{--                   width="100%">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    @can('canEditDelete', \App\Slider::class)--}}
{{--                        <th class="no-sort">--}}
{{--                            <div class="dmovie-checkbox dmovie-checkbox-custom">--}}
{{--                                <input value="0" id="checkbox-all" type="checkbox"--}}
{{--                                       class="display-none user-checkbox">--}}
{{--                                <label for="checkbox-all" class="cursor-pointer background-fff"></label>--}}
{{--                            </div>--}}
{{--                        </th>--}}
{{--                    @endcan--}}
{{--                    <th>#</th>--}}
{{--                    <th>{{ __('Title') }}</th>--}}
{{--                    <th class="no-sort">{{ __('Image') }}</th>--}}
{{--                    <th>{{ __('Href') }}</th>--}}
{{--                    <th>{{ __('Order') }}</th>--}}
{{--                    <th>{{ __('Status') }}</th>--}}
{{--                    @can('canEditDelete', \App\Slider::class)--}}
{{--                        <th class="no-sort min-width-65">{{ __('Task') }}</th>--}}
{{--                    @endcan--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tfoot>--}}
{{--                <tr>--}}
{{--                    @can('canEditDelete', \App\Slider::class)<th class="no-sort"></th>@endcan--}}
{{--                    <th>#</th>--}}
{{--                    <th>{{ __('Title') }}</th>--}}
{{--                    <th class="no-sort">{{ __('Image') }}</th>--}}
{{--                    <th>{{ __('Href') }}</th>--}}
{{--                    <th>{{ __('Order') }}</th>--}}
{{--                    <th>{{ __('Status') }}</th>--}}
{{--                    @can('canEditDelete', \App\Slider::class)--}}
{{--                        <th class="no-sort">{{ __('Task') }}</th>--}}
{{--                    @endcan--}}
{{--                </tr>--}}
{{--                </tfoot>--}}
{{--                <tbody>--}}
{{--                <?php /** @var $item \App\Slider */ ?>--}}
{{--                @foreach($sliders as $item)--}}
{{--                    <tr>--}}
{{--                        @can('canEditDelete', \App\Slider::class)--}}
{{--                            <td scope="checkbox">--}}
{{--                                <div class="dmovie-checkbox dmovie-checkbox-custom">--}}
{{--                                    <input value="{{ $item->id }}" id="checkbox-{{ $item->id }}"--}}
{{--                                           type="checkbox"--}}
{{--                                           grid-item-checkbox--}}
{{--                                           class="display-none user-checkbox">--}}
{{--                                    <label for="checkbox-{{ $item->id }}" class="cursor-pointer"></label>--}}
{{--                                </div>--}}
{{--                            </td>--}}
{{--                        @endcan--}}
{{--                        <td scope="id">{{ $item->id }}</td>--}}
{{--                        <td scope="title" title="{{ $item->getTitle() }}">--}}
{{--                            {{ strlen($item->getTitle()) > 65 ? substr($item->getTitle(), 0, 65) . '...' : $item->getTitle() }}--}}
{{--                        </td>--}}
{{--                        <td scope="image">--}}
{{--                            <a href="{{ $item->getImagePath() }}"--}}
{{--                               class="slide-item"--}}
{{--                               data-fancybox="sliders" data-caption="{{ $item->getTitle() }}">--}}
{{--                                <img src="{{ $item->getImagePath() }}"--}}
{{--                                    class="slide-item-image" />--}}
{{--                            </a>--}}
{{--                        </td>--}}
{{--                        <td scope="href">{!! $item->renderHtmlHref() !!}</td>--}}
{{--                        <td scope="Order">{{ $item->getAttribute('order') }}</td>--}}
{{--                        <td scope="status">--}}
{{--                            <div class="pretty p-switch p-fill dmovie-switch">--}}
{{--                                <input type="checkbox"--}}
{{--                                       {{ (int)$item->getAttribute('status') === 1 ? 'checked' : '' }}--}}
{{--                                       class="status-checkbox"--}}
{{--                                       value="{{ $item->getAttribute('status') }}"--}}
{{--                                       data-id="{{ $item->getAttribute('id') }}"--}}
{{--                                       @cannot('update', \App\Slider::class) disabled @endcannot--}}
{{--                                />--}}
{{--                                <div class="state p-success">--}}
{{--                                    <label class="status-text select-none">--}}
{{--                                        {{ $item->getStatusLabel() }}--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </td>--}}
{{--                        @can('canEditDelete', \App\Slider::class)--}}
{{--                            <td scope="task">--}}
{{--                                @can('view', \App\Slider::class)--}}
{{--                                    <a href="{{ route('sliders.edit', ['slider' => $item->id]) }}"--}}
{{--                                       type="button"--}}
{{--                                       class="@cannot('delete', \App\Slider::class)) col-md-12 @else col-md-6 @endcannot--}}
{{--                                           col-xs-12 btn dmovie-btn dmovie-btn-success"--}}
{{--                                       title="{{ __('Detail') }}">--}}
{{--                                        <i class="mdi mdi-account-edit"></i>--}}
{{--                                    </a>--}}
{{--                                @endcan--}}
{{--                                @can('delete', \App\Slider::class)--}}
{{--                                    <button id="deleteBtn" type="button"--}}
{{--                                            class="col-md-6 col-xs-12 btn dmovie-btn btn-danger"--}}
{{--                                            title="{{ __('Delete') }}"--}}
{{--                                            data-id="{{ $item->getId() }}"--}}
{{--                                            url="{{ route('sliders.destroy', ['slider' => $item->getId()]) }}">--}}
{{--                                        <i class="mdi mdi-account-minus"></i>--}}
{{--                                    </button>--}}
{{--                                @endcan--}}
{{--                            </td>--}}
{{--                        @endcan--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}

            <hr />

            <table id="sliders_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    @can('canEditDelete', \App\Slider::class)<th></th>@endcan
                    <th>#</th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('Href') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Order') }}</th>
                    @can('canEditDelete', \App\Slider::class)
                        <th class="no-sort min-width-65">{{ __('Task') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>


            <button id="btn-test">Test</button>
        </div>
    </div>

@endsection
