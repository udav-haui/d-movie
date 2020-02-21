@extends('admin.layouts.app')

@section('app.title'){{ __('Films Manage') }}
@endsection

@section('app.description'){{ __('Films Manage') }}
@endsection

@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li class="active">{{ __('Films Manage') }}</li>
@endsection

@section('titlebar.title')
    {{ __('Films Manage') }}
@endsection

@section('head.css')
@endsection

@section('head.js')
    <script src="{{ asset('adminhtml/assets/plugins/jszip/jszip.min.js') }}"></script>
@endsection


@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/ellipsis.js') }}"></script>
    <script>
        let columnDefs = [],
            colOrder = [[0, 'asc']],
            aoColumns = [
                {
                    data: 'id',
                    name: 'id',
                    className: 'no-visible-filter'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'poster',
                    name: 'poster',
                    orderable: false,
                    sortable: false
                },
                {
                    data: 'director',
                    name: 'director'
                },
                {
                    data: 'cast',
                    name: 'cast'
                },
                {
                    data: 'genre',
                    name: 'genre'
                },
                {
                    data: 'running_time',
                    name: 'running_time'
                },
                {
                    data: 'language',
                    name: 'language'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'release_date',
                    name: 'release_date'
                },
                {
                    data: 'mark',
                    name: 'mark'
                },
                {
                    data: 'trailer',
                    name: 'trailer'
                },
                {
                    data: 'is_coming_soon',
                    name: 'is_coming_soon'
                },
                {
                    data: 'is_open_sale_ticket',
                    name: 'is_open_sale_ticket'
                },
                {
                    data: 'is_sneak_show',
                    name: 'is_sneak_show'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },

                {
                    data: 'updated_at',
                    name: 'updated_at'
                }
            ],
            invisibleCols = ['.data-cell-description', '.data-cell-created_at', '.data-cell-updated_at'];
        @cannot('canEditDelete', \App\Film::class)
            columnDefs = [
            {
                targets: '[class^="data-cell-"]',
                width: '1%',
            },
        ];
        @else
            aoColumns.push({
                data: 'task',
                sortable: false,
                orderable: false
            });
        columnDefs = [
            {
                targets: 'data-cell-status',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'status');
                }
            },
            {
                targets: 'data-cell-title',
                render: $.fn.dataTable.render.ellipsis( 60, true )
            },
            {
                targets: 'data-cell-cast',
                render: $.fn.dataTable.render.ellipsis( 60, true )
            },
            {
                targets: 'data-cell-description',
                render: $.fn.dataTable.render.ellipsis( 60, true )
            },
            {
                targets: 'data-cell-is_coming_soon',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'is_coming_soon');
                }
            },

            {
                targets: 'data-cell-is_open_sale_ticket',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'is_open_sale_ticket');
                }
            },
            {
                targets: 'data-cell-is_sneak_show',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-id', rowData.id);
                    $(td).attr('scope', 'is_sneak_show');
                }
            },
            {
                targets: 'data-cell-task',
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('not-selector', '');
                }
            },
            {
                targets: '[class^="data-cell-"]',
                width: '1%',
            },
        ];
        @endcannot

        columnDefs.push({
            targets: ['no-sort'],
            orderable: false
        });
    </script>
    <script src="{{ asset('adminhtml/assets/plugins/datatables/plugins/dt-buttons/buttons.flash.js') }}"></script>
    <script src="{{ asset('adminhtml/js/film/index.js') }}"></script>
@endsection

@section('action_button')
    @can('create', \App\Film::class)
        <div class="navbar dmovie-fix-top-container">
            <div class="row bg-title" id="dmovie-fix-top-block">
                <a href="{{ route('films.create') }}"
                   class="btn dmovie-btn dmovie-btn-success dmovie-btn-large m-r-40 pull-right">
                    <i class="mdi mdi-shape-polygon-plus"></i>
                    {{ __('New film') }}
                </a>
            </div>
        </div>
    @endcan
@endsection

