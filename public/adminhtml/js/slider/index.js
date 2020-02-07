$(document).ready(function () {
    'use strict';

    tableName = 'sliders';
    swlIcon = langTextSelector.attr('swl-icon-warning-text');

    $.fn.dataTable.defaults.aoColumns = aoColumns;
    $.fn.dataTable.defaults.columnDefs = columnDefs;
    $.fn.dataTable.defaults.order = colOrder;

    dtable = serverSideDatatable();

    // dtable.on('select', function (e, dt, type, indexes) {
    //     if (type === 'row') {
    //
    //         let data = dtable.rows( indexes ).data().pluck( 'id' );
    //
    //         let objId = data[0];
    //
    //         selectedObjects.push(objId);
    //
    //         appendToSeletedLabel(selectedObjects.length);
    //     }
    // });
    // dtable.on('deselect', function (e, dt, type, indexes) {
    //     if (type === 'row') {
    //         let data = dtable.rows( indexes ).data().pluck( 'id' );
    //
    //         let objId = data[0];
    //
    //         selectedObjects = removeAElement(selectedObjects, objId);
    //
    //         appendToSeletedLabel(selectedObjects.length);
    //     }
    // });


    // $.fn.dataTable.defaults.columnDefs = columnDefs;
    // $.fn.dataTable.defaults.order = colOrder;

    // dtable = initDataTable();

    $('#btn-test').on('click', function () {
        dtable.draw();
    });

    /* Change status of slide item */

    /* Change status switch */
    $(document).on('change', '.status-checkbox', function () {
        let self = $(this),
            row = self.closest('tr');

        self.is(':checked') ? self.val(1) : self.val(0);

        let itemId = self.attr('data-id');

        changeItemStatus(self, row, itemId, self.val());

    });

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
                    multiDeleteRecords(exeUrl, data);
                });
            } else {
                /** If not select any row, then show a alert */
                normalAlert(errorTitle, errorText);
            }
        })
    }
});

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
