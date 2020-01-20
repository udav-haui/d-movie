window.$ = window.jQuery = require('jquery');
require('./bootstrap');
require('jquery-slimscroll');
require('@progress/kendo-ui');
window.Swal = window.Swal = require('sweetalert2');
require('select2/dist/js/select2.full');
require('gasparesganga-jquery-loading-overlay');
jQuery(document).ready(function ($) {
    "use strict";
    /**
     * Ajax loading overlay
     */
    // $(document).ajaxStart(function(){
    //     $('#page-wrapper').LoadingOverlay("show");
    // });
    // $(document).ajaxStop(function(){
    //     $('#page-wrapper').LoadingOverlay("hide");
    // });
    /**
     * /end
     */
    $('#user-profile').on('click', function () {
        $(this).attr('href', '/admin/user/' + $(this).attr('data-id'));
    });
    let actionBlock = $('#dmovie-fix-top-block').offset();
    if (actionBlock !== undefined) {
        $(window).bind('scroll', function () {
            let topNavHeight = $('.navbar-header').outerHeight();
            let lefSideBar = $('.sidebar').outerWidth();
            //console.log($(window).height());
            // console.log($(window).scrollTop());
            //console.log(actionNav.top);
            // var navHeight = $(window).height() - 102;$('.active-nav').height() +
            // console.log(navHeight);
            let fixedPoint = $(window).scrollTop() + topNavHeight - actionBlock.top;

            if (fixedPoint >= 0) {
                $('#dmovie-fix-top-block').addClass('dmovie-fix-top-block').css({
                    'top': topNavHeight,
                    'width': 'calc(100% - ' + lefSideBar + 'px)'
                });
                //$('.active-nav').removeClass('ml-3 mr-3');
            }
            else {
                $('#dmovie-fix-top-block').removeClass('dmovie-fix-top-block').css('width', 'auto');
                //$('.active-nav').addClass('ml-3 mr-3');
            }
        });
    }
});

/**
 * Show success message
 *
 * @param msg
 */
window.successMessage = function (msg) {
    let successMsgBlock = $('.success-block') || null;
    if (successMsgBlock !== null) {
        successMsgBlock.removeClass('display-none').append(msg);
    }
};

/**
 * Show error message
 *
 * @param msg
 */
window.errorMessage = function (msg) {
    let errorMsgBlock = $('.error-block') || null;
    if (errorMsgBlock !== null) {
        errorMsgBlock.removeClass('display-none').append(msg);
    }
};

/**
 * Show loader
 *
 * @param type
 * @constructor
 */
window.showLoader = function (type = 1) {
    let page = $('#page-wrapper');
    type === 1 ? page.LoadingOverlay("show", {
        imageColor: '#6e6e6ee6',
        imageResizeFactor: 0.45,
        imageAnimation: '1750ms'
    }) : page.LoadingOverlay("hide", {
        imageColor: '#6e6e6ee6',
        imageResizeFactor: 0.45,
        imageAnimation: '1750ms'
    });
};
