$(document).ready(function () {
    "use strict";

    tableName = 'times';

    $.fn.dataTable.defaults.aoColumns = aoColumns;
    $.fn.dataTable.defaults.columnDefs = columnDefs;
    $.fn.dataTable.defaults.order = colOrder;

    /* Draw data table */
    dtable = serverSideDatatable(getTimesUrl, invisibleCols);

    /* When dt row is select */
    dtable.on('select.dt', function () {
        handleDtRowSelect(dtable);
    });

    /* When dt row is deselect */
    dtable.on('deselect.dt', function ( ) {
        handleDtRowDeselect(dtable);
    });

    /* Processing overlay */
    dtable.on('processing.dt', function(e, settings, processing) {
        $(`#${tableName}_ajax_dt_processing`).css('display', 'none');
        if (processing) {
            screenLoader();
        } else {
            screenLoader(0);
        }
    });


    /* Delete film */
    $(`#${tableName}_ajax_dt tbody`).on('click', '#deleteBtn', function () {
        let self = $(this);
        let url = self.attr('url');
        let tr = self.closest('tr');
        showYesNoModal(swlTitle, swlSingDeleteText, swlIcon, function () {
            singleDeleteRecord(
                url,
                {},
                tr
            );
        } );
    });

    /* Multi destroy */
    let multiDeleteBtn = $('._delete-action');
    if (multiDeleteBtn.length > 0) {
        multiDeleteBtn.on('click', function () {
            if (selectedObjects.length > 0) {
                showYesNoModal(swlTitle, swlMultiDeleteText, swlIcon, function () {
                    let exeUrl = route(`times.massDestroy`),
                        data = {times: selectedObjects};
                    executeRequest(exeUrl, 'DELETE', data, null, true);
                });
            } else {
                /** If not select any row, then show a alert */
                normalAlert(errorTitle, errorText);
            }
        })
    }
});
