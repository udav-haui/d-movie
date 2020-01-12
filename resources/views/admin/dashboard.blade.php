@extends('admin.layouts.app')
@section('app.title')
    {{ __('Dashboard') }}
@endsection
@section('app.description')
    Đây là trang tổng quan
@endsection
@section('bottom.js')
    <script src="{{ asset('adminhtml/assets/js/dashboard1.js') }}"></script>
@endsection
