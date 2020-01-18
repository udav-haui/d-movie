$(document).ready(function () {
    let rolesDataTable = $('#roles_data');
    let lengthMenu = rolesDataTable.attr('lengthMenu'),
        zeroRecords = rolesDataTable.attr('zeroRecords'),
        info = rolesDataTable.attr('info'),
        inforEmpty = rolesDataTable.attr('infoEmpty'),
        infoFiltered = rolesDataTable.attr('infoFiltered'),
        search = rolesDataTable.attr('search');
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
            'search': search
        }
    });
});
