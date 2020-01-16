window.$ = window.jQuery = require('jquery');
require('./bootstrap');
require('jquery-slimscroll');
jQuery(document).ready(function ($) {
    "use strict";
    $('#user-profile').on('click', function () {
        $(this).attr('href', '/admin/user/' + $(this).attr('data-id'));
    });
});
