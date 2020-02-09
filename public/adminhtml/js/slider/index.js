$(document).ready(function () {
    'use strict';

    tableName = 'sliders';
    swlIcon = langTextSelector.attr('swl-icon-warning-text');

    $.fn.dataTable.defaults.aoColumns = aoColumns;
    $.fn.dataTable.defaults.columnDefs = columnDefs;
    $.fn.dataTable.defaults.order = colOrder;

    /* Draw data table */
    dtable = serverSideDatatable(route('sliders.index'));

    /* When dt row is select */
    dtable.on('select.dt', function () {
        handleDtRowSelect(dtable);
    });

    /* When dt row is deselect */
    dtable.on('deselect.dt', function ( ) {
        handleDtRowDeselect(dtable);
    });

    /* Processing overlay */
    dtable.on('processing.dt', function(e, settings, processing) {
        $(`#${tableName}_ajax_dt_processing`).css('display', 'none');
        if (processing) {
            screenLoader();
        } else {
            screenLoader(0);
        }
    });

    $('#btn-test').on('click', function () {
        dtable.draw();
    });

    /* Change status switch */
    $(document).on('change', '.status-checkbox', function () {
        let self = $(this),
            row = self.closest('tr');

        self.is(':checked') ? self.val(1) : self.val(0);

        let itemId = self.attr('data-id');

        changeItemStatus(self, row, itemId, self.val());

    });

    /* Change status of slide item */
    let multiChangeStatus = $('._change-status-sliders');
    if (multiChangeStatus.length > 0) {
        swlText = langTextSelector.attr('swl-slider-change-status-text');
        multiChangeStatus.click(function () {
            if (selectedObjects.length > 0) {
                showYesNoModal(swlTitle, swlText, swlIcon, function () {
                    let statusSelectionTitle = langTextSelector.attr('swl-select-status-title'),
                        enableText = langTextSelector.attr('enable-text'),
                        disableText = langTextSelector.attr('disable-text'),
                        options = {
                            '0': disableText,
                            '1': enableText
                        };
                    showEnableDisableAlert(
                        statusSelectionTitle,
                        options,
                        0,
                        function (newStatus) {
                            let exeUrl = route('slider.multiChangeStatus'),
                                data = {
                                    ids: selectedObjects,
                                    status: newStatus
                                };
                            try {
                                ajaxRequest(
                                    exeUrl,
                                    'POST',
                                    data,
                                    function () {
                                        screenLoader();
                                    },
                                    function (res) {
                                        if (res.status === 200) {

                                            selectedObjects.forEach(value => {
                                                dtable.$(`td[scope="status"]`).each(function () {
                                                    let checkbox = $(this).find('input'),
                                                        id = checkbox.val();
                                                    if (id === value) {
                                                        let switcher = checkbox,
                                                            isChecked = parseInt(newStatus) === 1;

                                                        switcher.prop('checked', isChecked);

                                                        $(this).find('.status-text').text(res.data.text);

                                                    }
                                                });
                                            });

                                            successMessage(res.message);
                                        } else {
                                            errorMessage(res.message);
                                        }
                                    },
                                    function (res) {
                                        let errorMsg = res.responseJSON.message;
                                        errorMessage(errorMsg);
                                    },
                                    function () {
                                        screenLoader(0);
                                    }
                                );
                            } catch (e) {
                                console.log(e);
                            }
                        }
                    );
                });
            } else {
                normalAlert(errorTitle, errorText);
            }

        });
    }

    /* Delete slide item */
    $(`#${tableName}_ajax_dt tbody`).on('click', '#deleteBtn', function () {
        let self = $(this);
        let url = self.attr('url');
        let tr = self.closest('tr');
        showYesNoModal(swlTitle, swlSingDeleteText, swlIcon, function () {
            singleDeleteRecord(
                url,
                {},
                tr
            );
        } );
    });

    /* Multi delete */
    let multiDeleteBtn = $('._delete-sliders');
    if (multiDeleteBtn.length > 0) {
        let swalText = multiDeleteBtn.attr('swl-text');
        multiDeleteBtn.on('click', function () {
            if (selectedObjects.length > 0) {
                showYesNoModal(swlTitle, swlMultiDeleteText, swlIcon, function () {
                    let exeUrl = route(`sliders.multiDestroy`),
                        data = {ids: selectedObjects};
                    executeRequest(exeUrl, 'DELETE', data, true);
                });
            } else {
                /** If not select any row, then show a alert */
                normalAlert(errorTitle, errorText);
            }
        })
    }
});

function test() {
    dtable.button('0').trigger();
}

function triggerSelectAllRows() {
    dtable.button('0').trigger();
}

/**
 * Change item status
 *
 * @param targetBtn
 * @param itemID
 * @param newStatus
 */
function changeItemStatus(targetBtn, row, itemID, newStatus) {
    ajaxRequest(
        route('sliders.changeStatus', {slider: itemID}),
        'POST',
        {
            status: newStatus
        },
        function () {
            showLoading(row);
        },
        function (res) {
            if (res.status === 200) {
                window.parent.successMessage(res.message);

                let statusCol = row.find('td[scope="status"]');

                statusCol.find('.status-text').text(res.data.text);
            } else {
                window.parent.errorMessage(res.message);
            }
            hideLoading(row);
        },
        function (res) {
            targetBtn.prop('checked', !targetBtn.prop('checked'));

            let errorMsg = res.responseJSON.message;

            errorMessage(errorMsg);

            window.parent.hideLoading(row);
        },
    );
}
