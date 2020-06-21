@extends('admin.store.config_index')

<?php
    $parentClass = 'active';
    $childClass = 'menu-open';
    $sectionActiveClass = 'section-active';
    $activeSection = $section ??
        [
            'parent' => null,
            'active_child' => null
        ];
?>

@section('child.bottom.js')
    <script src="{{ asset('adminhtml/js/store_config/admin_store_config.js') }}"></script>
@endsection

@section('section.sales.activeClass')
    {{ $activeSection['parent'] == 'sales' ? $parentClass : null }}
@endsection

@section('section.sales.submenu.activeClass')
    {{ $activeSection['active_child'] ? $childClass : null }}
@endsection

@section('section.sales.subitem.payment_methods.activeClass')
    {{ $activeSection['active_child'] == 'payment_methods' ? $sectionActiveClass : null }}
@endsection

@section('config.content')
    <div id="config_content">
        <payment-methods
            partner-code-place-holder-text-hint = "{{ __('Place your partner code here') }}"
            access-key-place-holder-text-hint = "{{ __('Place your access key here') }}"
            secret-key-place-holder-text-hint = "{{ __('Place your secret key here') }}"
            end-point-place-holder-text-hint = "{{ __('Place the end point of payment method') }}"
        ></payment-methods>
    </div>
@endsection
