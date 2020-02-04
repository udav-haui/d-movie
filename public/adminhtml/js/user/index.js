$(document).ready(function () {
    let langText = $('.lang-text'),
        tableName = 'users';
    let dtableSelector = $(`#${tableName}_data`);
    let title = langText.attr('swl-title-text'),
        text = langText.attr('swl-text-text'),
        icon = langText.attr('swl-icon-warning-text'),
        confirmButtonText = langText.attr('swl-confirmButtonText'),
        cancelButtonText = langText.attr('swl-cancelButtonText'),
        errorTitle = langText.attr('swl-error-title'),
        errorText = langText.attr('swl-error-text-must-select-one-record');
    $.fn.dataTable.defaults.columnDefs = [
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
    ];
    window.parent.dtable = initDataTable(dtableSelector, tableName);

    /**
     * Delete a user
     */
    $(`#${tableName}_data tbody`).on('click', '#deleteUserBtn', function () {
        let self = $(this);
        let tr = self.closest('tr');
        let row = $(`#${tableName}_data`).DataTable().row(tr);
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
                        let chkbox = tr.find('input[type="checkbox"]');

                        if (chkbox.is(':checked')) {
                            selectedObjects = removeAElement(selectedObjects, chkbox.val());
                            appendToSeletedLabel(selectedObjects.length);
                        }

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

    /**
     * Delete users action
     */
    let deleteUsersBtn = $('._delete-users');
    if (deleteUsersBtn.length > 0) {
        let swalText = deleteUsersBtn.attr('swl-text');
        deleteUsersBtn.on('click', function () {
            if (selectedObjects.length > 0) {
                window.parent.showYesNoModal(title, swalText, icon, confirmButtonText, cancelButtonText, function () {
                    let count = 0;
                    dtable.$(`td[scope="checkbox"]`).each(function () {
                        let checkbox = $(this).find('input');
                        if (checkbox.is(':checked')) {
                            let userId = checkbox.val();
                            let tr = checkbox.closest('tr'),
                                row = dtable.row(tr);
                            $(document).ajaxStart(function () {
                                window.parent.showLoading(dtableSelector);
                            });
                            $.ajax({
                                url: route('users.destroy', {user: userId}).url(),
                                method: 'DELETE',
                                datatype: 'json',
                                success: function (res) {
                                    if (res.status === 200) {
                                        selectedObjects = removeAElement(selectedObjects, userId);

                                        row.remove().draw();
                                    }
                                }
                            });
                            $(document).ajaxStop(function () {
                                window.parent.hideLoading(dtableSelector);
                            });
                            count++;
                        }
                    });
                    $(document).ajaxSuccess(function () {
                        let message = langText.attr('users-deleted');

                        window.parent.successMessage(message + count + ' users.');

                        appendToSeletedLabel(selectedObjects.length);
                    });
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
        let swalText = changeStateBtn.attr('swl-text');
        changeStateBtn.on('click', function () {
            let self = changeStateBtn;
            if (selectedObjects.length > 0) {
                window.parent.showYesNoModal(title, swalText, icon, confirmButtonText, cancelButtonText, function () {
                    let swlTitle = self.attr('swl-state-alert-title'),
                        swlSlNotActive = self.attr('swl-select-not-active-item'),
                        swlSlNotVerify = self.attr('swl-select-not-verify-item'),
                        swlSlActive = self.attr('swl-select-active-item'),
                        swlCancelBtnText = self.attr('swl-cancel-btn-text');

                    showStateAlert(swlTitle, swlSlNotActive, swlSlNotVerify, swlSlActive, 1, swlCancelBtnText, function (newState) {
                        dtable.$('i[scope="change-state"]').each(function () {
                            let targetBtn = $(this),
                                userId = targetBtn.attr('user-id');

                            $.each(selectedObjects, function (index, value) {
                                if (userId === value) {
                                    let row = targetBtn.closest('tr');

                                    doChangeState(targetBtn, row, value, newState, swlSlNotActive, swlSlNotVerify, swlSlActive);
                                }
                            });
                        });
                    });
                });
            } else {
                window.parent.normalAlert(errorTitle, errorText)
            }
        });
    }
    /** ./ END */

    /**
     * Assign role to selected users
     */
    let assignRoleBtn = $('._assign-role-users');
    if (assignRoleBtn.length > 0) {
        let swalText = assignRoleBtn.attr('swl-text');
        assignRoleBtn.on(
            'click',
            function () {
                let self = assignRoleBtn;
                if (selectedObjects.length > 0) {
                    window.parent.showYesNoModal(title, swalText, icon, confirmButtonText, cancelButtonText, function () {
                        let htmlText = `<div class="col-md-12">
                                            <select id="role_select2" oldRoleId="0" name="role" class="form-control"></select>
                                            <span class="error text-danger dmovie-error-box display-none"></span>
                                        </div>`,
                            sl2Placeholder = self.attr('sl2-placeholder'),
                            selectRole = null;
                        Swal.fire({
                            title: sl2Placeholder,
                            html: htmlText,
                            showCancelButton: true,
                            cancelButtonText: cancelButtonText,
                            customClass: {
                                popup: 'border-radius-0'
                            },
                            focusConfirm: false,
                            onOpen: function () {
                                let dmovieRoleSelectClass = 'dmovie-role-select2',
                                    unnamed = self.attr('unnamed');
                                selectRole = $('#role_select2');
                                /** Init list role */
                                window.parent.loadRoleSelect2(selectRole, sl2Placeholder, dmovieRoleSelectClass, '',unnamed);

                            },
                        }).then((result) => {
                            if (result.value) {
                                let roleId = selectRole.val();

                                if (roleId !== null) {
                                    let roleName = selectRole.text();
                                    dtable.$('input[type="checkbox"]').each(function () {
                                        let targetBtn = $(this),
                                            userId = targetBtn.val();

                                        $.each(selectedObjects, function (index, value) {
                                            if (userId === value) {
                                                let row = targetBtn.closest('tr');

                                                doAssignRole(row, userId, roleId, roleName);
                                            }
                                        });
                                    });

                                } else {
                                    let selectRoleErrorText = langText.attr('swl-sl-role-error-text');
                                    window.parent.normalAlert(errorTitle, selectRoleErrorText);
                                }
                            }
                        });
                    });
                } else {
                    window.parent.normalAlert(errorTitle, errorText);
                }
            }
        );
    }
    /** ./END */

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

    showStateAlert(title, notActive, notVerify, isActive, currentState, cancelText, function (newState) {
        doChangeState(self, row, uid, newState, notActive, notVerify, isActive);
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
 * @param targetBtn
 * @param row
 * @param uid
 * @param newState
 * @param notActive
 * @param notVerify
 * @param isActive
 */
function doChangeState(targetBtn, row, uid, newState, notActive, notVerify, isActive) {
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
                targetBtn.attr('data-id', newState);

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
/** ./End */

function doAssignRole(row, userId, roleId, newRoleName) {
    $.ajax({
        url: route('roles.doSingAssign'),
        method: 'POST',
        data: {
            user: userId,
            role: roleId
        },
        datatype: 'json',
        beforeSend: function() {
            window.parent.showLoading(row);
        },
        success: function (res) {
            if (res.status === 200) {
                window.parent.successMessage(res.message);

                let roleNameCol = row.find('td[scope="role"]');

                roleNameCol.text(newRoleName);
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
