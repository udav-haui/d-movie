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

    $.fn.dataTable.defaults.columnDefs = [
        {
            targets: 0,
            width: '2%'
        },
        {
            targets: 1,
            width: "3%"
        },
        {
            targets: [2,4],
            width: '25%'
        },
        {
            targets: 3,
            width: '5%'
        },
        {
            targets: 6,
            width: '1%'
        },
        {
            targets: 'no-sort',
            orderable: false
        },
    ];
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
            $.ajax({
                url: url,
                method: 'DELETE',
                data: {
                    slider: id
                },
                datatype: 'json',
                beforeSend: function () {
                    showLoading(tr);
                },
                success: function (res) {
                    if (res.status === 200) {
                        row.remove().draw();
                        successMessage(res.message);
                    } else {
                        errorMessage(res.message);
                    }

                    // hideLoading(tr);
                },
                error: function () {
                    // hideLoading(tr);
                }
            });
        } );
    });
});

/**
 * Change item status
 *
 * @param targetBtn
 * @param itemID
 * @param newStatus
 */
function changeItemStatus(targetBtn, row, itemID, newStatus) {
    $.ajax({
        url: route('sliders.changeStatus', {slider: itemID}),
        method: 'POST',
        data: {
            status: newStatus
        },
        delay: 500,
        datatype: 'json',
        beforeSend: function() {
            window.parent.showLoading(row);
        },
        error: function (res) {
            targetBtn.prop('checked', !targetBtn.prop('checked'));

            let errorMsg = res.responseJSON.message;

            errorMessage(errorMsg);

            window.parent.hideLoading(row);
        },
        success: function (res) {
            if (res.status === 200) {
                window.parent.successMessage(res.message);

                let statusCol = row.find('td[scope="status"]');

                statusCol.find('.status-text').text(res.data.text);
            } else {
                window.parent.errorMessage(res.message);
            }
            window.parent.hideLoading(row);
        }
    });
}
