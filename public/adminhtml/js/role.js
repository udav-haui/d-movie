$(document).ready(function () {
    tableName = 'roles';

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
    dtable = initDataTable();

    /* Delete role */
    $('#roles_data tbody').on('click', '#deleteRoleBtn', function () {
        let self = $(this);
        let url = self.attr('url');
        let tr = self.closest('tr');
        let row = $('#roles_data').DataTable().row(tr);
        let roleId = self.attr('data-id');
        showYesNoModal(swlTitle, swlSingDeleteText, swlIcon, function () {
            $.ajax({
                url: url,
                method: 'DELETE',
                data: {
                    role: roleId
                },
                datatype: 'json',
                success: function (res) {
                    if (res.status === 200) {
                        row.remove().draw(true);
                        window.parent.successMessage(res.message);
                    } else {
                        window.parent.errorMessage(res.message);
                    }
                }
            });
        } );
    });
});


