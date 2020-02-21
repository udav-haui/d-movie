$(document).ready(function () {
    'use strict';


    tableName = 'contacts';

    $.fn.dataTable.defaults.aoColumns = aoColumns;
    $.fn.dataTable.defaults.columnDefs = columnDefs;
    $.fn.dataTable.defaults.order = colOrder;

    dtable = serverSideDatatable(route('contacts.index'), invisibleCols);


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

    /* Delete page */
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
                    let exeUrl = route(`contacts.massDestroy`),
                        data = {contacts: selectedObjects};
                    executeRequest(exeUrl, 'DELETE', data, null, true);
                });
            } else {
                /** If not select any row, then show a alert */
                normalAlert(errorTitle, errorText);
            }
        })
    }


    /* Change status switch */
    $(document).on('change', '[dmovie-switch--dt]', function () {
        let self = $(this);

        self.is(':checked') ? self.val(1) : self.val(0);

        let contact = self.attr('data-id'),
            exeUrl = route('contacts.massUpdate'),
            data = {
                contacts: [contact],
                fields: {
                    status: self.val()
                }
            };

        executeRequest(exeUrl, 'PUT', data, null, true);

    });


    /* Change status of films */
    let multiChangeStatus = $('._change-status-action');
    if (multiChangeStatus.length > 0) {
        multiChangeStatus.click(function () {
            let self = $(this);
            swlText = self.attr('swl-text');
            if (selectedObjects.length > 0) {
                showYesNoModal(swlTitle, swlText, swlIcon, function () {
                    let statusSelectionTitle = langTextSelector.attr('swl-select-status-title'),
                        contactedText = self.attr('swl-select-contacted-item'),
                        pendingText = self.attr('swl-select-pending-item'),
                        options = {
                            '0': pendingText,
                            '1': contactedText
                        };
                    showEnableDisableAlert(
                        statusSelectionTitle,
                        options,
                        0,
                        function (newStatus) {
                            let exeUrl = route('contacts.massUpdate'),
                                data = {
                                    contacts: selectedObjects,
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

                                                        $(this).find('.status-text').text(parseInt(newStatus) === 1 ? contactedText : pendingText);

                                                    }
                                                });
                                            });

                                            topRightAlert(res.message);

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

    let $sendContactMail = $('._change-send-mail-action');
    if ($sendContactMail.length > 0) {
        $sendContactMail.on('click', function () {
            let mails = '';
            dtable.rows({selected: true}).every(function (rowIdx) {
                mails += ',' + $(dtable.row(rowIdx).data().contact_email).text();

            });

            if (mails === '') {
                normalAlert(errorTitle, errorText); return false;
            }
            window.open('https://mail.google.com/mail/?view=cm&fs=1&to='+mails, '_blank');
        });
    }
});
