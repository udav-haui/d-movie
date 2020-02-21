$(document).ready(function () {
    'use strict';

    initBackToTop();
});

function initBackToTop() {
    /* Back top top func */
    $(window).scroll(function() {
        if ($(window).scrollTop() <= 0) {
            jQuery('#rocketmeluncur').slideUp(500);
        } else {
            jQuery('#rocketmeluncur').slideDown(500);
        }
        let ftrocketmeluncur = $("#ft")[0] ? $("#ft")[0] : $(document.body)[0];
        let scrolltoprocketmeluncur = $('#rocketmeluncur');
        let viewPortHeightrocketmeluncur = parseInt(document.documentElement.clientHeight);
        let scrollHeightrocketmeluncur = parseInt(document.body.getBoundingClientRect().top);
        let basewrocketmeluncur = parseInt(ftrocketmeluncur.clientWidth);
        let swrocketmeluncur = scrolltoprocketmeluncur.clientWidth;
        // if (basewrocketmeluncur < 1000) {
        //     let leftrocketmeluncur = parseInt(fetchOffset(ftrocketmeluncur)['left']);
        //     leftrocketmeluncur = leftrocketmeluncur < swrocketmeluncur ? leftrocketmeluncur * 2 - swrocketmeluncur : leftrocketmeluncur;
        //     scrolltoprocketmeluncur.style.left = ( basewrocketmeluncur + leftrocketmeluncur ) + 'px';
        // } else {
        //     // scrolltoprocketmeluncur.css({
        //     //     'left':'auto',
        //     //     'right':'10px'
        //     // });
        // }
    });

    $('#rocketmeluncur').click(function() {
        $("html, body").animate({ scrollTop: '0px', display: 'none' }, {
            duration: 600,
            easing: 'linear'
        });

        var self = this;
        this.className += ' ' + "launchrocket";
        setTimeout(function() {
            self.className = 'showrocket';
        }, 800)
    });
    // .////////////////////////////////
}
