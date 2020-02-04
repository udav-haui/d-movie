$(document).ready(function () {
    let langText = $('.lang-text'),
        roles = 'roles';
    let rolesDataTable = $(`#${roles}_data`);
    let title = langText.attr('swl-title-text'),
        text = langText.attr('swl-text-text'),
        icon = langText.attr('swl-icon-warning-text'),
        confirmButtonText = langText.attr('swl-confirmButtonText'),
        cancelButtonText = langText.attr('swl-cancelButtonText');
    $.fn.dataTable.defaults.columnDefs = [
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
    ];
    initDataTable(rolesDataTable, roles);

    /* Delete role */
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


