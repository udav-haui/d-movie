$(document).ready(function () {
    'use strict';

    $.fn.dataTable.defaults.aoColumns = aoColumns;

    dtable = $('#sliders_ajax').DataTable({
        serverSide: true,
        processing: true,
        lengthMenu: [
            [5, 10, 15, 20, 25, 50, 100, 200, 500, 1000, -1],
            [5, 10, 15, 20, 25, 50, 100, 200, 500, 1000, "All"]
        ],
        pageLength: 15,
        ajax: {
            url: route('sliders.index')
        },

        columnDefs: [
            {
                targets: 0,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'checkbox');
                },
                checkboxes: {
                    selectRow: true,
                        selectAllRender: `<input type="checkbox" id="checkbox-all" />`
                }
            },
            {
                targets: 1,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'id');
                }
            },
            {
                targets: 5,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'status');
                }
            },
            {
                targets: ['no-sort', 0],
                orderable: false
            },
        ],
        order: [[1, 'asc']]
    });

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

    tableName = 'sliders';
    swlIcon = langTextSelector.attr('swl-icon-warning-text');

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
    $(`#${tableName}_data tbody`).on('click', '#deleteBtn', function () {
        let self = $(this);
        let url = self.attr('url');
        let tr = self.closest('tr');
        let id = self.attr('data-id');
        showYesNoModal(swlTitle, swlSingDeleteText, swlIcon, function () {
            deleteRowRecord(
                url,
                {},
                tr
            ).then((result) => console.log(result));
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
