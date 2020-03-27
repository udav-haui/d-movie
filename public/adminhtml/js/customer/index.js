$(document).ready(function () {
    'use strict';

    tableName = 'customers';


    $.fn.dataTable.defaults.aoColumns = aoColumns;
    $.fn.dataTable.defaults.columnDefs = columnDefs;
    $.fn.dataTable.defaults.order = colOrder;

    dtable = serverSideDatatable(route('users.customer.index'), invisibleCols);

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


    /* Delete a user */
    $(`#${tableName}_ajax_dt tbody`).on('click', '#deleteUserBtn', function () {
        let self = $(this);
        let trSelector = self.closest('tr');
        let userId = self.attr('data-id');
        showYesNoModal(swlTitle, swlSingDeleteText, swlIcon, function () {
            singleDeleteRecord(
                route('users.destroy', {user: userId}).url(),
                {},
                trSelector
            );
        } );
    });


    /* Delete users action */
    let deleteUsersBtn = $('._delete-users');
    if (deleteUsersBtn.length > 0) {
        deleteUsersBtn.on('click', function () {
            if (selectedObjects.length > 0) {
                showYesNoModal(swlTitle, swlMultiDeleteText, swlIcon, function () {
                    let exeUrl = route(`users.multiDestroy`),
                        data = {ids: selectedObjects};

                    executeRequest(exeUrl, "DELETE", data, dtable,true);
                });
            } else {
                /** If not select any row, then show a alert */
                window.parent.normalAlert(errorTitle, errorText);
            }
        });
    }
    /** ./end */

    /**
     * Change state users
     */
    let changeStateBtn = $('._change-state-users');
    if (changeStateBtn.length > 0) {
        swlText = changeStateBtn.attr('swl-text');
        changeStateBtn.on('click', function () {
            let self = changeStateBtn;
            if (selectedObjects.length > 0) {
                showYesNoModal(swlTitle, swlText, swlIcon, function () {
                    swlTitle = self.attr('swl-state-alert-title');
                    let swlSlNotActive = self.attr('swl-select-not-active-item'),
                        swlSlNotVerify = self.attr('swl-select-not-verify-item'),
                        swlSlActive = self.attr('swl-select-active-item');

                    showStateAlert(swlTitle, swlSlNotActive, swlSlNotVerify, swlSlActive, 1, swlCancelBtnText, function (newState) {
                        executeRequest(
                            route('users.massUpdate'),
                            'PUT',
                            {
                                users: selectedObjects,
                                fields: {state: newState}
                            }
                        );
                    });
                });
            } else {
                window.parent.normalAlert(errorTitle, errorText)
            }
        });
    }
    /** ./ END */

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
        cancelText = self.attr('cancel-text');

    showStateAlert(title, notActive, notVerify, isActive, currentState, cancelText, function (newState) {
        doChangeState(uid, newState);
    });
}


/**
 * Fire alert change state
 *
 * @param title
 * @param notActive
 * @param notVerify
 * @param isActive
 * @param currentState
 * @param cancelText
 * @param callback
 */
function showStateAlert(title, notActive, notVerify, isActive, currentState = -1, cancelText = 'Cancel', callback) {
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
            callback(newState);
        }
    });
}
/** ./END */

/**
 * Do change state of user
 *
 * @param uid
 * @param newState
 */
function doChangeState(uid, newState) {

    executeRequest(
        route('users.massUpdate'),
        'PUT',
        {
            users: [uid],
            fields: {state: newState}
        }
    );

}
/** ./End */