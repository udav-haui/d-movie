require('./bootstrap');
require('jquery-slimscroll');
require('@progress/kendo-ui');
window.Swal = window.Swal = require('sweetalert2');
require('select2/dist/js/select2.full');
require('gasparesganga-jquery-loading-overlay');
require('datatables.net-dt');
// require('datatables.net-select-dt');
require('dropify/dist/js/dropify.min');
require('@fancyapps/fancybox');

$(document).ready(function () {
    "use strict";

    /**
     * Back top top func
     */
    $(window).scroll(function(){
        if($(window).scrollTop() <= 0){
            jQuery('#rocketmeluncur').slideUp(500);
        }else{
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

    $('#rocketmeluncur').click(function(){
        $("html, body").animate({ scrollTop: '0px',display:'none'},{
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

let disMissBtn = `<button type="button" class="close" dmovie-noti-dismiss>×</button>`,
    selectedRowsCount = $('.selected-rows-label') || null;
window.langTextSelector = $('.lang-text') || null;

window.mainLang = $('html').attr('lang');

window.tableName = '';
window.dtable = null;

window.selectedObjects = [];

window.errorTitle = langTextSelector.attr('swl-error-title'),
window.errorText = langTextSelector.attr('swl-error-text-must-select-one-record');
window.swlTitle = langTextSelector.attr('swl-title-text');
window.swlText = '';
window.swlIcon = '';
window.swlSingDeleteText = langTextSelector.attr('swl-single-delete-text');
window.swlMultiDeleteText = langTextSelector.attr('swl-multi-delete-text');
window.swlConfirmBtnText = langTextSelector.attr('swl-confirm-btn-text');
window.swlCancelBtnText = langTextSelector.attr('swl-cancel-btn-text');
window.swlConfirmBtnColor = langTextSelector.attr('swl-confirm-btn-color');
window.swlCancelBtnColor = langTextSelector.attr('swl-cancel-btn-color');


/* DISMISS NOTIFICATION */

$( document ).on('click', '[dmovie-noti-dismiss]', function () {
    let notiBlock = $(this).closest('div');
    notiBlock.slideToggle(500);
});

// GLOBAL ACTION ///////
/**
 * Select all
 */
$(document).on('change', '#checkbox-all', function () {
    // selectedObjects.length = 0;
    let rows = dtable.rows({ 'search': 'applied' }).nodes();
    let check = $('input[grid-item-checkbox]', rows);
    $('input[grid-item-checkbox]', rows).prop('checked', this.checked);
    let count = 0;
    check.each(function () {
        if (count > 100) {
            screenLoader();
        }
        let uid = $(this).val();
        if (check.is(':checked')) {
            selectedObjects.push(uid);
        } else {
            selectedObjects = window.parent.removeAElement(selectedObjects, uid);
        }
        count++;
    });
    screenLoader(0);
    appendToSeletedLabel(selectedObjects.length);
});
/* ./ EnD */

/**
 * Check on checkbox item
 */
$(document).on('change', 'td[scope="checkbox"]', function () {
    let checkbox = $(this).find('input[grid-item-checkbox]');
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
 * Send ajax request
 *
 * @param url
 * @param method
 * @param data
 * @param beforeSendCallBack
 * @param doneCallback
 * @param failCallback
 * @param alwaysCallback
 */
window.ajaxRequest = function (
    url = '',
    method = '',
    data = {},
    beforeSendCallBack,
    doneCallback = null,
    failCallback = null,
    alwaysCallback = null
) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        datatype: 'json',
        beforeSend: function () {
            beforeSendCallBack();
        }
    }).done(function (response) {
        doneCallback(response);
    }).fail(function (response) {
        failCallback(response);
    }).always(function () {
        if (alwaysCallback != null) {
            alwaysCallback();
        }
    });
};

/**
 * Delete a data table row record
 *
 * @param url
 * @param data
 * @param trSelector
 */
window.singleDeleteRecord = function (
    url = '',
    data = {},
    trSelector = $(document)
) {
    let dtRow = dtable.row(trSelector);
    let result = null;
    try {
        ajaxRequest(
            url,
            'DELETE',
            data,
            function () {
                showLoading(trSelector);
            },
            function (res) {
                if (res.status === 200) {
                    var chkbox = trSelector.find('td[scope="checkbox"] input[type="checkbox"]');

                    if (chkbox.is(':checked')) {
                        selectedObjects = removeAElement(selectedObjects, chkbox.val());
                        appendToSeletedLabel(selectedObjects.length);
                    }

                    successMessage(res.message);
                } else {
                    hideLoading(trSelector);
                    errorMessage(res.message);
                }
            },
            function (res) {
                var errorMsg = res.responseJSON.message;
                errorMessage(errorMsg);
                hideLoading(trSelector);
            }
        ).done(function () {
            dtRow.deselect().remove().draw(true);
        });
    } catch (e) {
        console.log(e);
    }

    return result;
};

window.multiDeleteRecords = function (
    url = '',
    data = {},

) {
    try {
        let ajaxDtSelector = $(`#${tableName}_ajax_dt`);
        ajaxRequest(
            url,
            'DELETE',
            data,
            function () {
                screenLoader();
            },
            function (res) {
                if (res.status === 200) {


                    test().then(value => {
                        if (value) {
                            screenLoader(0);
                        }
                    });

                    selectedObjects = [];

                    successMessage(res.message);
                } else {
                    errorMessage(res.message);
                }
            },
            function (res) {
                let errorMsg = res.responseJSON.message;
                errorMessage(errorMsg);
                screenLoader(0);
            },
            function () {
                appendToSeletedLabel(selectedObjects.length);
            }
        );
    } catch (e) {
        console.log(e);
    }
};

async function test () {
    return dtable.draw(false);
}

/**
 * Append number of selected rows to showable section
 *
 * @param number
 */
window.appendToSeletedLabel = function (number = 0) {
    if (selectedRowsCount.length > 0) {
        selectedRowsCount.text(number);
    }
};

/**
 * Init datatable
 *
 * @returns {jQuery}
 */
window.initDataTable = function() {
    let dtableSelector = $(`#${tableName}_data`);
    return dtableSelector.DataTable({
        initComplete: function (settings, json) {
            let dataWrapper = $(`#${tableName}_data_wrapper`),
                selectDropdown = dataWrapper.find(`#${tableName}_data_length`),
                inputFilter = dataWrapper.find(`#${tableName}_data_filter`),
                dropdown = selectDropdown.find(`select`) || null,
                filterInput = inputFilter.find(`input`) || null;
            dropdown.addClass('dmovie-textbox-border h-32 p-l-10');
            filterInput.addClass('dmovie-border h-32 p-l-10');
        },
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
        successMsgBlock.slideDown(500).html(disMissBtn + msg);
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
        errorMsgBlock.removeClass('display-none').slideDown(500).html(disMissBtn + msg);
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
        warningMsgBlock.removeClass('display-none').slideDown(500).html(disMissBtn + msg);
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
 * Loader
 *
 * @param type
 * @constructor
 */
window.screenLoader = function (type = 1) {
    let show = 1;
    let page = $('body');
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
    selector.LoadingOverlay("show", {
        zIndex: 1
    });
};
window.hideLoading = function (selector) {
    selector.LoadingOverlay('hide');
};
/** ./END SHOW LOADING FOR BLOCK */

window.showYesNoModal = async function (
    title,
    swalText,
    icon,
    callback,
    confirmButtonColor = swlConfirmBtnColor,
    cancelButtonColor = swlCancelBtnColor,
) {
    Swal.fire({
        title: title,
        text: swalText,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: cancelButtonColor,
        confirmButtonText: swlConfirmBtnText,
        cancelButtonText: swlCancelBtnText,
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
window.imageDropify = function (selector) {
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
};

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
