$(document).ready(function () {
    let selectedUser = $('#user_id_select2'),
        selectedRole = $('#role_select2'),
        langText = $('.lang-text'),
        placeholderUsersText = langText.attr('placeholderUsersText'),
        placeholderRoleText = langText.attr('placeholderRoleText'),
        unnamed = langText.attr('unnamed'),
        oldRoleId = selectedRole.attr('oldRoleId') || null,
        dmovieUserSelectClass = 'dmovie-users-select2',
        dmovieRoleSelectClass = 'dmovie-role-select2';
    let invalidClass = selectedUser.hasClass('invalid') ? 'invalid border-none' : '';
    selectedUser.select2({
        placeholder: placeholderUsersText,
        containerCssClass: dmovieUserSelectClass + ' dmovie-select2-selection-border ' + invalidClass,
        pagination: {
            more: true,
        },
        ajax: {
            url: route('users.getUsers'),
            type: 'get',
            dataType: 'json',
            delay: 250,
            beforeSend: function () {
                window.parent.showLoading($('.' + dmovieUserSelectClass));
            },
            data: function (params) {
                return {
                    name: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (val, i) {
                        return {
                            id: val.id,
                            text: val.name == null || val.name === '' ? `ID: ${val.id} - ${unnamed}` : val.name
                        };
                    })
                }
            },
            success: function () {
                window.parent.hideLoading($('.' + dmovieUserSelectClass));
            },
            error: function (res) {
                window.parent.hideLoading($('.' + dmovieUserSelectClass));
            }
        }
    });
    invalidClass = selectedRole.hasClass('invalid') ? 'invalid border-none' : '';
    if (oldRoleId !== null) {
        $.ajax({
            url: route('roles.getRole', {role: oldRoleId}),
            type: 'get',
            dataType: 'json',
            beforeSend: function () {
                window.parent.showLoader();
            },
            success: function (res) {
                let $selectedRole = $("<option selected='selected'></option>").val(oldRoleId).text(res.data.role_name);
                selectedRole.append($selectedRole).trigger('change');
                window.parent.showLoader(0);
            }
        });
    }
    selectedRole.select2({
        placeholder: placeholderRoleText,
        containerCssClass: dmovieRoleSelectClass + ' dmovie-select2-selection-border ' + invalidClass,
        pagination: {
            more: true,
        },
        ajax: {
            url: route('roles.getRoles'),
            type: 'get',
            dataType: 'json',
            delay: 250,
            beforeSend: function () {
                window.parent.showLoading($('.' + dmovieRoleSelectClass));
            },
            success: function () {
                window.parent.hideLoading($('.' + dmovieRoleSelectClass));
            },
            error: function () {
                window.parent.hideLoading($('.' + dmovieRoleSelectClass));
            },
            data: function (params) {
                return {
                    role_name: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (val, i) {
                        return {
                            id: val.id,
                            text: val.role_name == null || val.role_name === '' ? `ID: ${val.id} - ${unnamed}` : val.role_name
                        };
                    })
                }
            }
        }
    });
});
