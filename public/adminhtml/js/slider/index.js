$(document).ready(function () {
    'use strict';

    let langText = $('.lang-text'),
        tableName = 'sliders';
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
            targets: 2,
            width: '5%'
        },
        {
            targets: 5,
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
        url: route('sliders.changeStatus'),
        method: 'POST',
        data: {
            slider: itemID,
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
