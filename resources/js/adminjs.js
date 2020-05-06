window._ = require('lodash');
try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
require('jquery-ui/ui/widgets/tooltip');
// require('jquery-ui/ui/widgets/datepicker');
require('jquery-slimscroll');
window.Swal = window.Swal = require('sweetalert2');
window.moment = require('moment');
require('select2/dist/js/select2.full');
require('gasparesganga-jquery-loading-overlay');
require('jszip');
var pdfMake = require('pdfmake/build/pdfmake.js');
var pdfFonts = require('pdfmake/build/vfs_fonts.js');
pdfMake.vfs = pdfFonts.pdfMake.vfs;
require('datatables.net-dt');
require('datatables.net-buttons-dt');
// require('datatables.net-buttons-bs4');
require('datatables.net-select-dt');
require('datatables.net-buttons/js/buttons.colVis.js');
require('datatables.net-buttons/js/buttons.html5.js');
require('datatables.net-buttons/js/buttons.print.js');

window.Handlebars = window.Handlebars = require("handlebars");

require('dropify/dist/js/dropify.min');
require('@fancyapps/fancybox');

$(document).ready(function() {
    "use strict";

    $(document).tooltip({
        track: true,
        tooltipClass: 'dmovie-tooltip'
    });

    /* fix on top */
    // cache the element
    var $navBar = $('#dmovie-fix-top-block');
    var $navbarHeader = $('.navbar-default');

    let $navBarContainer = $('.dmovie-fix-top-container');
    if ($navBar.length > 0 && $navbarHeader.length > 0 && $navBarContainer.length > 0) {
        $navBarContainer.height(75);

        var navHeight = $navbarHeader.outerHeight();

        // find original navigation bar position
        var navPos = $navBar.offset().top;
        // on scroll
        $(window).scroll(function() {
            // get scroll position from top of the page
            var scrollPos = $(this).scrollTop();

            // check if scroll position is >= the nav position
            if (scrollPos + navHeight >= navPos) {
                $navBar.addClass('fixed');
                $navBar.find('a').removeClass('m-r-40');
            } else {
                $navBar.removeClass('fixed');
                $navBar.find('a').addClass('m-r-40');
            }

        });
    }
});

let disMissBtn = `<button type="button" class="close" dmovie-noti-dismiss>×</button>`,
    selectedRowsCount = $('.selected-rows-label') || null;
window.langTextSelector = $('.lang-text') || null;

window.mainLang = $('html').attr('lang');

window.tableName = '';
window.dtableSelector = null;
window.dtable = null;
window.$fancybox = null;

window.selectedObjects = [];
window.detailsSelectedObjects = [];
window.exeDeleteRoute = null;

window.errorTitle = langTextSelector.attr('swl-error-title');
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

$(document).on('click', '[dmovie-noti-dismiss]', function() {
    let notiBlock = $(this).closest('div');
    notiBlock.slideToggle(350);
});

// GLOBAL ACTION ///////
/* Select all */
// $(document).on('change', '#checkbox-all', function() {
//     // selectedObjects.length = 0;
//     let rows = dtable.rows({ 'search': 'applied' }).nodes();
//     let check = $('input[grid-item-checkbox]', rows);
//     // $('input[grid-item-checkbox]', rows).prop('checked', this.checked);
//     let count = 0;
//     check.each(function() {
//         let row = $(this).closest('tr');
//         row.toggleClass('dmovie-selected');
//         if (count > 100) {
//             screenLoader();
//         }
//         let uid = $(this).val();
//         if (check.is(':checked')) {
//             selectedObjects.push(uid);
//         } else {
//             selectedObjects = removeAElement(selectedObjects, uid);
//         }
//         count++;
//     });
//     screenLoader(0);
//     appendToSeletedLabel(selectedObjects.length);
// });
/* ./ EnD */

/**
 * Check on checkbox item
 */
