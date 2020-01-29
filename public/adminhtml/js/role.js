$(document).ready(function () {
    let langText = $('.lang-text'),
        roles = 'roles';
    let rolesDataTable = $(`#${roles}_data`);
    let title = langText.attr('swl-title-text'),
        text = langText.attr('swl-text-text'),
        icon = langText.attr('swl-icon-text'),
        confirmButtonText = langText.attr('swl-confirmButtonText'),
        cancelButtonText = langText.attr('swl-cancelButtonText'),
        mainLang = langText.attr('main-lang');
    rolesDataTable.DataTable({
        initComplete: function (settings, json) {
            let dataWrapper = $(`#${roles}_data_wrapper`),
                selectDropdown = dataWrapper.find(`#${roles}_data_length`),
                inputFilter = dataWrapper.find(`#${roles}_data_filter`),
                dropdown = selectDropdown.find(`select`) || null,
                filterInput = inputFilter.find(`input`) || null;
            dropdown.addClass('dmovie-textbox-border h-32 p-l-10');
            filterInput.addClass('dmovie-textbox-border h-32 p-l-10');
            // console.log(dropdown);
        },
        columnDefs: [
            {
                targets: 0,
                width: '5%'
            },
            {
                targets: 2,
                width: '15%'
            },
            {
                targets: 'no-sort',
                orderable: false
            }
        ],
        oLanguage: {
            sUrl: `/adminhtml/assets/plugins/datatables/i18n/${mainLang}.json`
        }
    });

    /**
     * Delete a role
     */
    $('#roles_data tbody').on('click', '#deleteRoleBtn', function () {
        let self = $(this);
        let url = self.attr('url');
        let tr = self.closest('tr');
        let row = $('#roles_data').DataTable().row(tr);
        let roleId = self.attr('data-id');
        window.parent.showYesNoModal(title, text, icon, confirmButtonText, cancelButtonText, function () {
            $.ajax({
                url: url,
                method: 'DELETE',
                data: {
                    role: roleId
                },
                datatype: 'json',
                success: function (res) {
                    if (res.status === 200) {
                        row.remove().draw();
                        window.parent.successMessage(res.message);
                    } else {
                        window.parent.errorMessage(res.message);
                    }
                }
            });
        } );
    });
});


