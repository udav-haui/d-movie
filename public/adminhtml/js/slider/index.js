$(document).ready(function () {
    'use strict';

    dtable = $('#sliders_ajax').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: route('sliders.index')
        },
        columns: [
            {
                targets: 0,
                data: 'id',
                render: function (data, type, full, meta) {
                    return `<div class="dmovie-checkbox dmovie-checkbox-custom">
                                    <input value="${data}" id="checkbox-${data}"
                                           type="checkbox"
                                           grid-item-checkbox
                                           class="dt-checkboxes display-none user-checkbox">
                                    <label for="checkbox-${data}" class="cursor-pointer"></label>
                                </div>`;
                }
            },
            {
                data: 'id',
                name: 'id'
            },
            {
                data: 'title',
                name: 'title'
            },
            {
                data: 'order',
                name: 'order'
            }
        ],
        columnDefs: [
            {
                targets: 0,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('scope', 'checkbox');
                    // let chkInput = $(td).find('input');
                    // chkInput.val(cellData);
                    // chkInput.attr('grid-item-checkbox', "dmovie-checkbox");
                },
                checkboxes: {
                    selectRow: true,
                    selectAllRender: `<div class="dmovie-checkbox dmovie-checkbox-custom">
                                <input value="0" id="checkbox-all" type="checkbox"
                                       class="display-none user-checkbox">
                                <label for="checkbox-all" class="cursor-pointer background-fff"></label>
                            </div>`,
                }
            },
            {
                targets: ['no-sort', 0],
                orderable: false
            },
        ],
        select: {
            style: 'multi',
        },
        order: [[1, 'asc']]
    });

    dtable.on('select', function (e, dt, type, indexes) {
        if (type === 'row') {

            let data = dtable.rows( indexes ).data().pluck( 'id' );

            let objId = data[0];

            selectedObjects.push(objId);

            appendToSeletedLabel(selectedObjects.length);
        }
    });
    dtable.on('deselect', function (e, dt, type, indexes) {
        if (type === 'row') {
            let data = dtable.rows( indexes ).data().pluck( 'id' );

            let objId = data[0];

            selectedObjects = removeAElement(selectedObjects, objId);

            appendToSeletedLabel(selectedObjects.length);
        }
    });
    tableName = 'sliders';
    swlIcon = langTextSelector.attr('swl-icon-warning-text');

    $.fn.dataTable.defaults.columnDefs = columnDefs;
    $.fn.dataTable.defaults.order = colOrder;

    // dtable = initDataTable();

    /* Change status of slide item */

    $('.status-checkbox').on('change', function () {
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
                    dtable.$(`td[scope="checkbox"]`).each(function () {
                        let rowCheckbox = $(this).find('input');
                        if (rowCheckbox.is(':checked')) {
                            let rowSelector = rowCheckbox.closest('tr');
                            deleteRowRecord(
                                route(`sliders.destroy`, {slider: rowCheckbox.val()}),
                                {},
                                rowSelector,
                            );
                        }
                    });
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
        function (res) {
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
            window.parent.hideLoading(row);
        },
        function (res) {
            targetBtn.prop('checked', !targetBtn.prop('checked'));

            let errorMsg = res.responseJSON.message;

            errorMessage(errorMsg);

            window.parent.hideLoading(row);
        },
    );
}