// $(document).on('change', 'td[scope="checkbox"]', function() {
//     let checkbox = $(this).find('input[grid-item-checkbox]'),
//         $row = $(this).closest('tr');
//     if (checkbox.prop('checked')) {
//         selectedObjects.push(checkbox.val());
//         $row.addClass('dmovie-selected');
//     } else {
//         let objId = checkbox.val();
//         selectedObjects = window.parent.removeAElement(selectedObjects, objId);
//         $row.removeClass('dmovie-selected');
//     }
//     appendToSeletedLabel(selectedObjects.length);
// });
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
window.ajaxRequest = function(
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
        beforeSend: function() {
            beforeSendCallBack();
        }
    }).done(function(response) {
        doneCallback(response);
    }).fail(function(response) {
        failCallback(response);
    }).always(function() {
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
window.singleDeleteRecord = function(
    url = '',
    data = {},
    trSelector = $(document)
) {
    let dtRow = dtable.row(trSelector);
    try {
        ajaxRequest(
            url,
            'DELETE',
            data,
            function() {
                showLoading(trSelector);
            },
            function(res) {
                if (res.status === 200) {
                    let chkbox = trSelector.find('td[scope="checkbox"] input[type="checkbox"]');

                    if (trSelector.hasClass('selected')) {
                        selectedObjects = removeAElement(selectedObjects, trSelector.attr('data-id'));
                    }

                    if (chkbox.is(':checked')) {
                        selectedObjects = removeAElement(selectedObjects, chkbox.val());
                    }

                    dtRow.remove().draw(false);

                    topRightAlert(res.message)
                    successMessage(res.message);
                } else {
                    errorMessage(res.message);
                }
            },
            function(res) {
                var errorMsg = res.responseJSON.message;
                errorMessage(errorMsg);
            },
            function() {
                hideLoading(trSelector);
                appendToSeletedLabel(selectedObjects.length);
            }
        );
    } catch (e) {
        console.log(e);
    }
};

/**
 * Multi execute request for multi update records
 *
 * @param url
 * @param method
 * @param data
 * @param isClearSelected
 */
window.executeRequest = function(
    url = '',
    method = '',
    data = {},
    dtbl = null,
    isClearSelected = false
) {
    try {
        let ajaxDtSelector = $(`#${tableName}_ajax_dt`);
        ajaxRequest(
            url,
            method,
            data,
            function() {
                screenLoader();
            },
            function(res) {
                if (res.status === 200) {

                    if (dtbl === null) {
                        dtbl = dtable;
                    }
                    reloadDataTable(dtbl).then(value => {
                        if (value) {
                            screenLoader(0);
                        }
                    });

                    if (isClearSelected) { selectedObjects = []; }

                    topRightAlert(res.message);
                    successMessage(res.message);
                } else {
                    errorMessage(res.message);
                }
            },
            function(res) {
                let errorMsg = res.responseJSON.message;
                errorMessage(errorMsg);
            },
            function() {
                screenLoader(0);
                appendToSeletedLabel(selectedObjects.length);
            }
        );
    } catch (e) {
        console.log(e);
    }
};

async function reloadDataTable(dtbl = dtable) {
    return dtbl.draw(false);
}

/**
 * Append number of selected rows to showable section
 *
 * @param number
 */
window.appendToSeletedLabel = function(number = selectedObjects.length) {
    if (selectedRowsCount.length > 0) {
        selectedRowsCount.text(number);
    }
};

/**
 * Init datatable
 *
 * @returns {jQuery}
 */
window.initDataTable = function(invisCols = []) {
    dtableSelector = $(`#${tableName}_data`);
    return dtableSelector.DataTable({
        initComplete: function() {
            let dtb = this.DataTable();
            dtb.columns(invisCols).visible(false);
            let dataWrapper = $(`#${tableName}_data_wrapper`),
                exportBtn = dataWrapper.find('.dt-buttons'),
                selectDropdown = dataWrapper.find(`#${tableName}_data_length`),
                inputFilter = dataWrapper.find(`#${tableName}_data_filter`),
                dropdown = selectDropdown.find(`select`) || null,
                filterInput = inputFilter.find(`input`) || null;
            $fancybox = dataWrapper.find('[dm-fancybox]');

            /** Init fancy box if have */
            if ($fancybox.length > 0) {
                initFancybox($fancybox);
            }
            exportBtn.css({
                width: '100%',
                'margin-bottom': '15px'
            }).find('.dt-button').addClass('dmovie-border margin-0-auto');
            dataWrapper.addClass('overflow-x-auto');
            dropdown.addClass('dmovie-textbox-border h-32 p-l-10');
            filterInput.addClass('dmovie-border h-32 p-l-10').css({
                width: '350px'
            });
        },
        oLanguage: {
            sUrl: `/adminhtml/assets/plugins/datatables/i18n/${mainLang}.json`
        }
    });
};


/* Language text */
let selectAllRowsText = langTextSelector.attr('dt-select-all-rows-text'),
    deselectRowsText = langTextSelector.attr('dt-deselect-rows-text'),
    deselectAllRowsText = langTextSelector.attr('dt-deselect-all-rows-text'),
    deselectCurrentPageRowsText = langTextSelector.attr('dt-deselect-current-rows-text'),
    colVisibleText = langTextSelector.attr('dt-col-visible-text'),
    copySelectedRowsText = langTextSelector.attr('dt-copy-selected-rows-text'),
    exportText = langTextSelector.attr('dt-export-text'),
    asExcelText = langTextSelector.attr('dt-as-excel-text'),
    asCsvText = langTextSelector.attr('dt-as-csv-text'),
    asPdfText = langTextSelector.attr('dt-as-pdf-text'),
    browserPrintText = langTextSelector.attr('dt-browser-print-text'),
    allRecordsText = langTextSelector.attr('dt-all-record-text');
/**
 * Init datatable from server side
 *
 * @param url
 * @returns {jQuery}
 */
window.serverSideDatatable = function(url = '', invisCols, tblName = tableName) {
    let dtableSelector = $(`#${tblName}_ajax_dt`);

    return dtableSelector.DataTable({
        initComplete: function() {
            let dtb = this.DataTable();
            dtb.columns(invisCols).visible(false);
            let dataWrapper = $(`#${tblName}_ajax_dt_wrapper`),
                exportBtn = dataWrapper.find('.dt-buttons'),
                selectDropdown = dataWrapper.find(`#${tblName}_ajax_dt_length`),
                inputFilter = dataWrapper.find(`#${tblName}_ajax_dt_filter`),
                dropdown = selectDropdown.find(`select`) || null,
                filterInput = inputFilter.find(`input`) || null;
            $fancybox = dataWrapper.find('[dm-fancybox]');

            /** Init fancy box if have */
            if ($fancybox.length > 0) {
                initFancybox($fancybox);
            }
            exportBtn.css({
                width: '100%',
                'margin-bottom': '15px'
            }).find('.dt-button').addClass('dmovie-border margin-0-auto');
            dataWrapper.addClass('overflow-x-auto');
            dropdown.addClass('dmovie-textbox-border h-32 p-l-10');
            filterInput.addClass('dmovie-border h-32 p-l-10').css({
                width: '350px'
            });
        },
        serverSide: true,
        processing: true,
        pagingType: 'full_numbers',
        dom: 'Blfrtip',
        buttons: [{
                extend: 'selectAll',
                text: `<i class="mdi mdi-checkbox-multiple-marked-outline"></i><span class="m-l-5">${selectAllRowsText}</span>`
            },
            {
                extend: 'collection',
                text: `<i class="mdi mdi-checkbox-blank-outline"></i><span class="m-l-5">${deselectRowsText}</span>`,
                buttons: [{
                        extend: 'selectNone',
                        text: `<i class="mdi mdi-checkbox-blank-outline"></i><span class="m-l-5" title="${deselectCurrentPageRowsText}">${deselectCurrentPageRowsText}</span>`,
                    },
                    {
                        text: `<i class="mdi mdi-checkbox-blank-outline"></i><span class="m-l-5">${deselectAllRowsText}</span>`,
                        action: function(e, dt, node, config) {
                            if (tblName === tableName) {
                                selectedObjects = [];
                                appendToSeletedLabel(selectedObjects.length);
                            } else {
                                detailsSelectedObjects = [];
                            }
                            dt.draw(false);
                        }
                    }
                ]
            },
            {
                extend: 'colvis',
                columns: ':not(".no-visible-filter")',
                text: `<i class="mdi mdi-eye"></i><span class="m-l-5">${colVisibleText}</span>`
            },
            {
                extend: 'copy',
                text: `<i class="mdi mdi-content-copy"></i><span class="m-l-5">${copySelectedRowsText}</span>`,
                exportOptions: {
                    rows: '.selected',
                    columns: ':visible',
                    orthogonal: 'fullNotes'
                }
            },
            {
                extend: 'collection',
                text: `<i class="mdi mdi-exit-to-app"></i><span class="m-l-5">${exportText}</span>`,
                buttons: [{
                        extend: 'excel',
                        text: `<i class="mdi mdi-file-excel"></i><span class="m-l-5">${asExcelText}</span>`,
                        exportOptions: {
                            rows: '.selected',
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        text: `<i class="mdi mdi-file-chart"></i><span class="m-l-5">${asCsvText}</span>`,
                        charset: 'utf-8',
                        bom: true,
                        exportOptions: {
                            rows: '.selected',
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: `<i class="mdi mdi-file-pdf"></i><span class="m-l-5">${asPdfText}</span>`,
                        exportOptions: {
                            rows: '.selected',
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: `<i class="mdi mdi-printer"></i><span class="m-l-5">${browserPrintText}</span>`,
                        exportOptions: {
                            rows: '.selected',
                            columns: ':visible'
                        }
                    }
                ]
            }
        ],
        lengthMenu: [
            [5, 10, 15, 20, 25, 50, 100, 200, 500, 1000, -1],
            [5, 10, 15, 20, 25, 50, 100, 200, 500, 1000, allRecordsText]
        ],
        pageLength: 10,
        ajax: {
            url: url,
            error: function(xhr, error, thrown) {
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.status === 403) {
                        let noPermissionText = langTextSelector.attr('swl-error-403-text');
                        screenLoader(0);
                        normalAlert(errorTitle, noPermissionText);
                    } else {
                        errorText = langTextSelector.attr('swl-fail-to-load-data-text');
                        swlConfirmBtnText = langTextSelector.attr('swl-refresh-btn-text');
                        showYesNoModal(errorTitle, errorText, swlIcon, function() {
                            window.location.replace(route(`${tblName}`.index));
                        }, false);
                    }
                }
            },
        },
        oLanguage: {
            sUrl: `/adminhtml/assets/plugins/datatables/i18n/${mainLang}.json`
        },
        drawCallback: function(settings) {
            var api = this.api();

            var rows = api.rows();

            rows.every(function(rowIdx, tableLoop, rowLoop) {
                var row = this;
                let data = row.data(),
                    rowNode = $(row.node());

                rowNode.attr('data-id', data.id);
                let selectedObjs = [];
                if (tblName === tableName) {
                    selectedObjs = selectedObjects;
                } else {
                    selectedObjs = detailsSelectedObjects;
                }

                selectedObjs.forEach(eleVal => {
                    if (data.id === eleVal) {
                        row.select();
                    }
                });
            });
        },
        select: {
            style: 'multi',
            selector: 'td:not([not-selector])'
        }
    });
};

/* Handle when dt row be select */
window.handleDtRowSelect = function(table) {
    table.rows('.selected').every(function(rowIdx) {
        let objId = table.row(rowIdx).data().id;
        if (!selectedObjects.includes(objId)) {
            selectedObjects.push(objId);
        }
    });

    appendToSeletedLabel(selectedObjects.length);
}

window.handleDtRowDeselect = function(table) {
    table.rows({ selected: false }).every(function(rowIdx) {
        selectedObjects = removeAElement(selectedObjects, table.row(rowIdx).data().id);
    });

    appendToSeletedLabel(selectedObjects.length);
}

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
 * Get role list bind to select2
 *
 * @param selector
 * @param placeholderRoleText
 * @param dmovieRoleSelectClass
 * @param invalidClass
 * @param unnamed
 */
window.loadRoleSelect2 = function(
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
            beforeSend: function() {
                window.parent.showLoading($('.' + dmovieRoleSelectClass));
            },
            success: function() {
                window.parent.hideLoading($('.' + dmovieRoleSelectClass));
            },
            error: function() {
                window.parent.hideLoading($('.' + dmovieRoleSelectClass));
            },
            data: function(params) {
                return {
                    role_name: params.term,
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data.data, function(val, i) {
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

window.svSideSelect2 = function(
    url,
    selector = $('[dmovie-select2]'),
    callback,
    placeHolderText = 'Select a option',
    dmovieSelect2Class = 'dmovie-select2',
    invalidClass = '',
    data = {}
) {
    return selector.select2({
        placeholder: placeHolderText,
        containerCssClass: dmovieSelect2Class + ' dmovie-border ' + invalidClass,
        pagination: {
            more: true
        },
        ajax: {
            url: url,
            type: 'get',
            dataType: 'json',
            delay: 250,
            beforeSend: function() {
                showLoading($('.' + dmovieSelect2Class));
            },
            success: function() {
                hideLoading($('.' + dmovieSelect2Class));
            },
            error: function() {
                hideLoading($('.' + dmovieSelect2Class));
            },
            data: function(params) {
                return {
                    search_key: params.term,
                    data: data
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data.data, function(val, i) {

                        return callback(val, i);

                    })
                }
            }
        }
    });
}

/**
 * Loader
 *
 * @param type
 * @constructor
 */
window.screenLoader = function(type = 1) {
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
window.showLoading = function(selector) {
    selector.LoadingOverlay("show", {
        zIndex: 1
    });
};
window.hideLoading = function(selector) {
    selector.LoadingOverlay('hide');
};
/** ./END SHOW LOADING FOR BLOCK */

window.showYesNoModal = async function(
    title,
    swalText,
    icon,
    callback,
    showCancelButton = true,
    confirmButtonColor = swlConfirmBtnColor,
    cancelButtonColor = swlCancelBtnColor,
) {
    Swal.fire({
        title: title,
        text: swalText,
        icon: icon,
        showCancelButton: showCancelButton,
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

window.showEnableDisableAlert = function(title = '', options = {}, currentSelect = 0, callback) {
    Swal.fire({
        title: title,
        input: 'select',
        inputOptions: options,
        inputValue: currentSelect,
        customClass: {
            popup: 'border-radius-0',
            input: 'margin-0-auto width-100'
        },
        showCancelButton: true,
        cancelButtonText: swlCancelBtnText,
        inputValidator: function(value) {
            return new Promise(function(resolve, reject) {
                if (value !== '') {
                    resolve();
                }
            });
        }
    }).then(function(result) {
        if (result.value) {
            let newOption = result.value;
            callback(newOption);
        }
    });
}

/**
 * Show normal alert
 *
 * @param errorTitle
 * @param errorText
 */
window.normalAlert = function(errorTitle, errorText) {
    Swal.fire({
        icon: 'error',
        title: errorTitle,
        text: errorText
    })
};

let headSuccessText = langTextSelector.attr('head-success-text');
/**
 * Show alert
 */
window.topRightAlert = function(message, type = 'success', headText = headSuccessText) {
    $.toast({
        heading: headText,
        text: message,
        position: 'top-right',
        loaderBg: '#006ca3',
        bgColor: '#00c193',
        icon: type,
        hideAfter: 5000,
        stack: 6
    });
}

/**
 * Remove element
 *
 * @param arr
 * @param value
 * @returns {*}
 */
window.removeAElement = function(arr, value) {
    return arr.filter(function(ele) {
        return ele !== value;
    });
};

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
/**
 * Init dropify upload
 *
 * @param selector
 * @param langTextSelector
 */
window.imageDropify = function(selector) {
    selector.dropify({
        messages: {
            default: defaultMsg,
            replace: replaceMsg,
            remove: removeMsg,
            error: errorMsg
        }
    });
};

let fancyboxTxtClose = langTextSelector.attr('fancybox-text-close'),
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
window.initFancybox = function(selector) {
    if (selector.length > 0) {
        selector.fancybox({
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
}
