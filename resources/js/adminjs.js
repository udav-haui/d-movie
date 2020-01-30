require('./bootstrap');
require('jquery-slimscroll');
require('@progress/kendo-ui');
window.Swal = window.Swal = require('sweetalert2');
require('select2/dist/js/select2.full');
require('gasparesganga-jquery-loading-overlay');
require('datatables.net-dt');
require('datatables.net-select-dt');
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
    /**
     * Back top top func
     */
    jQuery(window).scroll(function(){
        if(jQuery(window).scrollTop() <= 0){
            jQuery('#rocketmeluncur').slideUp(500);
        }else{
            jQuery('#rocketmeluncur').slideDown(500);
        }
        let ftrocketmeluncur = jQuery("#ft")[0] ? jQuery("#ft")[0] : jQuery(document.body)[0];
        let scrolltoprocketmeluncur = $('#rocketmeluncur');
        let viewPortHeightrocketmeluncur = parseInt(document.documentElement.clientHeight);
        let scrollHeightrocketmeluncur = parseInt(document.body.getBoundingClientRect().top);
        let basewrocketmeluncur = parseInt(ftrocketmeluncur.clientWidth);
        let swrocketmeluncur = scrolltoprocketmeluncur.clientWidth;
        if (basewrocketmeluncur < 1000) {
            let leftrocketmeluncur = parseInt(fetchOffset(ftrocketmeluncur)['left']);
            leftrocketmeluncur = leftrocketmeluncur < swrocketmeluncur ? leftrocketmeluncur * 2 - swrocketmeluncur : leftrocketmeluncur;
            scrolltoprocketmeluncur.style.left = ( basewrocketmeluncur + leftrocketmeluncur ) + 'px';
        } else {
            // scrolltoprocketmeluncur.css({
            //     'left':'auto',
            //     'right':'10px'
            // });
        }
    });

    jQuery('#rocketmeluncur').click(function(){
        jQuery("html, body").animate({ scrollTop: '0px',display:'none'},{
            duration: 600,
            easing: 'linear'
        });

        var self = this;
        this.className += ' '+"launchrocket";
        setTimeout(function(){
            self.className = 'showrocket';
        },800)
    });
    // .////////////////////////////////
});
let disMissBtn = `<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>`;
/**
 * Show success message
 *
 * @param msg
 */
window.successMessage = function (msg) {
    let successMsgBlock = $('.success-block') || null;
    if (successMsgBlock !== null) {
        successMsgBlock.removeClass('display-none').html(disMissBtn + msg);
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
        errorMsgBlock.removeClass('display-none').html(disMissBtn + msg);
    }
};

/**
 * Show warning message
 *
 * @param msg
 */
window.warningMessage = function (msg) {
    let warningMsgBlock = $('.warning') || null;
    if (warningMsgBlock !== null) {
        warningMsgBlock.removeClass('display-none').html(disMissBtn + msg);
    }
};


/**
 * Show loader
 *
 * @param type
 * @constructor
 */
window.showLoader = function (type = 1) {
    let show = 1;
    let page = $('#page-wrapper');
    type === show ? page.LoadingOverlay("show", {
        imageColor: '#6e6e6ee6',
        imageResizeFactor: 0.45,
        imageAnimation: '1750ms'
    }) : page.LoadingOverlay("hide", {
        imageColor: '#6e6e6ee6',
        imageResizeFactor: 0.45,
        imageAnimation: '1750ms'
    });
};

/** Show loading for specifier block */
window.showLoading = function (selector) {
    selector.LoadingOverlay('show');
};
window.hideLoading = function (selector) {
    selector.LoadingOverlay('hide');
};
/** ./END SHOW LOADING FOR BLOCK */

window.showYesNoModal = function (
    title,
    text,
    icon,
    confirmButtonText,
    cancelButtonText,
    callback,
    confirmButtonColor = '#3085d6',
    cancelButtonColor = '#d33',
) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: cancelButtonColor,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        width: '640px'
    }).then((result) => {
        if (result.value) {
            callback();
        }
    })
};

/**
 * Show normal alert
 *
 * @param title
 * @param text
 */
window.normalAlert = function (title, text) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text
    })
};

/**
 * Remove element
 *
 * @param arr
 * @param value
 * @returns {*}
 */
window.removeAElement = function (arr, value) {
    return arr.filter(function(ele) {
        return ele !== value;
    });
}
