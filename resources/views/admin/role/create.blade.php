@extends('admin.layouts.app')
@section('app.title'){{ __('New Role') }}
@endsection
@section('app.description')
    {{ __('New Role') }}
@endsection
@section('titlebar.title')
    {{ __('New Role') }}
@endsection
@section('titlebar.breadcrumb')
    <li><a href="/admin">{{ __('Dashboard') }}</a></li>
    <li><a href="/admin/roles">{{ __('Roles Manage') }}</a></li>
    <li class="active">{{ __('New Role') }}</li>
@endsection
@section('content')
    <div class="row bg-title">
        <div class="col-md-2 col-xs-12 pull-right">
            <a href="/admin/roles/create"
               class="btn btn-block btn-default dmovie-btn dmovie-btn-success p-t-20 p-b-20">
                {{ __('Save') }}
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 form-horizontal">
            <div class="form-group">
                <label for="name" class="col-sm-5 control-label">{{ __('Role name') }} <strong class="text-danger">*</strong></label>
                <div class="col-sm-2">
                    <input name="role_name" type="text" class="form-control" id="name" placeholder="{{ __('Role name') }}"> </div>
            </div>
        </div>
    </div>
@endsection
