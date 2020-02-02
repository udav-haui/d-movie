require('./bootstrap');
require('jquery-slimscroll');
require('@progress/kendo-ui');
window.Swal = window.Swal = require('sweetalert2');
require('select2/dist/js/select2.full');
require('gasparesganga-jquery-loading-overlay');
require('datatables.net-dt');
require('dropify/dist/js/dropify.min');
require('@fancyapps/fancybox');

$(document).ready(function () {
    "use strict";

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

let disMissBtn = `<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>`,
    selectedRowsCount = $('.selected-rows-label') || null,
    langTextSelector = $('.lang-text') || null;
window.mainLang = $('html').attr('lang');
window.selectedObjects = [];
window.dtable = null;


// GLOBAL ACTION ///////
/**
 * Select all
 */
$('#checkbox-all').on('click', function () {
    selectedObjects.length = 0;
    let rows = dtable.rows({ 'search': 'applied' }).nodes();
    let check = $('input[type="checkbox"]', rows);
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
    check.each(function () {
        let uid = $(this).val();
        if (check.is(':checked')) {
            selectedObjects.push(uid);
        } else {
            selectedObjects = window.parent.removeAElement(selectedObjects, uid);
        }
    });

    appendToSeletedLabel(selectedObjects.length);
});
/* ./ EnD */

/**
 * Check on checkbox item
 */
$(`td[scope="checkbox"]`).on('change', function () {
    let checkbox = $(this).find('input[type="checkbox"]');
    if (checkbox.prop('checked')) {
        selectedObjects.push(checkbox.val());
    } else {
        let objId = checkbox.val();
        selectedObjects = window.parent.removeAElement(selectedObjects, objId);
    }
    appendToSeletedLabel(selectedObjects.length);
});
/** ./End */
// .////////////////////



/**
 * Append number of selected rows to showable section
 *
 * @param number
 */
appendToSeletedLabel = function (number = 0) {
    if (selectedRowsCount.length > 0) {
        selectedRowsCount.text(number);
    }
}
/**
 * Init datatable
 *
 * @param selector
 * @param tableName
 * @returns {jQuery}
 */
window.initDataTable = function(selector, tableName) {
    return selector.DataTable({
        initComplete: function (settings, json) {
            let dataWrapper = $(`#${tableName}_data_wrapper`),
                selectDropdown = dataWrapper.find(`#${tableName}_data_length`),
                inputFilter = dataWrapper.find(`#${tableName}_data_filter`),
                dropdown = selectDropdown.find(`select`) || null,
                filterInput = inputFilter.find(`input`) || null;
            dropdown.addClass('dmovie-textbox-border h-32 p-l-10');
            filterInput.addClass('dmovie-border h-32 p-l-10');
        },
        order: [
            [1, 'desc']
        ],
        oLanguage: {
            sUrl: `/adminhtml/assets/plugins/datatables/i18n/${mainLang}.json`
        }
    });
};

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
 * Get role list bind to select2
 *
 * @param selector
 * @param placeholderRoleText
 * @param dmovieRoleSelectClass
 * @param invalidClass
 * @param unnamed
 */
window.loadRoleSelect2 = function (
    selector = $('#role_select2'),
    placeholderRoleText = 'Select role',
    dmovieRoleSelectClass = 'dmovie-role-select2',
    invalidClass = '',
    unnamed = 'Unnamed'
) {
    selector.select2({
        placeholder: placeholderRoleText,
        containerCssClass: dmovieRoleSelectClass + ' dmovie-select2-selection-border ' + invalidClass,
        pagination: {
            more: true,
        },
        ajax: {
            url: route('roles.getRoles'),
            type: 'get',
            dataType: 'json',
            delay: 250,
            beforeSend: function () {
                window.parent.showLoading($('.' + dmovieRoleSelectClass));
            },
            success: function () {
                window.parent.hideLoading($('.' + dmovieRoleSelectClass));
            },
            error: function () {
                window.parent.hideLoading($('.' + dmovieRoleSelectClass));
            },
            data: function (params) {
                return {
                    role_name: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (val, i) {
                        return {
                            id: val.id,
                            text: val.role_name == null || val.role_name === '' ? `ID: ${val.id} - ${unnamed}` : val.role_name
                        };
                    })
                }
            }
        }
    });
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
 * @param errorTitle
 * @param errorText
 */
window.normalAlert = function (errorTitle, errorText) {
    Swal.fire({
        icon: 'error',
        title: errorTitle,
        text: errorText
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
};

/**
 * Init dropify upload
 *
 * @param selector
 * @param langTextSelector
 */
window.imageDropify = function (selector, langTextSelector = $('.lang-text')) {
    let defaultMsg = '',
        replaceMsg = '',
        removeMsg = '',
        errorMsg = '';
    if (langTextSelector.length > 0) {
        defaultMsg = langTextSelector.attr('dropify-msg-default');
        replaceMsg = langTextSelector.attr('dropify-msg-replace');
        removeMsg = langTextSelector.attr('dropify-msg-remove');
        errorMsg = langTextSelector.attr('dropify-msg-error');
    }
    selector.dropify({
        messages: {
            default: defaultMsg,
            replace: replaceMsg,
            remove: removeMsg,
            error: errorMsg
        }
    });
}

let imgFancybox = $( '[data-fancybox]' ),
    fancyboxTxtClose = langTextSelector.attr('fancybox-text-close'),
    fancyboxTxtNext = langTextSelector.attr('fancybox-text-next'),
    fancyboxTxtPrev = langTextSelector.attr('fancybox-text-previous'),
    fancyboxTxtError = langTextSelector.attr('fancybox-text-error'),
    fancyboxTxtStart = langTextSelector.attr('fancybox-text-start-slideshow'),
    fancyboxTxtPause = langTextSelector.attr('fancybox-text-pause-slideshow'),
    fancyboxTxtFullScreen = langTextSelector.attr('fancybox-text-full-screen'),
    fancyboxTxtThumbs = langTextSelector.attr('fancybox-text-thumbs'),
    fancyboxTxtDownload = langTextSelector.attr('fancybox-text-download'),
    fancyboxTxtShare = langTextSelector.attr('fancybox-text-share'),
    fancyboxTxtZoom = langTextSelector.attr('fancybox-text-zoom');
if (imgFancybox.length > 0) {
    imgFancybox.fancybox({
        lang: mainLang,
        i18n: {
            en: {
                CLOSE: "Close",
                NEXT: "Next",
                PREV: "Previous",
                ERROR: "The requested content cannot be loaded. <br/> Please try again later.",
                PLAY_START: "Start slideshow",
                PLAY_STOP: "Pause slideshow",
                FULL_SCREEN: "Full screen",
                THUMBS: "Thumbnails",
                DOWNLOAD: "Download",
                SHARE: "Share",
                ZOOM: "Zoom"
            },
            vi: {
                CLOSE: fancyboxTxtClose,
                NEXT: fancyboxTxtNext,
                PREV: fancyboxTxtPrev,
                ERROR: fancyboxTxtError,
                PLAY_START: fancyboxTxtStart,
                PLAY_STOP: fancyboxTxtPause,
                FULL_SCREEN: fancyboxTxtFullScreen,
                THUMBS: fancyboxTxtThumbs,
                DOWNLOAD: fancyboxTxtDownload,
                SHARE: fancyboxTxtShare,
                ZOOM: fancyboxTxtZoom
            }
        }
    });
}
