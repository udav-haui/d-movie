$(document).ready(function () {
    let langText = $('.lang-text');
    let rolesDataTable = $('#roles_data');
    let lengthMenu = rolesDataTable.attr('lengthMenu'),
        zeroRecords = rolesDataTable.attr('zeroRecords'),
        info = rolesDataTable.attr('info'),
        inforEmpty = rolesDataTable.attr('infoEmpty'),
        infoFiltered = rolesDataTable.attr('infoFiltered'),
        search = rolesDataTable.attr('search'),
        prevBtn = rolesDataTable.attr('prevBtn'),
        nextBtn = rolesDataTable.attr('nextBtn'),
        title = langText.attr('swl-title-text'),
        text = langText.attr('swl-text-text'),
        icon = langText.attr('swl-icon-text'),
        confirmButtonText = langText.attr('swl-confirmButtonText'),
        cancelButtonText = langText.attr('swl-cancelButtonText');
    rolesDataTable.DataTable({
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
        language: {
            "lengthMenu": lengthMenu,
            "zeroRecords": zeroRecords,
            "info": info,
            "infoEmpty": inforEmpty,
            "infoFiltered": infoFiltered,
            'search': search,
            'paginate': {
                previous: prevBtn,
                next: nextBtn
            }
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
        showYesNoModal(title, text, icon, confirmButtonText, cancelButtonText, function () {
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

function showYesNoModal(
    title,
    text,
    icon,
    confirmButtonText,
    cancelButtonText,
    callback,
    confirmButtonColor = '#3085d6',
    cancelButtonColor = '#d33',
) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: cancelButtonColor,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        width: '640px'
    }).then((result) => {
        if (result.value) {
            callback();
        }
    })
}

