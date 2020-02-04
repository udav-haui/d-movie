$(document).ready(function () {
    'use strict';

    let langText = $('.lang-text'),
        tableName = 'sliders',
        title = langText.attr('swl-title-text'),
        icon = langText.attr('swl-icon-warning-text'),
        text = langText.attr('swl-slider-delete-text'),
        confirmButtonText = langText.attr('swl-confirmButtonText'),
        cancelButtonText = langText.attr('swl-cancelButtonText');
    let dtableSelector = $(`#${tableName}_data`);

    $.fn.dataTable.defaults.columnDefs = columnDefs;
    $.fn.dataTable.defaults.order = colOrder;

    window.parent.dtable = initDataTable(dtableSelector, tableName);

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
        let row = dtable.row(tr);
        let id = self.attr('data-id');
        window.parent.showYesNoModal(title, text, icon, confirmButtonText, cancelButtonText, function () {
            ajaxRequest(
                url,
                'DELETE',
                {
                    slider: id
                },
                function (res) {
                    showLoading(tr);
                },
                function (res) {
                    if (res.status === 200) {
                        row.remove().draw();
                        successMessage(res.message);
                    } else {
                        hideLoading(tr);
                        errorMessage(res.message);
                    }
                },
                function (res) {
                    hideLoading(tr);
                }
            );
        } );
    });

    /* Multi delete */
    let multiDeleteBtn = $('._delete-sliders');
    if (multiDeleteBtn.length > 0) {
        let swalText = multiDeleteBtn.attr('swl-text');
        multiDeleteBtn.on('click', function () {
            if (selectedObjects.length > 0) {
                window.parent.showYesNoModal(title, swalText, icon, confirmButtonText, cancelButtonText, function () {
                    let count = 0;
                    dtable.$(`td[scope="checkbox"]`).each(function () {
                        let checkbox = $(this).find('input');
                        if (checkbox.is(':checked')) {
                            let userId = checkbox.val();
                            let tr = checkbox.closest('tr'),
                                row = dtable.row(tr);
                            $(document).ajaxStart(function () {
                                window.parent.showLoading(dtableSelector);
                            });
                            $.ajax({
                                url: route('users.destroy', {user: userId}).url(),
                                method: 'DELETE',
                                datatype: 'json',
                                success: function (res) {
                                    if (res.status === 200) {
                                        selectedObjects = removeAElement(selectedObjects, userId);

                                        row.remove().draw();
                                    }
                                }
                            });
                            $(document).ajaxStop(function () {
                                window.parent.hideLoading(dtableSelector);
                            });
                            count++;
                        }
                    });
                    $(document).ajaxSuccess(function () {
                        let message = langText.attr('users-deleted');

                        window.parent.successMessage(message + count + ' users.');

                        appendToSeletedLabel(selectedObjects.length);
                    });
                });
            } else {
                /** If not select any row, then show a alert */
                window.parent.normalAlert(errorTitle, errorText);
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
        500
    );
}
