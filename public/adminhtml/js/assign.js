$(document).ready(function () {
    let selectedUser = $('#user_id_select2'),
        langText = $('.lang-text'),
        placeholderText = langText.attr('placeholderText');

    selectedUser.select2({
        placeholder: placeholderText,
        containerCssClass: 'dmovie-select2-selection-border',
        pagination: {
            more: true,
        },
        ajax: {
            url: route('user.getUsers'),
            type: 'get',
            dataType: 'json',
            delay: 250,
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
                            text: val.name == null || val.name === '' ? `ID: ${val.id} - Chưa cập nhật tên` : val.name
                        };
                    })
                }
            }
        }
    });
});
