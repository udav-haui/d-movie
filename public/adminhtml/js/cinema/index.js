$(document).ready(function () {
    'use strict';
    var template = Handlebars.compile($("#shows-template").html()),
        detailDt = null;

    tableName = 'cinemas';
    swlIcon = langTextSelector.attr('swl-icon-warning-text');

    $.fn.dataTable.defaults.aoColumns = aoColumns;
    $.fn.dataTable.defaults.columnDefs = columnDefs;
    $.fn.dataTable.defaults.order = colOrder;

    /* Draw data table */
    dtable = serverSideDatatable(route('cinemas.index'), invisibleCols);

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

    /* Add event listener for opening and closing details */
    $(`#${tableName}_ajax_dt tbody`).on('click', 'td.get-shows-control', function () {
        let toggleIcon = $(this).find('i');
        if (toggleIcon.hasClass('fa-angle-double-right')) {
            toggleIcon.removeClass('fa-angle-double-right').addClass('fa-angle-double-down');
        } else {
            toggleIcon.removeClass('fa-angle-double-down').addClass('fa-angle-double-right');
        }
        var tr = $(this).closest('tr');
        var row = dtable.row(tr);
        var tableId = 'details-dt-shows-' + row.data().id;

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            tr.next().removeClass('dmovie-border-top');
        } else {
            // Open this row
            row.child(template(row.data())).show();
            initTable(tableId, row.data());
            tr.addClass('shown');
            tr.next().next().addClass('dmovie-border-top');
            tr.next().find('td').addClass('no-padding bg-gray');
        }
    });


    /* Delete cinema */
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
                    let exeUrl = route(`cinemas.massDestroy`),
                        data = {cinemas: selectedObjects};
                    executeRequest(exeUrl, 'DELETE', data, null, true);
                });
            } else {
                /** If not select any row, then show a alert */
                normalAlert(errorTitle, errorText);
            }
        })
    }


    /* Change status switch */
    $(document).on('change', '[dmovie-switch-dt]', function () {
        let self = $(this);

        self.is(':checked') ? self.val(1) : self.val(0);

        let cinema = self.attr('data-id'),
            exeUrl = route('cinemas.massUpdate'),
            data = {
                cinemas: [cinema],
                fields: {
                    status: self.val()
                }
            };

        executeRequest(exeUrl, 'PUT', data, null, true);

    });


    /* Change status of films */
    let multiChangeStatus = $('._change-status');
    if (multiChangeStatus.length > 0) {
        multiChangeStatus.click(function () {
            swlText = $(this).attr('swl-text');
            if (selectedObjects.length > 0) {
                showYesNoModal(swlTitle, swlText, swlIcon, function () {
                    let statusSelectionTitle = langTextSelector.attr('swl-select-status-title'),
                        enableText = langTextSelector.attr('enable-text'),
                        disableText = langTextSelector.attr('disable-text'),
                        options = {
                            '0': disableText,
                            '1': enableText
                        };
                    showEnableDisableAlert(
                        statusSelectionTitle,
                        options,
                        0,
                        function (newStatus) {
                            let exeUrl = route('cinemas.massUpdate'),
                                data = {
                                    cinemas: selectedObjects,
                                    fields: {
                                        status: newStatus
                                    }
                                };
                            try {
                                ajaxRequest(
                                    exeUrl,
                                    'PUT',
                                    data,
                                    function () {
                                        screenLoader();
                                    },
                                    function (res) {
                                        if (res.status === 200) {
                                            selectedObjects.forEach(value => {
                                                dtable.$(`td[scope="status"]`).each(function () {
                                                    let checkbox = $(this).find('input'),
                                                        id = checkbox.val();
                                                    if (id === value) {
                                                        let switcher = checkbox,
                                                            isChecked = parseInt(newStatus) === 1;

                                                        switcher.prop('checked', isChecked);

                                                        $(this).find('.status-text').text(parseInt(newStatus) === 1 ? enableText : disableText);

                                                    }
                                                });
                                            });

                                            successMessage(res.message);
                                        } else {
                                            errorMessage(res.message);
                                        }
                                    },
                                    function (res) {
                                        let errorMsg = res.responseJSON.message;
                                        errorMessage(errorMsg);
                                    },
                                    function () {
                                        screenLoader(0);
                                    }
                                );
                            } catch (e) {
                                console.log(e);
                            }
                        }
                    );
                });
            } else {
                normalAlert(errorTitle, errorText);
            }

        });
    }
});

function initTable(tableId, data) {

    $.fn.dataTable.defaults.aoColumns = detailsAoCol;
    $.fn.dataTable.defaults.columnDefs = detailsColumnDefs;
    $.fn.dataTable.defaults.order = [[0, 'asc']];

    detailDt = serverSideDatatable(data.shows_url, [], tableId);

    /* When dt row is select */
    detailDt.on('select.dt', function () {
        handleDetailsDtRowSelect(detailDt);

    });

    /* When dt row is deselect */
    detailDt.on('deselect.dt', function ( ) {
        handleDetailsDtRowDeselect(detailDt);

    });


    /* Change status switch */
    $(document).on('change', '[dmovie-details-dt]', function () {
        let self = $(this);

        self.is(':checked') ? self.val(1) : self.val(0);

        let show = self.attr('data-id'),
            exeUrl = route('shows.massUpdate'),
            data = {
                shows: [show],
                fields: {
                    status: self.val()
                }
            };

        executeRequest(exeUrl, 'PUT', data, detailDt);

    });

    /* Delete cinema */
    $(`#${tableId}_ajax_dt tbody`).on('click', '#detailDeleteBtn', function () {
        let self = $(this);
        let url = self.attr('url');
        let tr = self.closest('tr');
        console.log(self);
        showYesNoModal(swlTitle, swlSingDeleteText, swlIcon, function () {

            executeRequest(url, 'DELETE', {}, detailDt);

            // singleDeleteRecord(
            //     url,
            //     {},
            //     tr
            // );
        } );
    });
}

/* Handle when details dt row be select */
function handleDetailsDtRowSelect (table) {
    table.rows('.selected').every(function(rowIdx) {
        let objId = table.row(rowIdx).data().id;
        if (!detailsSelectedObjects.includes(objId)) {
            detailsSelectedObjects.push(objId);
        }
    });
}

/* Handle when details dt row be deselect */
function handleDetailsDtRowDeselect (table) {
    table.rows({ selected: false }).every(function(rowIdx) {
        detailsSelectedObjects = removeAElement(detailsSelectedObjects, table.row(rowIdx).data().id);
    });
}
