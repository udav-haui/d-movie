$(document).ready(function () {
    let selectedUser = $('#user_id_select2'),
        selectedRole = $('#role_select2'),
        langText = $('.lang-text'),
        placeholderUsersText = langText.attr('placeholderUsersText'),
        placeholderRoleText = langText.attr('placeholderRoleText'),
        unnamed = langText.attr('unnamed'),
        oldRoleId = selectedRole.attr('oldRoleId') || null;
    let invalidClass = selectedUser.hasClass('invalid') ? 'invalid border-none' : '';
    selectedUser.select2({
        placeholder: placeholderUsersText,
        containerCssClass: 'dmovie-users-select2 dmovie-select2-selection-border ' + invalidClass,
        pagination: {
            more: true,
        },
        ajax: {
            url: route('user.getUsers'),
            type: 'get',
            dataType: 'json',
            beforeSend: function () {
                $('.dmovie-users-select2').LoadingOverlay("show");
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
                $('.dmovie-users-select2').LoadingOverlay("hide");
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
        containerCssClass: 'dmovie-role-select2 dmovie-select2-selection-border ' + invalidClass,
        pagination: {
            more: true,
        },
        ajax: {
            url: route('roles.getRoles'),
            type: 'get',
            dataType: 'json',
            beforeSend: function () {
                $('.dmovie-role-select2').LoadingOverlay('show');
            },
            success: function () {
                $('.dmovie-role-select2').LoadingOverlay('hide');
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