@section('content')

    {{--  include lang text for js select  --}}
    @include('admin.lang.global_text_lang')


    @can('canEditDelete', \App\Slider::class)
        <div class="row m-b-15">
            <div class="col-md-3 col-lg-2">
                <div class="btn-group width-100">
                    <button
                        aria-expanded="false"
                        data-toggle="dropdown"
                        class="btn btn-default btn-outline width-100
                    dropdown-toggle waves-effect waves-light border-radius-0 dmovie-textbox-border"
                        type="button"> {{ __('Action') }}
                        <span class="caret"></span>
                    </button>
                    <ul role="menu" class="dropdown-menu border-radius-0 dmovie-border width-100">
                        @if (!auth()->user()->can('delete', \App\Film::class) && !auth()->user()->can('update',
                        \App\Film::class))
                            <li><a href="javascript:void(0);">{{ __('Not action available for you') }}</a></li>
                        @endif
                        @can('delete', \App\Film::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_delete-sliders"
                                   swl-text="{{ __('Do you want to destroy this selected films?') }}">
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        @endcan
                        @can('update', \App\Film::class)
                            <li>
                                <a href="javascript:void(0);"
                                   class="_change-status"
                                   swl-text="{{ __('Do you want change all status of selected films?') }}"
                                   swl-state-alert-title="{{ __('Select status') }}"
                                   swl-select-disable-item="{{ __('Disable') }}"
                                   swl-select-enable-item="{{ __('Enable') }}"
                                   swl-cancel-btn-text="{{ __('Cancel') }}" >
                                    {{ __('Set state') }}
                                </a>
                            </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       class="_change--status--action"
                                       data-field="is_coming_soon"
                                       swl-text="{{ __('Do you want change [:name] status of this selected :obj?', ['name' => __('Is Coming Soon'), 'obj' => __('Films')]) }}"
                                       swl-option-alert-title="{{ __('Select option') }}"
                                       swl-select-no-item="{{ __('No') }}"
                                       swl-select-yes-item="{{ __('Yes') }}"
                                       swl-cancel-btn-text="{{ __('Cancel') }}" >
                                        {{ __('Set [:name] status', ['name' => __('Is Coming Soon')]) }}
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       class="_change--status--action"
                                       data-field="is_open_sale_ticket"
                                       swl-text="{{ __('Do you want change [:name] status of this selected :obj?', ['name' => __('Is Open Sale Ticket'), 'obj' => __('Films')]) }}"
                                       swl-option-alert-title="{{ __('Select option') }}"
                                       swl-select-no-item="{{ __('No') }}"
                                       swl-select-yes-item="{{ __('Yes') }}"
                                       swl-cancel-btn-text="{{ __('Cancel') }}" >
                                        {{ __('Set [:name] status', ['name' => __('Is Open Sale Ticket')]) }}
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       class="_change--status--action"
                                       data-field="is_sneak_show"
                                       swl-text="{{ __('Do you want change [:name] status of this selected :obj?', ['name' => __('Is Sneak Show'), 'obj' => __('Films')]) }}"
                                       swl-option-alert-title="{{ __('Select option') }}"
                                       swl-select-no-item="{{ __('No') }}"
                                       swl-select-yes-item="{{ __('Yes') }}"
                                       swl-cancel-btn-text="{{ __('Cancel') }}" >
                                        {{ __('Set [:name] status', ['name' => __('Is Sneak Show')]) }}
                                    </a>
                                </li>
                        @endcan
                    </ul>
                </div>
            </div>
            <div class="col-md-2 selected-rows-label-container">
                {{ __('Selected') }}&nbsp;<span class="selected-rows-label">0</span>&nbsp;{{ __('records') }}
            </div>
        </div>
    @endcan



    <div class="row">
        <div class="col-md-12 table-responsive">
            <table id="films_ajax_dt"
                   class="display nowrap dmovie-table"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="data-cell-id">ID</th>
                    <th class="data-cell-status">{{ __('Status') }}</th>
                    <th class="data-cell-title">{{ __('Title') }}</th>
                    <th class="data-cell-poster">{{ __('Poster') }}</th>
                    <th class="data-cell-director">{{ __('Director') }}</th>
                    <th class="data-cell-cast">{{ __('Cast') }}</th>
                    <th class="data-cell-genre">{{ __('Genre') }}</th>
                    <th class="data-cell-running_time">{{ __('Running time') }}</th>
                    <th class="data-cell-language">{{ __('Language') }}</th>
                    <th class="data-cell-description">{{ __('Description') }}</th>
                    <th class="data-cell-release_date">{{ __('Release date') }}</th>
                    <th class="data-cell-mark">{{ __('Mark') }}</th>
                    <th class="data-cell-trailer">{{ __('Trailer') }}</th>
                    <th class="data-cell-is_coming_soon">{{ __('Is Coming Soon') }}</th>
                    <th class="data-cell-is_open_sale_ticket">{{ __('Is Open Sale') }}</th>
                    <th class="data-cell-is_sneak_show">{{ __('Is Sneak Show') }}</th>
                    <th class="data-cell-created_at">{{ __('Create Time') }}</th>
                    <th class="data-cell-updated_at">{{ __('Modify Time') }}</th>
                    @can('canEditDelete', \App\Film::class)
                        <th class="no-sort min-width-65 data-cell-task">{{ __('Task') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
