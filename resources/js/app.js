require('./bootstrap');
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.moment = require('moment');
window.Vue = require('vue');
Vue.mixin(require('./trans'));
window.Vuex = require('vuex');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('select-seats', require('./components/booking/SelectSeats.vue').default);

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
// });

$(document).ready(function () {
    'use strict';




    /* DISMISS NOTIFICATION */

    $(document).on('click', '[dmovie-noti-dismiss]', function() {
        let notiBlock = $(this).closest('div');
        notiBlock.slideToggle(350);
    });

    /* fix on top */
    // cache the element
    var $navBar = $('.header');
    var $navbarHeader = $('.pre-header');

    if ($navBar.length > 0 && $navbarHeader.length > 0) {


        var navHeight = $navbarHeader.outerHeight();

        // find original navigation bar position
        var navPos = $navBar.offset().top;
        // on scroll
        $(window).scroll(function() {
            // console.log($navbarHeader.outerHeight());
            // get scroll position from top of the page
            var scrollPos = $(this).scrollTop();

            // check if scroll position is >= the nav position
            if (scrollPos >= navPos) {
                $('body').addClass('page-header-fixed');
            } else {
                $('body').removeClass('page-header-fixed');
            }

        });
    }

});

let disMissBtn = `<button type="button" class="close" dmovie-noti-dismiss>×</button>`;
/**
 * Show error message
 *
 * @param msg
 */
window.errorMessage = function(msg) {
    let errorMsgBlock = $('.error-block') || null;
    if (errorMsgBlock !== null) {
        errorMsgBlock.removeClass('display-none').slideDown(500).html(disMissBtn + msg);
    }
};


/**
 * Show success message
 *
 * @param msg
 */
window.successMessage = function(msg) {
    let successMsgBlock = $('.success-block') || null;
    if (successMsgBlock !== null) {
        successMsgBlock.slideDown(500).html(disMissBtn + msg);
    }
};


/**
 * Show warning message
 *
 * @param msg
 */
window.warningMessage = function(msg) {
    let warningMsgBlock = $('.warning') || null;
    if (warningMsgBlock !== null) {
        warningMsgBlock.removeClass('display-none').slideDown(500).html(disMissBtn + msg);
    }
};

/**
 * Convert vi to en (khong dau)
 *
 * @param text
 * @param isReplaceSpaceToMinus
 * @param isConvertUpperToLower
 * @returns {string}
 */
window.convert_vi_to_en = function (text, isReplaceSpaceToMinus = true, isConvertUpperToLower = true)
{
    var str = text;
    str = str.normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/đ/g,"d")
        .replace(/Đ/g,"D");
    return str;
};
//
// window.convert_vi_to_en = function (text, isReplaceSpaceToMinus = true, isConvertUpperToLower = true) {
//     var str = text;
//     if (isConvertUpperToLower) {
//         str = str.toLowerCase();
//     }
//     str = str.replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g,"a");
//     str = str.replace(/[ÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴ]/g,"A");
//     str = str.replace(/[èéẹẻẽêềếệểễ]/g,"e");
//     str = str.replace(/[ÈÉẸẺẼÊỀẾỆỂỄ]/g,"E");
//     str = str.replace(/[ìíịỉĩ]/g,"i");
//     str = str.replace(/[ÌÍỊỈĨ]/g,"I");
//     str = str.replace(/[òóọỏõôồốộổỗơờớợởỡ]/g,"o");
//     str = str.replace(/[ÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠ]/g,"O");
//     str = str.replace(/[ùúụủũưừứựửữ]/g,"u");
//     str = str.replace(/[ÙÚỤỦŨƯỪỨỰỬỮ]/g,"U");
//     str = str.replace(/[ỳýỵỷỹ]/g,"y");
//     str = str.replace(/[ỲÝỴỶỸ]/g,"Y");
//     str = str.replace(/đ/g,"d");
//     str = str.replace(/Đ/g,"D");
//     str = str.replace(/[!@%^*()+=<>?\/,.:;'"&#\[\]~$_`{}|\\]/g," ");
//     str = str.replace(/ + /g," ");
//     str = str.trim();
//     if (isReplaceSpaceToMinus) {
//         str = str.replace(/\s+/g, "-");
//     }
//     return str;
// };

/**
 * Countdown js
 *
 * @param selector
 * @param minuteNumber
 */
window.countdown = function (selector, minuteNumber) {
    // Set the date we're counting down to
// var countDownDate = new Date();
    var countDownDate = new Date();
    countDownDate.setMinutes( countDownDate.getMinutes() + minuteNumber );

// Update the count down every 1 second
    var x = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        // var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        // var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        document.querySelector(selector).innerHTML = minutes + ":" + seconds;

        // If the count down is over, write some text
        if (distance < 0) {
            window.location.href = '/';
        }
    }, 1000);
};
