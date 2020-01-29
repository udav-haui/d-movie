jQuery(document).ready(function ($) {
    let langText = $('.lang-text'),
        table = 'users';
    let rolesDataTable = $(`#${table}_data`);
    let title = langText.attr('swl-title-text'),
        text = langText.attr('swl-text-text'),
        icon = langText.attr('swl-icon-text'),
        confirmButtonText = langText.attr('swl-confirmButtonText'),
        cancelButtonText = langText.attr('swl-cancelButtonText'),
        mainLang = langText.attr('main-lang');
    rolesDataTable.DataTable({
        initComplete: function (settings, json) {
            let dataWrapper = $(`#${table}_data_wrapper`),
                selectDropdown = dataWrapper.find(`#${table}_data_length`),
                inputFilter = dataWrapper.find(`#${table}_data_filter`),
                dropdown = selectDropdown.find(`select`) || null,
                filterInput = inputFilter.find(`input`) || null;
            dropdown.addClass('dmovie-textbox-border h-32 p-l-10');
            filterInput.addClass('dmovie-textbox-border h-32 p-l-10');
            // console.log(dropdown);
        },
        columnDefs: [
            {
                targets: 0,
                width: '2%'
            },
            {
                targets: 2,
                width: '15%'
            },
            {
                targets: 'no-sort',
                orderable: false
            },
        ],
        order: [
            [1, 'desc']
        ],
        oLanguage: {
            sUrl: `/adminhtml/assets/plugins/datatables/i18n/${mainLang}.json`
        }
    });

    /**
     * Delete a user
     */
    $(`#${table}_data tbody`).on('click', '#deleteUserBtn', function () {
        let self = $(this);
        let tr = self.closest('tr');
        let row = $(`#${table}_data`).DataTable().row(tr);
        let userId = self.attr('data-id');
        window.parent.showYesNoModal(title, text, icon, confirmButtonText, cancelButtonText, function () {
            $.ajax({
                url: route('users.destroy', {user: userId}).url(),
                method: 'DELETE',
                beforeSend: function () {
                    window.parent.showLoading(tr);
                },
                datatype: 'json',
                success: function (res) {
                    if (res.status === 200) {
                        row.remove().draw();
                        window.parent.successMessage(res.message);
                    } else {
                        window.parent.errorMessage(res.message);
                    }
                    window.parent.hideLoading(tr);
                },
                error: function () {
                    window.parent.hideLoading(tr);
                }
            });
        } );
    });
});

/**
 * Change status of user
 *
 * @param recordRow
 * @param uid
 * @param title
 * @param notActive
 * @param notVerify
 * @param isActive
 */
function changeStatus(recordRow ,uid, title, notActive, notVerify, isActive) {
    let self = $(recordRow),
        currentState = self.attr('data-id'),
        cancelText = self.attr('cancel-text'),
        row = self.closest('tr');

    Swal.fire({
        title: title,
        input: 'select',
        inputOptions: {
            '-1': notActive,
            '0': notVerify,
            '1': isActive
        },
        inputValue: currentState,
        customClass: {
            popup: 'border-radius-0',
            input: 'margin-0-auto width-100'
        },
        showCancelButton: true,
        cancelButtonText: cancelText,
        inputValidator: function (value) {
            return new Promise(function (resolve, reject) {
                if (value !== '') {
                    resolve();
                }
            });
        }
    }).then(function (result) {
        if (result.value) {
            let newState = result.value;
            $.ajax({
                url: route('users.changeState'),
                method: 'POST',
                data: {
                    user: uid,
                    state: newState
                },
                datatype: 'json',
                beforeSend: function() {
                    window.parent.showLoading(row);
                },
                success: function (res) {
                    if (res.status === 200) {
                        window.parent.successMessage(res.message);
                        let statusCol = row.find('td[scope="status"]');
                        self.attr('data-id', newState);

                        statusCol.find('.status-text').text(
                            newState === '-1' ? notActive : (newState === '0' ? notVerify : isActive)
                        );
                    } else {
                        window.parent.errorMessage(res.message);
                    }
                    window.parent.hideLoading(row);
                },
                error: function () {
                    window.parent.hideLoading(row);
                }
            });
        }
    });
}


